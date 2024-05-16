<?php
	// require_once('dbconfig.php');
	// $query2=mysqli_query($conn,"SELECT sessionid from loginlogs where id='quizadmin' ORDER BY login_time DESC LIMIT 1");

	// // ORDER BY time DESC LIMIT 1

	// $stored_session_id = mysqli_fetch_assoc($query2)["sessionid"];
    // error_log("previous Session id : " . $stored_session_id);
	// session_id("$stored_session_id");
	// session_start();
	// session_destroy();
	session_start();
	session_regenerate_id();
	include("./phptextClass.php");	
	
	/*create class object*/
	$phptextObj = new phptextClass();	
	/*phptext function to genrate image with text*/
	header('Content-type:image/jpg');
	$phptextObj->phpcaptcha('#162453','#fff',120,40,10,25);	
 ?>