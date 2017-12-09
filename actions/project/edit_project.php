<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["projectId", "title", "description", "colour"]);

require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/Member.php");

$result = array("success" => false);

$project = new Project();
if ($project->load($_POST["projectId"])) {
	if ($project->verifyOwnership($_SESSION["userId"])) {
		$project->title = $_POST["title"];
		$project->description = $_POST["description"];
		$project->colour = $_POST["colour"];
		if ($project->validate()) {
			$project->update();
			$result["success"] = true;
			// header("Location: ../../project.php?projectId=".$project->projectId);
		} else {
			$result["errors"] = $project->errors;
		}
	} else {
		$result["errors"] = array("No permission");
	}
} else {
	$result["errors"] = array("Project not found");
}

echo json_encode($result);