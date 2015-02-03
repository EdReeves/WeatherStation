/* Arduino weatherstation V2.2 - Ed Reeves
    
   This version measures windspeed, wind direction, temperature, pressure and humidity and 
   rainfall.
   
*/

#include <JeeLib.h> // Low power functions library
#include "DHT.h" // Humidity library
#include <SFE_BMP180.h> // Pressure Library
#include <Wire.h>
#define DHTTYPE DHT22



//Pin config

const int RAIN_GND = 5; // Ground pin for rain sensor.
const int ANEMOMETER = 13;
const int RAINGAUGE = 3; 
const int VANE = 3; // analogue 3.  
const int DHTPIN = 4;
const int RADIOPIN = 12; // Used to toggle the transistor to power the radio.
const int NUMDIRS = 8;
const int MSECS_CALC_DURATION = 5000;  //milliseconds to measure windspeed for. 
unsigned long last_reading = 0;
unsigned long next_reading = 0;
volatile int raintips = 0;
int NumRevsAnemometer; 
float SpeedCalc;
unsigned long   adc[NUMDIRS] = {26, 45, 77, 118, 161, 196, 220, 256};
char *strVals[NUMDIRS] = {"W","NW","N","SW","NE","S","SE","E"};
byte dirOffset=0;

DHT dht(DHTPIN, DHTTYPE);
float humidity;
SFE_BMP180 pressure;
const int ALTITUDE = 180; //This is the altitude of the weather station. This is required for calculating relective pressure.
char status;
double T,P,p0; // Variables for pressure readings.

void setup() {
     Serial.begin(115200);
     
     pinMode(RAIN_GND, OUTPUT);
     digitalWrite(RAIN_GND, LOW);
     pinMode(RADIOPIN,OUTPUT);
     pinMode(7,OUTPUT);
     digitalWrite(7,HIGH); // Required by the radio.
     pinMode(ANEMOMETER, INPUT);
     digitalWrite(ANEMOMETER, HIGH);
     pinMode(RAINGAUGE, INPUT);
     digitalWrite(RAINGAUGE, HIGH);
     attachInterrupt(1,CountRain, LOW);
     dht.begin();
     pressure.begin();
     
     
}

void loop() {
  attachInterrupt(1,CountRain, LOW);
  broadcast();
  delay(60000);
  
  
}

//This function outputs the results.
void broadcast() { 
  
  //Serial.println(millis());
  //Serial.println("BEGIN DATA READOUT");
  calcWindSpeed();
  calcWindDir();
  TempAndPressure();
  humidity = dht.readHumidity();
  Serial.println(humidity,0);
 
  Serial.println(raintips*0.2794);
  raintips = 0;
  //Serial.println("FINSH DATA READOUT");
  
  
}

void CountRain() {
  raintips++;
  detachInterrupt(1);
}

void calcWindDir() {
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
  
   Serial.println(strVals[x]); //Output direction.
}

int calcWindSpeed() {
  
  unsigned long finish = MSECS_CALC_DURATION + millis(); 
  int pinReading;
  int lastPinReading;
  //Serial.println("Beginning wind speed check");
  while(millis() <= finish) {
  
   pinReading = digitalRead(ANEMOMETER);  
    if (pinReading == HIGH) {
        if (lastPinReading == 1) //if the switch is HIGH and the previous iteration it was HIGH it doesnt count
          {
           // Serial.println("ignoring");
          }
          else {
                //  Serial.println ("HIGH");
                  NumRevsAnemometer++;
                  lastPinReading = pinReading; 
               }
          }
         else 
         {
           //Serial.println("LOW");
           lastPinReading = pinReading;
         }  
    }
  
  
  SpeedCalc = (NumRevsAnemometer/5)*1.333;
  NumRevsAnemometer = 0;
  Serial.println(SpeedCalc);
  
}

void TempAndPressure() {
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
  Serial.println(T,2);
  Serial.println(p0,2);
}




