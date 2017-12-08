<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["projectId", "userId"]);

require_once(dirname(__FILE__) . "/../../classes/User.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/Member.php");

$result = array("success" => false);
$project = new Project;
if ($project->load($_POST["projectId"])) {
	if ($project->verifyOwnership($_SESSION["userId"])) {
		$member = new Member($project->projectId, $_POST["userId"]);
		if ($member->load()) {
			if (Member::countByProject($project->projectId) > 1) {//the project has no more members
				if ($member->delete()) {
					$result["success"] = true;
				} else {
					$result["errors"] = array("Internal error");
				}
			} else {
				$result["errors"] = array("Unable to delete last member");
			}
		} else {
			$result["errors"] = array("User is not a member of this project");
		}
	} else {
		$result["errors"] = array("No permission");
	}
} else {
	$result["errors"] = array("Project not found");
}

echo json_encode($result);