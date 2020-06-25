<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_POST['email']) || !isset($_POST['userID']) || !isset($_POST['updateUserID']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['userGroup']) || !isset($_POST['cardID']) || !isset($_POST['isAdmin']) || !isset($_POST['isInstructor']) || !isset($_POST['token'])){
    $out['result'] = 0;
    $out['message'] = "Missing param";
    $json = json_encode($out);
    print_r($json);
    return;
}

$givenEmail = $_POST['email'];
$givenFirstName = $_POST['firstName'];
$givenLastName = $_POST['lastName'];
$givenGroup = $_POST['userGroup'];
$givenCardID = $_POST['cardID'];
$givenIsAdmin = $_POST['isAdmin'];
$givenIsInstructor = $_POST['isInstructor'];
$givenUserID = $_POST['updateUserID'];
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


$query = "UPDATE users SET Email = '$givenEmail', Firstname = '$givenFirstName', Lastname = '$givenLastName',";
$query .= " Usergroup = $givenGroup, CardID = '$givenCardID', isAdmin = $givenIsAdmin, isInstructor = $givenIsInstructor WHERE ID = $givenUserID";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
    $out['message'] = "Brugeren blev opdateret.";
    
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>