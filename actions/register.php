<?php
require_once(dirname(__FILE__)."/../classes/User.php");

$result = array("success"=>false);

$user = new User();
$user->loadFromArray($_POST);//load user properties from post

if($user->validate()){
	if($user->duplicateUsername()){
		$result["errors"] = array("username already exists");
	}elseif($user->duplicateEmail()){
		$result["errors"] = array("email already exists");
	}elseif($user->insert()){
		$result["success"] = true;
	}
}else{
	$result["errors"] = $user->errors;
}
echo json_encode($result);
