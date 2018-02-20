<?php
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $emailAddress;
    public $userName;
    public $password;
    public $createdDate;
    public $updatedDate;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	// create db and table 
	function createDbTable(){	
		
		$query = "CREATE TABLE IF NOT EXISTS `users` (
				  `emailAddress` varchar(40) NOT NULL,
				  `userName` varchar(100) NULL,
				  `password` varchar(60)  NULL,
				  `createdDate` datetime  NULL,
				  `updatedDate` datetime  NULL,
				  PRIMARY KEY (`emailAddress`)
				)";
		
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);	 
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
		
	 
		return false;
	}
	
	function read(){
	 
		// select all query
		$query = "SELECT * FROM " . $this->table_name;
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	
	function readOneDataByEmail($emailAddress){
 
		// query to read single record
		$query = "SELECT userName,password FROM " . $this->table_name . " WHERE emailAddress=:emailAddress";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		$stmt->bindParam(":emailAddress", $emailAddress);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		$this->userName = $row['userName'];
		$this->password = $row['password'];
	}

	function authentication($emailAddress,$password,$userName){
		$this->readOneDataByEmail($emailAddress);
		
		if($userName == $this->userName){	
			if(md5($password) == $this->password){
				echo json_encode(
					array("message" => "Authentication Success.")
				);
				exit;
			}
		}
		
		echo json_encode(
			array("message" => "Authentication Fail.")
		);
		exit;
		
	}



	// update 
	function update(){
		$stmt = $this->search($this->emailAddress);
		$num = $stmt->rowCount();
	
		if($num > 0){
			// update query
			$query = "UPDATE
						" . $this->table_name . "
					SET
						password = :password,updatedDate=:updatedDate
					WHERE
						emailAddress = :emailAddress";
		 
			// prepare query statement
			$stmt = $this->conn->prepare($query);
		 
			// sanitize
			$this->password=htmlspecialchars(strip_tags($this->password));
			$this->emailAddress=htmlspecialchars(strip_tags($this->emailAddress));
			$this->updatedDate=htmlspecialchars(strip_tags(date('Y-m-d H:i:s')));
		 
			$passwordHash = md5($this->password);
			
			// bind new values
			$stmt->bindParam(':password', $passwordHash);
			$stmt->bindParam(":updatedDate", $this->updatedDate);
			$stmt->bindParam(':emailAddress', $this->emailAddress);
		 
			// execute the query
			if($stmt->execute()){
				return true;
			}
		}
	 
		return false;
	}

	// delete
	function delete(){
		$stmt = $this->search($this->emailAddress);
		$num = $stmt->rowCount();
		
		if($num > 0){
			// delete query
			$query = "DELETE FROM " . $this->table_name . " WHERE emailAddress = ?";
		 
			// prepare query
			$stmt = $this->conn->prepare($query);
		 
			// sanitize
			$this->emailAddress=htmlspecialchars(strip_tags($this->emailAddress));
		 
			$stmt->bindParam(1, $this->emailAddress);
		 
			// execute query
			if($stmt->execute()){
				return true;
			}
		}
	 
		return false;
		 
	}

	function search($email){
		// select all query
		$query = "SELECT * FROM " . $this->table_name . " WHERE emailAddress = ? ";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$email=htmlspecialchars(strip_tags($email));
		//$email = "%{$email}%";
	
		// bind
		$stmt->bindParam(1, $email);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	
	// create user
	function create(){
		$stmt = $this->search($this->emailAddress);
		$num = $stmt->rowCount();
	
		if(!($num > 0)){
			// query to insert record
			$query = "INSERT INTO
						" . $this->table_name . "
					SET
						emailAddress=:emailAddress, userName=:userName, password=:password,createdDate=:createdDate,updatedDate=:updatedDate";
		 
			// prepare query
			$stmt = $this->conn->prepare($query);
		 
			// sanitize
			$this->emailAddress=htmlspecialchars(strip_tags($this->emailAddress));
			$this->userName=htmlspecialchars(strip_tags($this->userName));
			$this->createdDate=htmlspecialchars(strip_tags($this->createdDate));
		 
			// bind values
			$passwordHash = md5($this->password);
			$stmt->bindParam(":emailAddress", $this->emailAddress);
			$stmt->bindParam(":userName", $this->userName);
			$stmt->bindParam(":password", $passwordHash);
			$stmt->bindParam(":createdDate", $this->createdDate);
			$stmt->bindParam(":updatedDate", $this->createdDate);
		
			// execute query
			if($stmt->execute()){
				return true;
			}
		}
	 
		return false;
		 
	}
}

?>