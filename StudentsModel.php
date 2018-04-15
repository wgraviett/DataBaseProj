<?php>
require ('User.php'); //Connects to user portfolio

class StudentsModel {

private $error = ' ';
private $mysqli;
private $orderby = 'Name';
private $orderdirection ='asc';
private $user;

	public function __construct(){
		$this->initDatabaseConnection();
		$this->restoreUser();
		}
	public function __destruct(){
					if ($this->mysqli) {
				$this->mysqli->close();
			}
	}
	private function initDatabaseConnection(){
		require('db_credentials.php');
		$this->mysqli = new mysqli($servername, $username, $password, $dbname);
		if ($this->mysqli->connect_error)
			{
			$this->error = $mysqli->connect_error;
			}
		
	}
	private function restoreUser(){
	if ($loginID =$_SESSION['loginid']) {
		$this->user = new User();
		if (!$this->user->load($loginID, $this->mysqli)){
			$this->user = null;
			}
		}
	}
	public function getUser(){
	return $this->user;
	}
	
		public function login($loginID, $password) {
			// check if loginID and password are valid by comparing
			// encrypted version of password to encrypted password stored
			// in database for user with loginID
			
			$user = new User();
			if ($user->load($loginID, $this->mysqli) && password_verify($password, $user->hashedPassword)) {
				$this->user = $user;
				$_SESSION['loginid'] = $loginID;
				return array(true, "");
			} else {
				$this->user = null;
				$_SESSION['loginid'] = '';
				return array(false, "Invalid login information.  Please try again.");
			}
		}
	
	public function getError() {
		return $this->error;
		}
	public function logout(){
		$this->user = null;
		$_SESSION['loginid'] = '';
	}
	
	public function ListAllApps()
	{ //Used to list all applications for administer or advisor view.
		$this->error ='';
		$apps = array();
		
		if(!$this->user){
			$this->error = "User not specified. Unable to Display.";
			
		}
		if (!$this->mysqli){
			$this->error = "No connection to database.";
			return array ($apps, $this->error);
		}
		
		$sql="SELECT Applications.id,Applications.StudentID, users.First_Name, users.Last_Name, Applications.application_status, Applications.ProgramID
FROM Applications INNER JOIN users ON users.studentID = Applications.studentID";
		if ($result = $this->mysqli->query($sql)){
			if($result->num_rows >0){
				while($row = $result -> fetch_assoc()){
					array_push($apps,$row);
				}
			}
			$result ->close();
		}
		else {
			$this->error = $mysqli->error;
		}
		return array($apps,$this->error);
	}
	
	public function getapp($id){
		// used to fetch individual application for CRUD
		$this->error ='';
		$app=null;
		if (!$this->user){
			$this->error ="User not logged in. Please log in again.";
			return $this->error;
			
		}
		
		if (!$this->mysqli){
			$this->error = "No Connection to Database.";
			return array($app,$this->error);
		}
		if (! $id) {
			$this->error = "No id specified for app to retrieve.";
			return array($app, $this->error);
			}	
			
		$idEscaped = $this->mysqli->real_escape_string($id);
		$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);		
			
			$sql = "SELECT Applications.id, Applications.application_question_1,Applications.StudentID, users.First_Name, users.Last_Name, Applications.application_status, Applications.ProgramID
FROM Applications INNER JOIN users ON users.studentID = Applications.studentID where Applications.id = '$idEscaped' ";
				
				if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					$app = $result->fetch_assoc();
				}
				$result->close();
			} else {
				$this->error = $this->mysqli->error;
			}
			
			return array($app, $this->error);	
		
	}

	
		

}//close model
?>