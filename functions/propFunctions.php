<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/checkIn/database.inc");

function generateRandomSalt($len) {
    $database = new Database();
    $db = $database->getConnection();
    $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@$#%&/';
    $charLen = strlen($char);
    $saltInUse = 1;
    try{
        while($saltInUse){
            $randomSalt = '';
            for ($i = 0; $i < $len; $i++) {
                $randomSalt .= $char[rand(0, $charLen - 1)];
            }
            $query = "SELECT * FROM users WHERE Salt = '$randomSalt'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if($num>0){
                $saltInUse = 1;
            }else{
                $saltInUse = 0;
            }
        }
        return $randomSalt;
    } catch(PDOException $e){
        return($e);
    }
}

function getSaltFromDB($user){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Salt FROM users WHERE Username = '$user'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $Salt;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function getPassFromDB($user){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Pass FROM users WHERE Username = '$user'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $Pass;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function emailExists($mail){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Firstname FROM users WHERE Email = '$mail'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            return true;
        }else{
            return false;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function cardExists($cardID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Firstname FROM users WHERE CardID = '$cardID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            return true;
        }else{
            return false;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function getUserIDFromName($user){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT * FROM users WHERE Username = '$user'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $ID;
        }else{
            return;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function getUsernameFromUserID($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT * FROM users WHERE ID = '$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $Username;
        }else{
            return;
        }
    } catch(PDOException $e){
        return($e);
    }
}



function getEmailFromUserID($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Email FROM users WHERE ID = '$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $Email;
        }
    } catch(PDOException $e){
        return($e);
    }
}


function getUserGroup($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT ugrp.Type FROM users INNER JOIN usergroups ugrp ON ugrp.ID = users.UserGroup WHERE users.ID = '$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            $out['result'] = 1;
            $out['type'] = $Type;
            return $out;
        }else{
            $out['result'] = 0;
        }

    } catch(PDOException $e){
        return($e);
    }
}

function getUserAvatar($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Username, Avatar FROM users WHERE ID ='$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if(empty($Avatar)){
                $defaultAvatar = "https://test.test/images/default.png";
                return $defaultAvatar;
            }else{
                return "http://test.test/images/profilepics/".$Username."/".$Avatar;
            }
        }
    } catch(PDOException $e){
        return($e);
    }
}


function getUserIDFromCardID($givenCardID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT ID FROM users WHERE CardID = '$givenCardID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $ID;
        }else{
            return false;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function getScannerIDFromScannerMAC($scannerMAC){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT ID FROM scanners WHERE MAC = '$scannerMAC'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $ID;
        }else{
            return false;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function checkIfAdmin($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT isAdmin FROM users WHERE ID = '$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        return $isAdmin;
    } catch(PDOException $e){
        return($e);
    }
}

function getUserGroupFromUserID($userID){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT userGroup FROM users WHERE ID = '$userID'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        return $userGroup;
    } catch(PDOException $e){
        return($e);
    }
}
?>