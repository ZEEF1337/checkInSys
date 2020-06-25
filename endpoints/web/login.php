<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");


include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

$email = $_POST['email'];
$cleanpass = $_POST['password'];


$login = verifyPass($email, $cleanpass);
$userID = getUserIDFromEmail($email);
$token = generateToken($email);
$adminCheck = checkIfAdmin($token);
$getNames = getNames($email);

if($login == 1){
    $out['result'] = 1;
    $out['message'] = "Login successful";
    $out['userID'] = (INT)$userID;
    $out['isAdmin'] = (INT)$adminCheck;
    $out['email'] = $email;
    $out['firstName'] = $getNames['firstName'];
    $out['lastName'] = $getNames['lastName'];
    $out['token'] = $token;
}else{
    $out['result'] = 0;
    $out['message'] = "Forkert brugernavn eller adgangskode.";
}


$json = json_encode($out);
print_r($json);
return;
?>