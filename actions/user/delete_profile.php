<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../classes/User.php");

$result = array("success" => false);

$user = new User($_SESSION["userId"]);
if ($user->delete()) {
	$result["success"] = true;
	User::logout();
} else {
	$result["errors"] = array("Unable to delete account");
}

echo json_encode($result);
