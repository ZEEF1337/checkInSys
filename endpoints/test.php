<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

date_default_timezone_set('Europe/Copenhagen');
$date = new DateTime();
$currTime = $date->format("H:i");
$week = $date->format('W');
$year = $date->format('Y');
$weekday = $date->format('l');
$timestamp = $date->format('Y-m-d H:i:s');
echo $currTime;
echo "   ";
echo $week;
echo "   ";
echo $year;
echo "   ";
echo $weekday;
echo "   ";
echo $timestamp;

// $cardID = $_POST['cardID'];

// $out['result'] = $cardID;

// $json = json_encode($out);
// print_r($json);
return;

?>