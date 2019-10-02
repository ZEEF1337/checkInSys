<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");

include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

if(!isset($_GET['userID'])){
    $out['result'] = 0;
    $json = json_encode($out);
    print_r($json);
    return;
}
$userID = $_GET['userID'];


$database = new Database();
$db = $database->getConnection();
if($userID == -1){
    $query = "SELECT u.ID, u.Email, u.Firstname, u.Lastname, u.CardID, ug.Area, u.isInstructor FROM users as u"; 
    $query .=" INNER JOIN usergroups AS ug ON ug.ID = u.Usergroup ORDER BY TRIM(u.Firstname) ASC, TRIM(u.Lastname) ASC;";
}else{
    $query = "SELECT u.ID, u.Email, u.Firstname, u.Lastname, u.CardID, ug.Area, u.isInstructor FROM users as u";
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
                "FirstName" => $Firstname,
                "LastName" => $Lastname,
                "Email" => $Email,
                "CardID" => $CardID,
                "Area" => $Area,
                "isInstructor" => (int)$isInstructor,
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