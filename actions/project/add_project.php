<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["title", "description", "colour"]);

require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/Member.php");

$result = array("success" => false);

$project = new Project();
// $project->userId = $_SESSION["userId"];
$project->loadFromArray($_POST);//load project properties from post

if ($project->validate()) {
	if ($project->insert()) {//created project
		$member = new Member($project->projectId, $_SESSION["userId"]);
		if($member->insert()){
			$result["success"] = true;
		}
		$result["projectId"] = $project->projectId;
	}
} else {
	$result["errors"] = $project->errors;
}

echo json_encode($result);