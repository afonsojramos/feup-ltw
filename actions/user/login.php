<?php
require_once(dirname(__FILE__) . "/../../includes/common/session.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["username", "password"]);

require_once(dirname(__FILE__) . "/../../classes/User.php");

$result = array("success" => false);

$user = new User();
if ($user->login($_POST)) {
	$result["success"] = true;
	header("Location: ../../index.php");
}

echo json_encode($result);