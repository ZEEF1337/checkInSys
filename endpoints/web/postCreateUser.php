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
$givenPassword = $_POST['password'];
$givenFirstName = $_POST['firstName'];
$givenLastName = $_POST['lastName'];
$givenGroup = $_POST['userGroup'];
$givenCardID = $_POST['cardID'];
$givenIsAdmin = $_POST['isAdmin'];
$givenIsInstructor = $_POST['isInstructor'];

$emailAlreadyExists = emailExists($givenEmail);
$cardAlreadyExists = cardExists($givenCardID);

if($emailAlreadyExists){
    $out['result'] = 0;
    $out['message'] = "Email adressen er allerede i brug";
    $json = json_encode($out);
    print_r($json);
    return;
}else if($cardAlreadyExists){
    $out['result'] = 0;
    $out['message'] = "Kortet er allerede registered";
    $json = json_encode($out);
    print_r($json);
    return;
}

$salt = generateRandomSalt(32);
$password = hash('sha512', hash('sha512', $salt).$givenPassword);

$database = new Database();
$db = $database->getConnection();


$query = "INSERT INTO users (Email, Password, Salt, Firstname, Lastname, Usergroup, CardID, isAdmin, isInstructor) VALUES";
$query .= " ('$givenEmail', '$password', '$salt', '$givenFirstName', '$givenLastName', $givenGroup, '$givenCardID', $givenIsAdmin, $givenIsInstructor);";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
    $out['message'] = "Kontoen blev oprettet";
    
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>