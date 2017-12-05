<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");
$project;
if (!isset($project)) {
	if (isset($_GET["projectId"])) {
		global $project;
		$project = new Project();
		if (!$project->load($_GET["projectId"]))
			die("Unable to find project");
		/* if (!$project->verifyOwnership($_SESSION["userId"]))
			die("No permission to see list"); */
	} else {
		die("Missing parameters");
	}
}
?>
<div class="project" data-projectId="<?= $project->projectId ?>">
	<label class="projectTitle" ><?= htmlentities($project->title) ?></label>
</div>