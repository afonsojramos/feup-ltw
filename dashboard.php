<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Dashboard for " . $_SESSION["username"];
$PAGE["styles"] = array_merge($PAGE["styles"], ["modal.css", "todo_list.css", "fab.css", "card_form.css"]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("editableOnClick.js", "dashboard.js", "modal.js", "modal_add_list.js", "search.js", "ajax.js"));
$PAGE["showSideBar"] = true;
$PAGE["includeCSRF"] = true;
require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");

require_once(dirname(__FILE__) . "/templates/dashboard/sidebar.php");
?>

<div class="container" id="dashboardContainer">
    <div class="todos">
		<?php
			require_once(dirname(__FILE__) . "/classes/TodoList.php");
			$todos = TodoList::getAllQuery($_GET, $_SESSION["userId"]);
			foreach ($todos as $todo)
				include(dirname(__FILE__) . "/templates/dashboard/todo.php");
		?>
    </div>
	<?php require_once(dirname(__FILE__) . "/templates/dashboard/modal_add_list.php"); ?>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>