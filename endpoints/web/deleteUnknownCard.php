<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!$_GET['cardID']){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$cardID = $_GET['cardID'];

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');

$query = "DELETE FROM unknowncards WHERE CardID = '$cardID'";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>