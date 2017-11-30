<?php
require_once(dirname(__FILE__)."/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__)."/../classes/TodoList.php");

$result = array("success"=>false);

$todoList = new TodoList();

if($todoList->load($_POST["todoListId"])){
	if ($todoList->verifyOwnership($_SESSION["userId"])){
		if ($todoList->delete() !== false){
			$result["success"] = true;
		} else{
			$result["errors"]= array("Could not delete todoList");
		}
	} else{
		$result["errors"]= array("User is not the owner of the todoList");
	}
}else {
	$result["errors"] = array("Could not load todoListId");
}

echo json_encode($result);