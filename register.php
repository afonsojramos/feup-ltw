<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_logout.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Register";
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>

<h1>Register page</h1>

<form action="actions/register.php" method="post" id="registerMenu">
	<input type="text" name="username" id="username" placeholder="username"><br/>
	<input type="email" name="email" id="email" placeholder="email"><br/>
	<input type="password" name="password" id="password" placeholder="password"><br/>
	<input type="submit" value="Register">
</form>

<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>