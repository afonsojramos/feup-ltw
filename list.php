<?php
require_once(dirname(__FILE__) . "/includes/common/session.php");
require_once(dirname(__FILE__) . "/includes/common/check_request.php");
verifyAttributes($_GET, ["link"]);

require_once(dirname(__FILE__) . "/classes/TodoList.php");

//load the list
$todo = new TodoList;
if (!$todo->loadByLink($_GET["link"])) {
	echo "No permission";
	die();
}

//load page defaults
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : READONLY List : " . htmlentities($todo->title);
$PAGE["styles"] = array_merge($PAGE["styles"], ["todo_list.css"]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("search.js"));

require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");

?>
<div class="container">
	<div class="readOnlyList">
		<?php include(dirname(__FILE__) . "/templates/dashboard/todo_readonly.php"); ?>
	</div>

</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>