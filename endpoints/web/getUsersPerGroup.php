<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");


if(!isset($_GET['groupID'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}
$groupID = $_GET['groupID'];

$database = new Database();
$db = $database->getConnection();

date_default_timezone_set('Europe/Copenhagen');
$date = new DateTime();
$currTime = $date->format("H:i");
$currWeek = $date->format('W');
$year = $date->format('Y');
$weekday = $date->format('l');

$query = "SELECT u.Firstname, u.Lastname, u.isInstructor, u.Email, u.CardID, u.ID as userID, tt.*, ug.* FROM users as u";
$query .= " LEFT JOIN timetable as tt ON tt.UserID = u.ID INNER JOIN usergroups as ug ON ug.ID = u.Usergroup";
$query .= " WHERE NOT EXISTS (SELECT * FROM timetable WHERE u.ID = timetable.UserID && timetable.Week = '$currWeek')";
$query .= " && u.Usergroup = '$groupID' || tt.Week = '$currWeek' && tt.Year = '$year' &&u.Usergroup = '$groupID' ORDER BY TRIM(u.Firstname) ASC, TRIM(u.Lastname) ASC;";
$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $num = $stmt->rowCount();
    $out = array();
    if($num > 0){
        $out["users"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if($Week == $currWeek){
                $temp = array(
                    "firstName" => $Firstname,
                    "lastName" => $Lastname,
                    "email" => $Email,
                    "cardID" => $CardID,
                    "ID" => $userID,
                    "checkTime" => json_decode($row[$weekday]),
                    "isInstructor" => (INT)$isInstructor,
                );
            }else{
                $temp = array(
                    "firstName" => $Firstname,
                    "lastName" => $Lastname,
                    "email" => $Email,
                    "cardID" => $CardID,
                    "ID" => $userID,
                    "checkTime" => NULL,
                    "isInstructor" => (INT)$isInstructor,
                );
            }
            array_push($out["users"], $temp);
            $out['instructor'] = $Instructor;
            $out['area'] = $Area;
            $out['week'] = $currWeek;
            $out['year'] = $year;
            $out['result'] = 1;
        }
    }else{
        $query2 = "SELECT * FROM usergroups WHERE ID = $groupID;";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute();
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $out['instructor'] = $Instructor;
        $out['area'] = $Area;
        $out['week'] = $currWeek;
        $out['year'] = $year;
        $out['result'] = 0;
        
    }
} catch(PDOException $e){
    return(print_r($e));
}

$json = json_encode($out);
print_r($json);
return;

?>