<?php
/**
 * Token support functions
 */
function generate_random_token()
{
	return bin2hex(openssl_random_pseudo_bytes(32));
}

function insertHiddenToken(){
	echo '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
}

function verifyCSRF($arg){
	if($_SESSION['csrf'] !== $arg){
		$result = array("success"=>false);
		$result["errors"] = array("token mismatch.");
		echo json_encode($result);
		die();
	}
}

?>