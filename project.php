<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");


//load the prject if it exists project
require_once(dirname(__FILE__) . "/includes/common/check_request.php");
verifyAttributes($_GET, ["projectId"]);

require_once(dirname(__FILE__) . "/classes/Project.php");
require_once(dirname(__FILE__) . "/classes/Member.php");

//check if the user is a member of the project
$member = new Member($_GET["projectId"], $_SESSION["userId"]);
if (!$member->load()) {
	echo "No permission for you.";
	die();
}

//load the project
$project = new Project($_GET["projectId"]);
$project->load();

//load page defaults
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Project " . htmlentities($project->title);
$PAGE["styles"] = array_merge($PAGE["styles"], ["card_form.css"]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("editableOnClick.js", "search.js", "project.js", "ajax.js"));
$PAGE["includeCSRF"] = true;
$PAGE["bodyClasses"][] = $project->colour;

require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");
?>
<div class="container">
	<h1 class="center">Project: <?= htmlentities($project->title) ?></h1>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>