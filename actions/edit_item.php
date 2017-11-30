<?php
require_once(dirname(__FILE__) . "/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../classes/Item.php");
require_once(dirname(__FILE__) . "/../classes/TodoList.php");

$result = array("success" => false);

$item = new Item();

if ($item->load($_POST["itemId"])) {
	$item->todoListId;

	$todoList = new todoList();
	if ($todoList->load($item->todoListId)) {
		if ($todoList->verifyOwnership($_SESSION["userId"])) {
			if ($_POST["action"] == "completed") {
				$item->completed = $_POST["completed"] == "false" ? 0 : 1;
			} else if ($_POST["action"] == "content") {
				$item->content = $_POST["content"];
			}
			if ($item->update() !== false) {
				$result["success"] = true;
			} else {
				$result["errors"] = array("Could not update item");
			}
		} else {
			$result["errors"] = array("No match for userId");
		}
	} else {
		$result["errors"] = array("Could not load todoListId");
	}
} else {
	$result["errors"] = array("Could not load itemId");
}

echo json_encode($result);