<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_POST['MAC']) || !isset($_POST['userID']) || !isset($_POST['name']) || !isset($_POST['token'])){
    $out['result'] = 0;
    $out['message'] = "Missing param";
    $json = json_encode($out);
    print_r($json);
    return;
}

$givenName = $_POST['name'];
$givenMAC = $_POST['MAC'];
$userID = $_POST['userID'];
$token = $_POST['token'];

$adminCheck = checkIfAdmin($token);

if($adminCheck == 0){
    $out['result'] = 0;
    $out['message'] = "No access";
    $json = json_encode($out);
    print_r($json);
    return;
}

$database = new Database();
$db = $database->getConnection();


$query = "INSERT INTO scanners (MAC, Name) VALUES";
$query .= " ('$givenMAC', '$givenName');";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
    $out['message'] = "Scanneren blev oprettet.";
    
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>