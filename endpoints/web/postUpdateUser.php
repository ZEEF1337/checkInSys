<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");


$givenEmail = $_POST['email'];
$givenFirstName = $_POST['firstName'];
$givenLastName = $_POST['lastName'];
$givenGroup = $_POST['userGroup'];
$givenCardID = $_POST['cardID'];
$givenIsAdmin = $_POST['isAdmin'];
$givenIsInstructor = $_POST['isInstructor'];
$givenUserID = $_POST['userID'];

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