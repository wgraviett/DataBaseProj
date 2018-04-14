<?php
	// Model-View-Controller implementation of Student Management
	
	require('StudentManagementController.php');

	$controller = new StudentManagementController();
	$controller->run();
?>