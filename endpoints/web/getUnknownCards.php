<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");


$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');

$query = "SELECT .unknowncards.CardID, unknowncards.Timestamp, scanners.Name FROM unknowncards";
$query .= " INNER JOIN scanners ON scanners.ID = unknowncards.ScannerUsed ORDER BY unknowncards.Timestamp DESC;";
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