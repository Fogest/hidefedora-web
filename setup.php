<?php
	session_start();
	require_once ("phplib/config.php");
	
	include_once ("phplib/Database.php");
	
	include_once ("phplib/Alert.php");
	
	include_once ("phplib/page.php");
	

	
	$database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$page = new Page();
	

	
	if(isset($_POST['username_login'])) {
		include_once ("login.php");

	}
	
	if(isset($_POST['username_register'])) {
		include_once ("register.php");
	}
	

?>