<?php

	class StudentsViews {
		private $stylesheet = 'studentmanager.css';
		private $pageTitle = 'Students';
		
		public function __construct() {

		}
		
		public function __destruct() {

		}
		public function AdvisorView($user, $apps, $message = '') {
			$body = "<h1>Applications for {$user->firstName} {$user->lastName}</h1>\n";
		
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
		
			$body .= "<p><a class='taskButton' href='index.php?view=taskform'>+ Add Task</a> <a class='taskButton' href='index.php?logout=1'>Logout</a></p>\n";
	
			if (count($apps) < 1) {
				$body .= "<p>No Applications to display!</p>\n";
				return $this->page($body);
			}
	
			$body .= "<table>\n";
			$body .= "<tr><th>delete</th><th>edit</th><th>View</th>";
		
			$columns = array(array('name' => 'id', 'label' => 'Application ID'),
			array('name' => 'First_Name', 'label' => 'First Name'),
							array('name' => 'Last_Name', 'label' => 'Last Name'),
							array('name' => 'StudentID', 'label' => 'StudentID'), 
							 array('name' => 'application_status', 'label' => 'Application Status'), 
							 array('name' => 'ProgramID', 'label' => 'ProgramID'));
		
			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}
	
			foreach ($apps as $app) {
				$id = $app['id'];
				$application_status = $app['application_status'];
				$ProgramID = $app['ProgramID'];
				$First_Name= $app['First_Name'];
				$Last_Name = $app['Last_Name'];
				$StudentID = $app['StudentID'];
			
				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='view' /><input type='hidden' name='id' value='$id' /><input type='submit' value='View'></form></td>";
				$body .= "<td>$id</td><td>$First_Name</td><td>$Last_Name</td><td>$StudentID</td><td>$application_status</td><td>$ProgramID</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";
	
			return $this->page($body);
		}
		

		public function loginFormView($data = null, $message = '') {
			$loginID = '';
			if ($data) {
				$loginID = $data['loginid'];
			}
		
			$body = "<h1>Apps</h1>\n";
			
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
			
			$body .= <<<EOT
<form action='index.php' method='post'>
<input type='hidden' name='action' value='login' />
<p>User ID<br />
  <input type="text" name="loginid" value="$loginID" placeholder="login id" maxlength="255" size="80"></p>
<p>Title<br />
  <input type="password" name="password" value="" placeholder="password" maxlength="255" size="80"></p>
  <input type="submit" name='submit' value="Login">
</form>	
EOT;
			
			return $this->page($body);
		}
		
		public function errorView($message) {	
			$body = "<h1>Apps</h1>\n";
			$body .= "<p>$message</p>\n";
			
			return $this->page($body);
		}
		
		private function page($body) {
			$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>{$this->pageTitle}</title>
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
<p>&copy; 2017 Dale Musser. All rights reserved.</p>
</body>
</html>
EOT;
			return $html;
		}

}