<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
if(!$_POST){
	echo '{';
        echo '"message": "Error Found."';
    echo '}';
	exit;
}

include_once 'DB.php';
include_once 'objects/UsersObj.php';
 
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
$usersObj = new Users($db);
 
 // get posted data
$data = json_decode(json_encode($_POST));
$usersObj->emailAddress = $data->emailAddress;
$usersObj->password = $data->password;
  
// update
if($usersObj->update()){
    echo '{';
        echo '"message": "Password was updated."';
    echo '}';
}
 
// if unable to update the product, tell the user
else{
    echo '{';
        echo '"message": "Unable to update password."';
    echo '}';
}
?>