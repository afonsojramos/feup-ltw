<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_login.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Edit Profile for" . $_SESSION["username"];
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>

<div class="container">
	<h1>Edit Profile</h1>
</div>


<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>