<?php

/**
 * Initialise generic stuff
 */
// Set PHP timezone
date_default_timezone_set('Europe/London');

$mysqli = mysqli_connect('127.0.0.1', 'xxx', 'xxx', 'xxx');

if (mysqli_connect_errno()) die('Database connection error');

define('JSON_PATH', realpath(dirname(__FILE__)).'/json');

require(realpath(dirname(__FILE__)).'/functions.php');

return $mysqli;
