<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");


if(!isset($_POST['email']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['userID']) || !isset($_POST['token'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$givenEmail = $_POST['email'];
$givenFirstName = $_POST['firstName'];
$givenLastName = $_POST['lastName'];
$givenUserID = $_POST['userID'];
$token = $_POST['token'];


$validUser = checkUserCall($givenUserID, $token);
$adminCheck = checkIfAdminNew($token);

if($adminCheck == 0){
    if($validUser == 0){
        $out['result'] = 0;
        $json = json_encode($out);
        print_r($json);
        return;
    }
}

$database = new Database();
$db = $database->getConnection();


$query = "UPDATE users SET Email = '$givenEmail', Firstname = '$givenFirstName', Lastname = '$givenLastName'";
$query .= " WHERE ID = $givenUserID";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
    $out['message'] = "Profil indstillinger opdateret.";
    
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>