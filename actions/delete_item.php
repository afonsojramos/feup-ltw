<?php
require_once(dirname(__FILE__) . "/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../classes/Item.php");
require_once(dirname(__FILE__) . "/../classes/TodoList.php");

$result = array("success" => false);

$item = new Item();

if ($item->load($_POST["itemId"])) {
	$item->todoListId;

	$todoList = new TodoList();

	if ($todoList->load($item->todoListId)) {
		if ($todoList->verifyOwnership($_SESSION["userId"])) {
			if ($item->delete() !== false) {
				$result["success"] = true;
			} else {
				$result["errors"] = array("Could not delete item");
			}
		} else {
			$result["errors"]= array("User has no permission to access Todo List");
		}
	} else {
		$result["errors"] = array("Could not load Todo List");
	}
} else {
	$result["errors"] = array("Could not load Item");
}

echo json_encode($result);