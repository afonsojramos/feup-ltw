<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["projectId"]);

require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/Member.php");

$result = array("success" => false);
$project = new Project;
if ($project->load($_POST["projectId"])) {
	if ($project->verifyOwnership($_SESSION["userId"])) {
		if ($project->delete()) {
			$result["success"] = true;
		} else {
			$result["errors"] = array("Internal error");
		}
	} else {
		$result["errors"] = array("No permission");
	}
} else {
	$result["errors"] = array("Project not found");
}

echo json_encode($result);