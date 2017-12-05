<?php

/**
 * Validate the requests' attributes, such as required indexes in $_POST, ...
 */
//can be used like verifyAttributes($_POST, ["itemId", "pwd"]);
function verifyAttributes($array, $attributes) {
	$notFound = array();
	foreach ($attributes as $attr) {
		if (!isset($array[$attr])) {
			$notFound[] = "Request requires index $attr, which was not supplied";
		}
	}
	if (count($notFound) > 0) {
		echo json_encode(array("success" => false, "errors" => $notFound));
		die();
	}
}