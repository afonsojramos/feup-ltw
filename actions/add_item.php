<?php
require_once(dirname(__FILE__)."/../includes/common/only_allow_login.php");
require_once(dirname(__FILE__)."/../classes/Item.php");

$result = array("success"=>false);

//TODO: afonso

$result["errors"] = array("afonso vem fazer o actions/add_item.php ");

echo json_encode($result);
