<?php
    require_once(dirname(__FILE__)."/includes/common/only_allow_login.php");
    require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Dashboard for " . $_SESSION["username"];
	$PAGE["styles"] = array_merge($PAGE["styles"], ["modal.css", "todo_list.css", "fab.css", "card_form.css"]);
	$PAGE["scripts"] = array("dashboard.js", "modal.js", "modal_add_list.js", "search.js", "ajax.js");
	$PAGE["showSideBar"] = true;
    require_once(dirname(__FILE__)."/templates/common/header.php");
    require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");

    require_once(dirname(__FILE__)."/templates/dashboard/sidebar.php");
?>


<div class="container" id="dashboardContainer">
    <h1>Welcome to the dashboard</h1>
    <p>Let the todos begin.</p>


	<?php

		require_once(dirname(__FILE__)."/classes/TodoList.php");
		$query = new QueryBuilder(TodoList::class);
		var_dump($query->select()->getAll());

	?>



    <div class="todos">



		<div class="todo"> asdasd </div>
    	<div class="todo"><img src="public/images/background.jpeg" width="200px" height="auto"></div>
    	<div class="todo"><img src="public/images/logo_ltw.png" width="auto" height="200px"></div>
    	<div class="todo"> asdasd </div>
    	<div class="todo"> asafasdf assag sfd hgd hgd  hfh fhfdasd </div>
    	<div class="todo"><img src="public/images/logo_ltw.png" width="auto" height="200px"></div>
    	<div class="todo"><img src="public/images/background.jpeg" width="200px" height="auto"></div>
    	<div class="todo"><img src="public/images/logo_ltw.png" width="auto" height="200px"></div>
    	<div class="todo"> asafasdf assag sfd hgd hgd  hfh fhfdasd </div>
    	<div class="todo"><img src="public/images/logo_ltw.png" width="auto" height="200px"></div>
    	<div class="todo"><img src="public/images/background.jpeg" width="200px" height="auto"></div>
    	<div class="todo"> asdasd </div>
    	<div class="todo"> asdasd </div>
    	<div class="todo"><img src="public/images/background.jpeg" width="200px" height="auto"></div>
    	<div class="todo"> asdasd </div>
    	<div class="todo"> asafasdf assag sfd hgd hgd  hfh fhfdasd </div>
    </div>

	<?php require_once(dirname(__FILE__)."/templates/dashboard/modal_add_list.php"); ?>

</div><!--/.container-->


<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>
