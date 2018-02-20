<?php

// show error reporting
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
 
$database = new Database();
$db = $database->getConnection();
 
$usersObj = new Users($db);

// get posted data
$data = json_decode(json_encode($_POST));

$usersObj->emailAddress = $data->emailAddress;
$usersObj->password = $data->password;
$usersObj->userName = $data->userName;
$usersObj->createdDate = date('Y-m-d H:i:s');
  

if($usersObj->create()){
    echo '{';
        echo '"message": "New User was created."';
    echo '}';
}
 
else{
    echo '{';
        echo '"message": "Unable to create User."';
    echo '}';
}
?>