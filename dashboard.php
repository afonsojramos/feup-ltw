<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_login.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Dashboard for " . $_SESSION["username"];
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>


<div class="container">
	<h1>Welcome to the dashboard</h1>
	<p>Let the todos begin.</p>
</div><!--/.container-->


<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>