<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");

if(!isset($_POST['cardID']) || !isset($_POST['scannerMAC'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$cardID = $_POST['cardID'];
$scannerMAC = $_POST['scannerMAC'];

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');
$date = new DateTime();
$week = $date->format('W');
$year = $date->format('Y');
$weekday = $date->format('l');
$currTime = $date->format("H:i");
$timestamp = $date->format('Y-m-d H:i:s');
$userID = getUserIDFromCardID($cardID);
$scannerID = getScannerIDFromScannerMAC($scannerMAC);

if(!$scannerID){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}else if(!$userID){
    $out['result'] = 0;
    $query3 = "INSERT INTO unknowncards (CardID, Timestamp, ScannerUsed) VALUES ('$cardID', '$timestamp', $scannerID);";
    $stmt3 = $db->prepare($query3);
    $stmt3->execute();
    $json = json_encode($out);
    print_r($json);
    return;
}


$query = "SELECT $weekday FROM timetable WHERE UserID = '$userID' && Week = '$week' && Year = '$year'";
$stmt = $db->prepare($query);
   
try{
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $checktime = json_decode($row[$weekday]);

        if($checktime === NULL){
            $query2 = "UPDATE timetable SET $weekday = JSON_OBJECT('in', '$currTime', 'out', '', 'scannerIDIn', '$scannerID', 'scannerIDOut', '-1') WHERE UserID = '$userID' && Week = '$week' && Year = '$year'";
            $stmt2 = $db->prepare($query2);
            $stmt2->execute();
            $out['result'] = 1;
        }else{
            if($checktime->out == ''){
                $query2 = "UPDATE timetable SET $weekday = JSON_OBJECT('in', '$checktime->in', 'out', '$currTime', 'scannerIDIn', '$checktime->scannerIDIn', 'scannerIDOut', '$scannerID') WHERE UserID = '$userID' && Week = '$week' && Year = '$year'";
                $stmt2 = $db->prepare($query2);
                $stmt2->execute();
                $out['result'] = 1;
            }else{
                $out['result'] = 0;
                $query3 = "INSERT INTO unknowncards (CardID, Timestamp, ScannerUsed) VALUES ('$cardID', '$timestamp', $scannerID);";
                $stmt3 = $db->prepare($query3);
                $stmt3->execute();
            }
        }
    }else{
        $query2 = "INSERT INTO timetable (userID, Week, Year, $weekday) VALUES ('$userID', '$week', '$year', JSON_OBJECT('in', '$currTime', 'out', '', 'scannerIDIn', '$scannerID', 'scannerIDOut', '-1'));";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute();
        $out['result'] = 1;
    }
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;

?>