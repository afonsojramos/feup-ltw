<?php
	require_once(dirname(__FILE__)."./includes/common/defaults.php");
	$PAGE["title"] .= " : Homepage";
	//$PAGE["styles"][] = "public/css/index.css";//add a css file at the end
	//$PAGE["scripts"] = array("public/js/index.js");//add the scripts needed
	require_once(dirname(__FILE__)."./templates/common/header.php");
	require_once(dirname(__FILE__)."./includes/common/choose_navbar.php");
?>

<h1>Hello World</h1>

<?php require_once(dirname(__FILE__)."./templates/common/footer.php"); ?>