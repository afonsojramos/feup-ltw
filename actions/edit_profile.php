<?php
require_once(dirname(__FILE__)."/../classes/User.php");
require_once(dirname(__FILE__)."/../includes/common/session.php");
$result = array("success"=>false);

if(!isset($_SESSION['userId']) || $_SESSION['userId']==''){
	die('Session expired!');
}

$user = new User();
if(!$user->load($_SESSION['userId'])){ //load user
	die('User no longer exists!');
}

$user->username=$_POST['username'];
$user->email=$_POST['email'];


if($user->validate()){
	if($user->duplicateUsernameOnEditProfile()){
		$result["errors"] = array("username already exists");
	}elseif($user->duplicateEmailOnEditProfile()){
		$result["errors"] = array("email already exists");
	}elseif($user->clear()->update() == 1){
		$result["success"] = true;
	}
}else{ 
	$result["errors"] = $user->errors;
}

echo json_encode($result);
