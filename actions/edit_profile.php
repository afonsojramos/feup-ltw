<?php

/**
 * This action file treats 2 kinds of requests:
 * 	- chnage of user name and/or email.
 *  - change of password.
 * 
 * These are two separate actions, and cannot be done concurrently.
 * 
 */

require_once(dirname(__FILE__)."/../classes/User.php");
require_once(dirname(__FILE__)."/../includes/common/session.php");
$result = array("success"=>false);


if(!isset($_SESSION['userId']) || $_SESSION['userId']==''){
	$result["errors"] = array("session expired");
	echo json_encode($result);
	die();
}

verifyCSRF($_POST['csrf']);

if(isset($_POST['pwd1']) && isset($_POST['pwd2']) && $_POST['pwd1']!="" && $_POST['pwd2']!=""){
	/**
	 * This is a password change. Let's treat it acordingly.
	 */

	$user = new User();
	if(!$user->load($_SESSION['userId'])){ //load user
		$result["errors"] = array("username no longer exists");
	}
	
	if($_POST['pwd1']!==$_POST['pwd2']){
		$result["errors"] = array("passwords don't match");
	}else{
		$user->password=$_POST['pwd1'];
	}
	
	if($user->validate()){
		if($user->clear()->update() == 1){
			$result["success"] = true;
		}
	}else{ 
		$result["errors"] = $user->errors;
	}


}else{
	/**
	 * This is a username/email change.
	 */

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
}

echo json_encode($result);
