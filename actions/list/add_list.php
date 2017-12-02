<?php
require_once(dirname(__FILE__)."/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["title", "tags", "colour", "projectId"]);

require_once(dirname(__FILE__)."/../../classes/TodoList.php");

$result = array("success"=>false);

$todo = new TodoList();
$todo->userId = $_SESSION["userId"];
$todo->loadFromArray($_POST);//load todo properties from post

if($todo->validate()){
	if($todo->insert()){
		$result["success"] = true;
		$result["todoListId"] = $todo->todoListId;
	}
}else{
	$result["errors"] = $todo->errors;
}

echo json_encode($result);