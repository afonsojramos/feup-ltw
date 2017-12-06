<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_logout.php");
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Register";
$PAGE["styles"][] = "card_form.css";
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("register.js", "ajax.js"));
$PAGE["includeCSRF"] = true;
require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");
?>

<div class="grid shadow-2">
	<form class="cardForm" id="registerForm" action="actions/user/register.php" method="post">
		<?php insertCsrfToken(); ?>
		<div class="formHeader">
			<h3 class="formTitle">Register</h3>
		</div>
		<div class="formBody">
			<div>
				<input type="text" name="username" id="username" placeholder="Username" autofocus required>
			</div>
			<div>
				<input type="email" name="email" id="email" placeholder="Email" required>
			</div>
			<div>
				<input type="password" name="password" id="password" placeholder="Password" required>
			</div>
		</div>
		<footer class="formFooter">
			<input type="submit" value="Register">
		</footer>
		<div class="errors"></div>
	</form>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>