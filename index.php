<?php
	require_once(dirname(__FILE__)."./templates/common/defaults.php");
	$PAGE["title"] .= " : Homepage";
	//$PAGE["scripts"] = array("public/js/index.js");//add the scripts needed
	require_once(dirname(__FILE__)."./templates/common/header.php");
?>

<h1>Hello World</h1>

<?php require_once(dirname(__FILE__)."./templates/common/footer.php"); ?>