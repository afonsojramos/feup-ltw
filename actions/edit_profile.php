<?php
require_once(dirname(__FILE__)."/../classes/User.php");
require_once(dirname(__FILE__)."/../includes/common/session.php");
$result = array("success"=>false);

$user = new User();
$user->load($_SESSION['userId']);//load user 

if(!$user){
	header("Location: /");
}

$user->username=$_POST['username'];
$user->email=$_POST['email'];

var_dump($user); die();

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
