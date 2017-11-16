<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_login.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Dashboard for " . $_SESSION["username"];
	$PAGE["styles"][] = "modal.css";
	$PAGE["scripts"][] = "modal.js";

	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");

	require_once(dirname(__FILE__)."/templates/dashboard/sidebar.php");
?>


<div class="container">
	<h1>Welcome to the dashboard</h1>
	<p>Let the todos begin.</p>
	<div class="modal" opener="openModal">
  		<div class="modalContent">
			<div class="modalHeader">
				<span class="closeModal"><i class="material-icons">close</i></span>
				<h2>Modal Header</h2>
			</div>
			<div class="modalBody">
				<p>Some text in the Modal Body</p>
				<p>Some other text...</p>
			</div>
				<div class="modalFooter">
				<h3>Modal Footer</h3>
			</div>
		</div>
	</div>
	<a id="openModal">Open Modal</a>
</div><!--/.container-->


<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>