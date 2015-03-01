

/* Arduino weatherstation V3.0 - Ed Reeves
   
   Arduino based automated weatherstation measuring windspeed, wind direction, temperature, pressure, humidity and rainfall.
   
   The system gathers data from the connected sensors and sends the data via HTTP POST to a page PHP page on a websever which inserts
   it into a MYSQL database.
   
   Sensors used:
    
    Temperature/Pressure: BMP180.
    Humidity:             DHT22. 
    Windspeed/Winddirection/Rainfall: Argent weather station assembly, available from various sellers under different names.
   
   Hardware:
  
    Arduino Mega 2560.
    Arduino Ethernet Shield. 
    
   Issues: 
   Wind measurement may still be problematic. 
*/


#include <SPI.h>
#include <Ethernet.h>
#include "DHT.h" // Humidity library
#include <SFE_BMP180.h> // Pressure Library
#include <Wire.h>
#include <sha256.h>

#define DHTTYPE DHT22


// Setup for Ethernet Library
byte mac_addr[] = { 0x00, 0xAA, 0xBB, 0xCC, 0xDE, 0x01 };
IPAddress server(178,62,14,61);
EthernetClient client;


//Pin config


const uint8_t ANEMOMETER = 2;
const uint8_t RAINGAUGE = 3; 
const uint8_t VANE = 3; // Analogue 3.  
const uint8_t DHTPIN = 4;

DHT dht(DHTPIN, DHTTYPE);

const uint8_t NUMDIRS = 8; //Wind Vane directions.
volatile int raintips = 0;
volatile int NumRevsAnemometer = 0; 
unsigned long   adc[NUMDIRS] = {26, 45, 77, 118, 161, 196, 220, 256}; //Wind Vane directions.
char *strVals[NUMDIRS] = {"W","NW","N","SW","NE","S","SE","E"}; //Wind Vane directions.
byte dirOffset=0;
String winddirection;
SFE_BMP180 pressure;
const uint8_t ALTITUDE = 180; // This is the altitude of the weather station. This is required for calculating reletive pressure.
char status;
uint8_t humidity;
double T,P,p0; // Variables for temperature pressure readings.
float rainfall;
String Data =  ""; // Data sent over HTTP to the server
String DataWind = ""; // Wind data sent over HTTP to the server
unsigned long NextTip; //The minimum time of the next expected rain gauge activation. (debounce)
unsigned long NextRev; //The minimum time of the next expected anemometer activation. (debounce)
uint8_t Last3Secs = 0; //Number of revs in the last 3 seconds.
float avgWind; //The average windspeed in the measuring period. 
float MaxGust; //The highest 3 second wind speed (Gust) in the measuring period.
float MinLull = 100; //The lowest 3 second wind speed (Lull) in the measuring period. 
unsigned long NextSecReading = 3000; // 3 second readings for wind speed.
unsigned long NextWindAvg = 5000; //10 min period for average windspeed measurement.
const uint32_t AVGPERIOD = 600000; //Time over which wind averages are taken. 600000 = 10 minutes.
unsigned long NextReading  = 0; //Time of the next temp/pressure/humidity/wind direction/rainfall readings.
const uint16_t ReadingPeriod = 60000; // Time between temp/pressure/humidity/wind direction/rainfall readings.
String KEY = ""; //REMOVED FOR GITHUB

String HashInput;
String HashString;
uint8_t* hash;

void setup() {
     
     Serial.begin(115200);
     Serial.println("Connecting");
     Ethernet.begin(mac_addr);
     Serial.println("Connected");     
     pinMode(RAINGAUGE, INPUT);
     digitalWrite(RAINGAUGE, HIGH);
     pinMode(ANEMOMETER, INPUT);
     digitalWrite(ANEMOMETER, HIGH);
     
     dht.begin();
     pressure.begin();
    
     attachInterrupt(1,CountRain, LOW);
     attachInterrupt(0,calcWindSpeed, FALLING);
    
}

void loop() {
  //Temperature, Pressure, Humidity, Wind Direction and Rainfall are taken every minute.
  //Wind readings are averaged over 10 minute periods, with 'gusts' and 'lulls' given as the highest and lowest 3 second average within the 10 minute period.
  if(millis() > NextReading) {
    Gatherdata();
    html_POST(T,humidity, p0, winddirection,rainfall);
    raintips = 0;
    NextReading = millis() + ReadingPeriod;
    Serial.println(millis());
    Serial.println(NextReading);
  }
  
  if(NextWindAvg > millis()) {
    if(NextSecReading > millis()) {
       Last3Secs = NumRevsAnemometer;
    } else {
       if (Last3Secs > MaxGust) { 
            MaxGust = Last3Secs;
       }
       if (Last3Secs <= MinLull) {
            MinLull = Last3Secs;
    }
    avgWind += NumRevsAnemometer;
    NumRevsAnemometer = 0;
    NextSecReading = millis() + 3000;
    }
  } else {
    NextWindAvg = millis() + AVGPERIOD;
    avgWind = (avgWind/600)*0.6667; //Conversion to m/s 
    MaxGust = (MaxGust/3)*0.6667; //Conversion to m/s
    MinLull = (MinLull/3)*0.6667; //Conversion to m/s
    html_POST_wind(avgWind, MaxGust, MinLull);
    avgWind = 0; //reset
    MaxGust = 0; //reset
    MinLull = 100; //reset
    
  }   
}

