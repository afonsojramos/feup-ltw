<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_logout.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Login";
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>

	<div class="grid">

      <form action="actions/login.php" method="post" id="loginMenu">

        <header class="loginHeader">
          <h3 class="loginTitle">Login</h3>
        </header>

        <div class="loginBody">

          <div class="formField">
            <input type="text" name="username" id="username" placeholder="Username" required>
          </div>

          <div class="formField">
            <input type="password" name="password" id="password" placeholder="Password" required>
          </div>

        </div>

        <footer class="loginFooter">
          <input type="submit" value="Login">

          <p><span class="icon"></span><a href="#">Forgot Password?</a></p>
        </footer>

      </form>

    </div>

<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>