<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["projectId", "username"]);

require_once(dirname(__FILE__) . "/../../classes/User.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/Member.php");

$result = array("success" => false);
$project = new Project;
if ($project->load($_POST["projectId"])) {
	if ($project->verifyOwnership($_SESSION["userId"])) {
		$user = new User;
		if ($user->loadFromUsernameOrEmail($_POST["username"])) {
			$member = new Member($project->projectId, $user->userId);
			if ($member->load()) {
				$result["errors"] = array("User is already in project");
			} elseif ($member->insert()) {
				$result["success"] = true;
			} else {
				$result["errors"] = array("Internal error");
			}
		} else {
			$result["errors"] = array("User not found");
		}
	} else {
		$result["errors"] = array("No permission");
	}
} else {
	$result["errors"] = array("Project not found");
}

echo json_encode($result);