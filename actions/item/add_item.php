<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/TodoList.php");

$result = array("success" => false);

$item = new Item();
$item->loadFromArray($_POST);

if ($item->validate()) {
	if ($item->verifyOwnership($_SESSION["userId"])) {
		if ($item->insert()) {
			$result["success"] = true;
			$result["itemId"] = $item->itemId;
		}
	} else {
		$result["errors"] = array("User has no permission to access Todo List");
	}
} else {
	$result["errors"] = $item->errors;
}

echo json_encode($result);