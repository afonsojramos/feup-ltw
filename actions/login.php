<?php
	require_once(dirname(__FILE__)."/../includes/common/session.php");
	require_once(dirname(__FILE__)."/../classes/User.php");

	$result = array("success"=>false);

	if(isset($_POST["username"]) && isset($_POST["password"])){
		$user = new User();
		if($user->login($_POST)){
			$result["success"] = true;
			header("Location: ../index.php");
		}
	}

	echo json_encode($result);