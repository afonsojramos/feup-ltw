<?php
	require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");
	require_once(dirname(__FILE__) . "/includes/common/defaults.php");
	$PAGE["title"] .= " : Edit Profile for" . $_SESSION["username"];
	$PAGE["styles"][] = "card_form.css";
	require_once(dirname(__FILE__) . "/templates/common/header.php");
	require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");
	require_once(dirname(__FILE__) . "/classes/User.php");
?>

<?php
	$user = new User;
	$user = $user->load($_SESSION['userId']);
?>



<div class="container">
	<h1>Edit Profile</h1>
	<div class="grid grid-long shadow-1">
		<form class="cardForm split" action="actions/edit_profile.php" method="post">
			<div class="formHeader">
				<h3 class="formTitle">Edit User Details</h3>
			</div>
			<div class="formBody">
				<div class="formField">
					<span> User name: </span><input type="text" name="username" title="User name:" id="username" value="<?php echo $user->username; ?>" placeholder="Username" required>
				</div>
				<div class="formField">
					<span> Email: </span><input type="email" name="email" id="email" value="<?php echo $user->email; ?>" placeholder="Email" required>
				</div>
			</div>
			<footer class="formFooter">
				<input type="submit" value="Submit">
			</footer>
		</form>
	</div>

	<div class="grid shadow-1">
		<form class="cardForm split" action="actions/edit_profile.php" method="post">
			<div class="formHeader">
				<h3 class="formTitle">Change Password</h3>
			</div>
			<div class="formBody">
				<div class="formField">
					<input type="password" name="pwd1" placeholder="Password" required>
				</div>
				<div class="formField">
					<input type="password" name="pwd2" placeholder="Password" required>
				</div>
			</div>
			<footer class="formFooter">
				<input type="submit" value="Submit">
			</footer>
		</form>
	</div>
</div>


<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>