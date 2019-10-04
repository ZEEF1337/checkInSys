<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_GET['deleteUserID']) || !isset($_GET['userID'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$deleteUserID = $_GET['deleteUserID'];
$userID = $_GET['userID'];

$adminCheck = checkIfAdmin($userID);

if($adminCheck == 0){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}


$database = new Database();
$db = $database->getConnection();

$query = "DELETE FROM users WHERE ID = '$deleteUserID'";
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