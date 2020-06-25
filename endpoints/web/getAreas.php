<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");


include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_GET['userID']) || !isset($_GET['token'])){
    $out['result'] = 0;
    $out['message'] = 'Missing Param';
    $json = json_encode($out);
    print_r($json);
    return;
}

$userID = $_GET['userID'];
$userGroup = getUserGroupFromUserID($userID);
$token = $_GET['token'];

if(checkIfAdmin($token) == 1){
    $query = "SELECT * FROM usergroups;";
}else{
    $query = "SELECT * FROM usergroups WHERE ID = $userGroup;";
}

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');
$stmt = $db->prepare($query);
try{
    $stmt->execute();
    $out = array();
    $out["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $temp = array(
            "ID" => $ID,
            "Area" => $Area,
            "Instructor" => $Instructor,
        );
        array_push($out["records"], $temp);
        $out['result'] = 1;
    }

} catch(PDOException $e){
    print_r($e);
    return;
}

$json = json_encode($out);
print_r($json);
return;
?>