//This function outputs the results.
void Gatherdata() { 

  calcWindDir();
  TempAndPressure();
  humidity = dht.readHumidity();  
  rainfall = raintips * 0.2784;
  
}

void CountRain() {
  if(NextTip == 0 || NextTip < millis()) {
  raintips++;
  NextTip = millis()+200;
  }
}

String calcWindDir() {
   int val;
   byte x, reading;

   val = analogRead(VANE);
   val >>=2;                        
   reading = val;

   for (x=0; x<NUMDIRS; x++) {
      if (adc[x] >= reading)
         break;
   }
   
   x = (x + dirOffset) % 8;   
   winddirection = strVals[x];
   return winddirection;
   
}

void calcWindSpeed() {
  if(NextRev == 0 || NextRev < millis()) {
  NumRevsAnemometer++;
  NextRev = millis()+7; // This is the minimum period between activations.
 }
  
}

float TempAndPressure() {
  status = pressure.startTemperature(); 
  if (status != 0) {
      delay(status);
      status = pressure.getTemperature(T);
      if (status != 0) {
          status = pressure.startPressure(3);
          if (status != 0) {
              delay(status);
              status = pressure.getPressure(P,T);
              if (status != 0) {
                  p0 = pressure.sealevel(P,ALTITUDE); //Reletive pressure is logged rather than absolute because it allows pressure to be comparable to other locations. 
              }
          }
      }
  }
  
  return (T,2);
  return (p0,2);
  
}

void html_POST(float T, int humidity, float p0,String winddirection, float rainfall) {
 
        
  Data += F("&winddirection=");
  Data.concat(winddirection);
  Data += F("&temperature=");
  Data.concat(T);
  Data += F("&pressure=");
  Data.concat(p0);
  Data += F("&humidity=");
  Data.concat(humidity);
  Data += F("&rainfall=");
  Data.concat(rainfall);
  Data += F("&KEY=");
  HashInput = Data;
  HashInput.concat(KEY);
  Sha256.init();
  Sha256.print(HashInput);
  printHash(Sha256.result());
  Data.concat(HashString);
  Serial.println(HashInput);
  Serial.println(Data);
  senddata(Data, 1);
 
  
}

String printHash(uint8_t* hash) {
  int i;
  for (i=0; i<32; i++) {
    
    HashString += "0123456789abcdef"[hash[i]>>4];
    HashString += "0123456789abcdef"[hash[i]&0xf];
    
  }
  return HashString;
}

void senddata(String DataString, int dest) {
  String postString;
  String URL[2] = {"/addwind.php" , "/add.php"};
 
  
  if (client.connect(server,80)) { 
		Serial.println(F("connected to edreeves.co.uk"));
                postString = "POST ";
                postString.concat(URL[dest]);
                postString += " HTTP/1.1";
                client.println(postString); 
		client.println(F("Host: 178.62.14.61")); 
		client.println(F("Content-Type: application/x-www-form-urlencoded")); 
		client.print(F("Content-Length: ")); 
		client.println(DataString.length()); 
		client.println(); 
		client.print(DataString); 
	}else
        Serial.println(F("fail"));

	if (client.connected()) { 
		client.stop();	// DISCONNECT FROM THE SERVER
                
                DataWind = "";
                HashString = "";
                Data = "";
	} 
}

void html_POST_wind(float AVGwind, float MaxGust, float Minlull) {
 
  DataWind += F("&avgWind=");
  DataWind.concat(AVGwind);
  DataWind += F("&Gust=");
  DataWind.concat(MaxGust);
  DataWind += F("&Lull=");
  DataWind.concat(MinLull);
  DataWind += F("&KEY=");
  HashInput = DataWind;
  HashInput.concat(KEY);
  Sha256.init();
  Sha256.print(HashInput);
  printHash(Sha256.result());
  DataWind.concat(HashString);
  Serial.println(HashInput);
  Serial.println(DataWind);
  senddata(DataWind, 0);
  
	
}





