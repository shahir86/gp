<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once 'DB.php';
include_once 'objects/UsersObj.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$usersObj = new Users($db);
 
//$stmt = $usersObj->read();

$searchEmail=isset($_REQUEST["emailAddress"]) ? $_REQUEST["emailAddress"] : "";
$stmt = $usersObj->search($searchEmail);
$num = $stmt->rowCount();

if($num>0){

    $usersArr=array();
    $usersArr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $userList=array(
            "emailAddress" => $emailAddress,
            "userName" => $userName,
            //"password" => $password,
            "createdDate" => $createdDate,
            "updatedDate" => $updatedDate
        );
 
        array_push($usersArr["records"], $userList);
    }
 
    echo json_encode($usersArr);
}
 
else{

    echo json_encode(
        array("message" => "No data found.")
    );
}


?>