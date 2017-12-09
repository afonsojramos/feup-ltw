<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
verifyAttributes($_POST, ["todoListId", "title", "tags", "colour", "projectId"]);

require_once(dirname(__FILE__) . "/../../classes/TodoList.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");

$result = array("success" => false);

$todoList = new TodoList;
if ($todoList->load($_POST["todoListId"])) {
	if ($todoList->verifyOwnership($_SESSION["userId"])) {
		if($_POST["title"]!="") $todoList->title = $_POST["title"];
		if($_POST["colour"]!="") $todoList->colour = $_POST["colour"];
		if($_POST["tags"]!="") $todoList->tags = $_POST["tags"];
		if ($todoList->validate()) {
			$project = new Project($_POST["projectId"]);
			if ($project->projectId == 0 || ($project->load() && $project->verifyOwnership($_SESSION["userId"]))) {
				if($todoList->projectId != $_POST["projectId"]){
					$result["alteredProjectId"] = true;
				}
				$todoList->projectId = $_POST["projectId"];
				if ($todoList->update() !== false) {
					$result["success"] = true;
				} else {
					$result["errors"] = array("Could not delete Todo List");
				}
			} else {
				$result["errors"] = array("Cannot nodify this project");
			}
		} else {
			$result["errors"] = $todoList->errors;
		}
	} else {
		$result["errors"] = array("User has no permission to access Todo List");
	}
} else {
	$result["errors"] = array("Could not load Todo List");
}

echo json_encode($result);