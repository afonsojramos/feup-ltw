<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_logout.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Register";
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>

	<div class="grid">

      <form action="actions/register.php" method="post" id="registerMenu">

        <header class="registerHeader">
          <h3 class="registerTitle">Register</h3>
        </header>

        <div class="registerBody">

          <div class="formField">
            <input type="text" name="username" id="username" placeholder="Username" required>
		  </div>

		  <div class="formField">
            <input type="email" name="email" id="email" placeholder="Email"><br/>
		  </div>

          <div class="formField">
            <input type="password" name="password" id="password" placeholder="Password" required>
          </div>

        </div>

        <footer class="registerFooter">
          <input type="submit" value="Register">
        </footer>

      </form>

    </div>

<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>