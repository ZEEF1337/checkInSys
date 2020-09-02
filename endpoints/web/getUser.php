<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/functions/propFunctions.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");

if(!isset($_GET['userID']) || !isset($_GET['token'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$userID = $_GET['userID'];
$token = $_GET['token'];

$adminCheck = checkIfAdminNew($token);

if($adminCheck == 0){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}

$database = new Database();
$db = $database->getConnection();


if($userID == -1){
    $query = "SELECT u.ID, u.Email, u.Firstname, u.Lastname, u.CardID, ug.Area, u.isInstructor, u.isAdmin, ug.ID as AreaID FROM users as u"; 
    $query .=" INNER JOIN usergroups AS ug ON ug.ID = u.Usergroup ORDER BY TRIM(u.Firstname) ASC, TRIM(u.Lastname) ASC;";
}else{
    $query = "SELECT u.ID, u.Email, u.Firstname, u.Lastname, u.CardID, ug.Area, u.isInstructor, u.isAdmin, ug.ID as AreaID FROM users as u";
    $query .= " INNER JOIN usergroups AS ug ON ug.ID = u.Usergroup WHERE u.ID = '$userID' ORDER BY TRIM(u.Firstname) ASC, TRIM(u.Lastname) ASC;";
}

$stmt = $db->prepare($query);

try{
    $stmt->execute();
    $num = $stmt->rowCount();
    $out = array();
    if($num > 0){
        $out["users"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $temp = array(
                "ID" => (INT)$ID,
                "firstName" => $Firstname,
                "lastName" => $Lastname,
                "email" => $Email,
                "cardID" => $CardID,
                "area" => $Area,
                "areaID" => $AreaID,
                "isInstructor" => (INT)$isInstructor,
                "isAdmin" => (INT)$isAdmin,
            );
            
            array_push($out["users"], $temp);
            $out['result'] = 1;
        }
    }else{
        $out['result'] = 0;
        
    }
} catch(PDOException $e){
    return(print_r($e));
}

$json = json_encode($out);
print_r($json);
return;

?>