<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");


$givenArea = $_POST['area'];
$givenInstructor = $_POST['instructor'];

$database = new Database();
$db = $database->getConnection();


$query = "INSERT INTO usergroups (Area, Instructor) VALUES";
$query .= " ('$givenArea', '$givenInstructor');";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $out['result'] = 1;
    $out['message'] = "Bruger gruppen blev oprettet";
    
} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>