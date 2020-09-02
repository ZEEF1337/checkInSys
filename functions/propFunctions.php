<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/endpoint/database.inc");

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

function getSaltFromDB($email){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Salt FROM users WHERE Email = '$email'";
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

function getPassFromDB($email){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Password FROM users WHERE Email = '$email'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $Password;
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

function getUserIDFromEmail($email){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT ID FROM users WHERE Email = '$email'";
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
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $isAdmin;
        }
    } catch(PDOException $e){
        return($e);
    }
}

function checkIfAdminNew($token){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT AuthToken, isAdmin FROM users WHERE AuthToken = '$token'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            return $isAdmin;
        }
        else{
            return 0;
        }
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

function verifyPass($email, $pass){
    $salt = getSaltFromDB($email);
    $dbPass = getPassFromDB($email);
    $testPass = hash('sha512', hash('sha512', $salt).$pass);
    if(strcmp($dbPass, $testPass) == 0){
        return 1;
    }else{
        return 0;
    }
}

function getNames($email){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT Firstname, Lastname FROM users WHERE Email = '$email'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            $out['firstName'] = $Firstname;
            $out['lastName'] = $Lastname;
            return $out;
        }
    } catch(PDOException $e){
        return($e);
    }
}


function generateToken($email) {
    $database = new Database();
    $db = $database->getConnection();
    $len = 12;
    $char = '0123456789'.crc32($email);
    $charLen = strlen($char);
    $tokenInUse = 1;
    try{
        while($tokenInUse){
            $token = '';
            for ($i = 0; $i < $len; $i++) {
                $token .= $char[rand(0, $charLen - 1)];
            }
            $token = hash('sha1', $token);
            $query = "SELECT AuthToken FROM users WHERE AuthToken = '$token'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if($num>0){
                $tokenInUse = 1;
            }else{
                $tokenInUse = 0;
            }
        }
        insertAuthToken($email, $token);
        return $token;
    } catch(PDOException $e){
        return($e);
    }
}

function insertAuthToken($email, $token){
    $database = new Database();
    $db = $database->getConnection();
    $query = "UPDATE users SET AuthToken='$token' WHERE Email = '$email'";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        return true;
    } catch(PDOException $e){
        return($e);
    }
}


function checkUserCall($userID, $token){
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT AuthToken, ID FROM users WHERE AuthToken = '$token' && ID = $userID";
    $stmt = $db->prepare($query);
    try{
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            return 1;
        }
        else{
            return 0;
        }
    } catch(PDOException $e){
        return($e);
    }
}
?>