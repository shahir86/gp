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
$stmt = $usersObj->search($data->emailAddress);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
	$usersObj->authentication($data->emailAddress,$data->password,$data->userName);   
} else{
    echo json_encode(
        array("message" => "Authentication Failure.")
    );
}
?>