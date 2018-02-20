<?php
header("Content-Type: application/json; charset=UTF-8");

include_once 'DB.php';
include_once 'objects/UsersObj.php';

$database = new Database();
$db = $database->getConnection();
 
$usersObj = new Users($db);

  
if($usersObj->createDbTable()){
    echo '{';
        echo '"message": "Table was created."';
    echo '}';
}
else{
    echo '{';
        echo '"message": "Unable to create table."';
    echo '}';
}

?>