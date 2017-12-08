<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");

require_once(dirname(__FILE__) . "/includes/common/check_request.php");
verifyAttributes($_GET, ["projectId"]);

require_once(dirname(__FILE__) . "/classes/Project.php");
require_once(dirname(__FILE__) . "/classes/Member.php");

//load the project
$project = new Project();
if (!$project->load($_GET["projectId"])) {
	echo "Project not found";
	die();
}

//check if the user is a member of the project
if (!$project->verifyOwnership($_SESSION["userId"])) {
	echo "No permission for you.";
	die();
}

//load page defaults
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Project " . htmlentities($project->title);
$PAGE["styles"] = array_merge($PAGE["styles"], ["card_form.css", "project.css"]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("editableOnClick.js", "search.js", "project.js", "ajax.js"));
$PAGE["includeCSRF"] = true;
$PAGE["bodyClasses"][] = $project->colour;

require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");


//load statistics
require_once(dirname(__FILE__) . "/classes/TodoList.php");
$todos = TodoList::getAllByUserProject($_SESSION["userId"], $project->projectId);
$countArchived = 0;
$countItems = 0;
$countIncompleteItems = 0;

foreach ($todos as $todo) {
	if ($todo->archived) $countArchived++;
	$countItems += count($todo->items);
	foreach ($todo->items as $item)
		if (!$item->completed)
		$countIncompleteItems++;
}

$statistics = array(
	"Members" => Member::countByProject($project->projectId),
	"Todo Lists" => count($todos),
	"Archived Todo Lists" => $countArchived,
	"Completed Items" => $countItems - $countIncompleteItems,
	"Incomplete Items" => $countIncompleteItems,
	"Total Items" => $countItems
);

//load members
require_once(dirname(__FILE__) . "/classes/User.php");
$members = User::getAllByProject($project->projectId);

?>
<div class="container" id="projectMainContainer" data-projectId="<?= $project->projectId ?>">
	<h1 class="center"><span class="strong"><?= htmlentities($project->title) ?></span></h1>
	<h2 class="strong">Statistics</h2>
	<div class="statistics">
		<?php foreach ($statistics as $name => $value) : ?>
		<ul class="statistics list">
			<li class="statistics value"><?= $value ?></li>
			<li class="statistics name"><?= $name ?></li>
		</ul>
		<?php endforeach ?>
	</div>

	<h2 class="strong">Description</h2>
	<div class="description">
		<?= htmlentities($project->description) ?>
	</div>

	<form id="projectForm" action="actions/project/edit_project.php" method="post">
		<?php insertCsrfToken(); ?>
		<div class="cardForm">
			<div class="formHeader">
				<h3 class="formTitle">Edit Project</h3>
			</div>
			<div class="formBody">
				<input type="hidden" name="projectId" value="<?= $project->projectId ?>">
				<div>
					<input type="text" name="title" placeholder="Project title" value="<?= htmlentities($project->title) ?>" required>
				</div>
				<div>
					<textarea name="description" rows="5" placeholder="Project Description"><?= htmlentities($project->description) ?></textarea>
				</div>
				<div>
					<select name="colour">
					<?php
				$colours = array("white", "red", "orange", "yellow", "green", "teal", "blue", "purple", "pink", "brown");
				foreach ($colours as $colour) : ?>
							<option class="<?= $colour ?>" value="<?= $colour ?>" <?= $project->colour == $colour ? "selected" : "" ?>><?= ucfirst($colour) ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<footer class="formFooter">
				<input type="submit" value="Save">
			</footer>
		</div>
	</form>

	<hr/>
	<h2 class="center strong">Members</h2>
	<div class="members">
		<div class="errors"></div>
		<?php foreach ($members as $member) :
		$base = "public/images/profile/";
		$filename = $base . $member->userId . ".jpg";
		if (!file_exists($filename)) $filename = $base . "default.png" ?>
			<div class="memberContainer">
				<a class="memberLink" href="user.php?userId=<?= $member->userId ?>" title="user: <?= htmlentities($member->username) ?>"><img class="member" href="user.php?userId=<?= $member->userId ?>" src="<?= $filename ?>"/></a>
				<h2 class="center"> <?= htmlentities($member->username); ?></h2>
				<hr/>
				<a class="removeMember" data-userId="<?= $member->userId ?>" title="remove member"><i class="material-icons">delete</i></a>
			</div>
		<?php endforeach ?>
		<br/>
		<a id="addMember"><i class="material-icons">add</i> Add member</a>
	</div>


	<hr/>
	<h1 class="center strong" id="actions">Actions</h1>

	<h1 class="strong"><a id="deleteProject"><i class="material-icons">delete</i> Delete Project</a></h1>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>