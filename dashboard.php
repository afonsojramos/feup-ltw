<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Dashboard for " . $_SESSION["username"];
$PAGE["styles"] = array_merge($PAGE["styles"], ["modal.css", "todo_list.css", "fab.css", "card_form.css"]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("editableOnClick.js", "dashboard.js", "modal.js", "modal_add_list.js", "modal_edit_list.js", "modal_add_project.js", "search.js", "ajax.js"));
$PAGE["showSideBar"] = true;
$PAGE["includeCSRF"] = true;
require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");

require_once(dirname(__FILE__) . "/templates/dashboard/sidebar.php");
//get all todos for the current query
require_once(dirname(__FILE__) . "/classes/TodoList.php");
$todos = TodoList::getAllQuery($_GET, $_SESSION["userId"]);
?>

<div class="container" id="dashboardContainer">
	<?php if (count($todos) == 0) : ?>
		<h1 class="center">This page looks a bit empty <i class="material-icons">sentiment_very_dissatisfied</i></h1>
	<?php endif ?>
    <div class="todos">
		<?php
		foreach ($todos as $todo)
			include(dirname(__FILE__) . "/templates/dashboard/todo.php");
		?>
    </div>
	<?php require_once(dirname(__FILE__) . "/templates/dashboard/modal_add_list.php"); ?>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>