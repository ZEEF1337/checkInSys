<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");


if(!isset($_GET['token'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$token = $_GET['token'];

$adminCheck = checkIfAdminNew($token);

if($adminCheck == 0){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}


$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');

$query = "SELECT DISTINCT u.CardID, GROUP_CONCAT(DISTINCT u.Timestamp ORDER BY u.Timestamp DESC SEPARATOR ', ') AS Timestamp,";
$query .= " substring_index(GROUP_CONCAT(u.ScannerUsed ORDER BY u.Timestamp DESC SEPARATOR ', ' ), ',', 3) AS ScannerUsed";
$query .= " FROM unknowncards AS u INNER JOIN scanners ON scanners.ID = u.ScannerUsed GROUP BY u.CardID ORDER BY Timestamp DESC;";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num > 0){
        $out = array();
        $out["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $explodedTimestamp = explode(",", $Timestamp);
            $unixTime = strtotime($explodedTimestamp[0]);
            $formattedDate = date('H:i - d-m-Y', $unixTime);

            $query2 = "SELECT Name FROM scanners WHERE scanners.ID = '$ScannerUsed[0]';";
            $stmt2 = $db->prepare($query2);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            extract($row2);

            $temp = array(
                "CardID" => $CardID,
                "Timestamp" => $formattedDate,
                "ScannerUsed" => $Name,
            );
            array_push($out["records"], $temp);
            $out['result'] = 1;
        }
    }else{
        $out['result'] = 0;
    }
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>