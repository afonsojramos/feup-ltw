<?php
require_once(dirname(__FILE__)."/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__)."/../classes/Item.php");

$result = array("success"=>false);

$item = new Item();
$item->todoListId = $_SESSION["todoListId"];
$item->loadFromArray($_POST);

if($item->validate()){
	if($item->insert()){
		$result["success"] = true;
	}
}else{
	$result["errors"] = $item->errors;
}

echo json_encode($result);