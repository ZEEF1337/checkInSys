<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

$userID = $_GET['userID'];

if(!$userID){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');
$date = new DateTime();
$currWeek = $date->format('W');
$currYear = $date->format('Y');
$currDay = $date->format('l');

$query = "SELECT * FROM timetable WHERE UserID = '$userID' && Week = '$currWeek' && Year = '$currYear'";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $num = $stmt->rowCount();
    $out = array();
    $out["records"] = array();
    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            print_r(json_decode($Tuesday));
            $temp = array(
                "Monday" => json_decode($Monday),
                "Tuesday" => json_decode($Tuesday),
                "Wednesday" => json_decode($Wednesday),
                "Thursday" => json_decode($Thursday),
                "Friday" => json_decode($Friday),
            );
            array_push($out["records"], $temp);
            $out['currentWeek'] = $currWeek;
            $out['currentDay'] = $currDay;
            $out['result'] = 1;
        }
    }else{
        $temp = array(
            "Monday" => NULL,
            "Tuesday" => NULL,
            "Wednesday" => NULL,
            "Thursday" => NULL,
            "Friday" => NULL,
        );
        array_push($out["records"], $temp);
        $out['currentWeek'] = $currWeek;
        $out['currentDay'] = $currDay;
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