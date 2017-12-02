<?php
require_once(dirname(__FILE__) . "/check_request.php");

/**
 * Token support functions
 */
function generate_random_token(){
	return bin2hex(openssl_random_pseudo_bytes(32));
}

function insertCsrfToken(){
	echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
}

function verifyCSRF(){
	verifyAttributes($_POST, ["csrf"]);
	if ($_SESSION['csrf'] !== $_POST["csrf"]) {
		$result = array("success" => false);
		$result["errors"] = array("token mismatch.");
		echo json_encode($result);
		die();
	}
}

