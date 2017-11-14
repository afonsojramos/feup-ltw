<?php
	require_once(dirname(__FILE__)."./includes/common/defaults.php");
	$PAGE["title"] .= " : Login";
	require_once(dirname(__FILE__)."./templates/common/header.php");
	require_once(dirname(__FILE__)."./includes/common/choose_navbar.php");
?>

<h1>Login page</h1>

<form action="actions/login.php" method="post">
	<input type="text" name="username" id="username" placeholder="username"><br/>
	<input type="password" name="password" id="password" placeholder="password"><br/>
	<input type="submit" value="Login">
</form>

<?php require_once(dirname(__FILE__)."./templates/common/footer.php"); ?>