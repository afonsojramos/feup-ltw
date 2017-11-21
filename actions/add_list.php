<?php
require_once(dirname(__FILE__)."/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__)."/../classes/TodoList.php");

$result = array("success"=>false);

$todoList = new TodoList();
$todoList->userId = $_SESSION["userId"];
$todoList->loadFromArray($_POST);//load todoList properties from post

if($todoList->validate()){
	if($todoList->insert()){
		$result["success"] = true;
	}
}else{
	$result["errors"] = $todoList->errors;
}
echo json_encode($result);
