<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_logout.php");
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Login";
$PAGE["styles"][] = "card_form.css";
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("login.js", "ajax.js"));
$PAGE["includeCSRF"] = true;
require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");
?>

<div class="grid shadow-2">
	<form id="loginForm" class="cardForm" action="actions/user/login.php" method="post">
		<?php insertCsrfToken(); ?>
		<header class="formHeader">
			<h3 class="formTitle">Login</h3>
		</header>
		<div class="formBody">
			<div>
				<input type="text" name="username" id="username" placeholder="Username or Email" value="<?= isset($_GET["username"]) ? $_GET["username"] : "" ?>" required>
			</div>
			<div>
				<input type="password" name="password" id="password" placeholder="Password" required>
			</div>
		</div>
		<footer class="formFooter">
			<input type="submit" value="Login">
		</footer>
		<div class="errors"></div>
	</form>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>