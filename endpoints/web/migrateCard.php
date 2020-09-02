<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");


if(!isset($_GET['cardID']) || !isset($_GET['userID']) || !isset($_GET['token'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$cardID = $_GET['cardID'];
$callerID = $_GET['userID'];
$token = $_GET['token'];

$adminCheck = checkIfAdminNew($token);

if($adminCheck == 0){
    $out['result'] = 0;
    $out['message'] = "No access";
    $json = json_encode($out);
    print_r($json);
    return;
}

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');
$out = array();
$query = "SELECT uc.Timestamp, uc.ScannerUsed, users.ID as UserID FROM unknowncards as uc";
$query .= " INNER JOIN users ON users.CardID = uc.CardID WHERE uc.CardID = '$cardID' ORDER BY Timestamp ASC;";
$stmt = $db->prepare($query);
try{
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $ddate = $Timestamp;
            $date = new DateTime($ddate);
            $weekday = $date->format('l');
            $week = $date->format("W");
            $year = $date->format("Y");
            $time = $date->format("H:i");
            
            $query2 = "SELECT * FROM timetable WHERE UserID = $UserID && Year = $year && Week = $week";
            $stmt2 = $db->prepare($query2);
            $stmt2->execute();
            $num2 = $stmt2->rowCount();
            if($num2 > 0){
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    extract($row2);
                    $checktime = json_decode($row2[$weekday]);
    
                    if($checktime === NULL){
                        $query3 = "UPDATE timetable SET $weekday = JSON_OBJECT('in', '$time', 'out', '', 'scannerIDIn', '$ScannerUsed', 'scannerIDOut', '-1') WHERE UserID = '$UserID' && Week = '$week' && Year = '$year'";
                    }else if($checktime->out == ''){
                        $query3 = "UPDATE timetable SET $weekday = JSON_OBJECT('in', '$checktime->in', 'out', '$time', 'scannerIDIn', '$checktime->scannerIDIn', 'scannerIDOut', '$ScannerUsed') WHERE UserID = '$UserID' && Week = '$week' && Year = '$year'";
                    }
                }
            }else{
                $query3 = "INSERT INTO timetable (userID, Week, Year, $weekday) VALUES ('$UserID', '$week', '$year', JSON_OBJECT('in', '$time', 'out', '', 'scannerIDIn', '$ScannerUsed', 'scannerIDOut', '-1'));";
            }
            if(isset($query3)){
                $stmt3 = $db->prepare($query3);
                $stmt3->execute();
            }
        }
        $out['result'] = 1;
    }else{
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