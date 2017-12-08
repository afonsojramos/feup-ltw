<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");

//if a get project id is set, that is the default one
if (isset($_GET['projectId'])) {
	$selectedProjectId = $_GET['projectId'];
}else{
	$selectedProjectId = 0;
}
//load projects if needed
if(!isset($projects) || !is_array($projects)){
	$projects = Project::getAllByUser($_SESSION["userId"]);
}
if(count($projects) == 0 || (count($projects) && $projects[0]->projectId != "0")){
	//create default project
	$projects = array_merge(array(new Project("0", "Private (No Project)")), $projects);
}
?>

<form class="modal modalEditList" target="openEditListModal-<?= $todo->todoListId ?>" id ="modalEditList-<?= $todo->todoListId ?>">
	<div class="errors"></div>
	<div class="modalContent cardForm grid">
		<div class="formHeader">
			<h3 class="formTitle">Edit Todo List</h3>
		</div>
		<div class="formBody">
			<input type="hidden" name="todoListId" value="<?= $todo->todoListId ?>">
			<div>
				<input type="text" name="title" placeholder="List title" value="<?= htmlentities($todo->title) ?>" required>
			</div>
			<div>
				<input type="text" name="tags" placeholder="Tags (Comma separate)" value="<?= $todo->tags ?>" />
			</div>
			<div>
				<select name="projectId">
					<?php foreach ($projects as $p): ?>
						<option value="<?= $p->projectId ?>" <?= $p->projectId==$todo->projectId?"selected":"" ?>><?= htmlentities($p->title) ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div>
				<select name="colour">
					<?php
						$colours = array("white", "red", "orange", "yellow" , "green", "teal" , "blue", "purple", "pink", "brown");
						foreach ($colours as $colour): ?>
							<option class="<?= $colour ?>" value="<?= $colour ?>" <?= $todo->colour==$colour?"selected":"" ?>><?= ucfirst($colour) ?></option>
						<?php endforeach ?>
				</select>
			</div>
		</div>
		<footer class="formFooter">
			<input type="submit" value="Edit">
		</footer>
	</div>
</form>