<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_GET['userID']) || !isset($_GET['year'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}


$userID = $_GET['userID'];
$givenYear = $_GET['year'];


$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');

$query = "SELECT * FROM timetable WHERE UserID = '$userID' && year = '$givenYear' ORDER BY Week DESC";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $num = $stmt->rowCount();
    $out = array();
    $out["records"] = array();
    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $out["records"][$Week] = array(
                "Monday" => json_decode($Monday),
                "Tuesday" => json_decode($Tuesday),
                "Wednesday" => json_decode($Wednesday),
                "Thursday" => json_decode($Thursday),
                "Friday" => json_decode($Friday),
            );
            $out['year'] = $givenYear;
            $out['result'] = 1;
        }
    }else{
        $out['year'] = $givenYear;
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