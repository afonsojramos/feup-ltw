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
	"Members:" => Member::countByProject($project->projectId),
	"Todo Lists:" => count($todos),
	"Archived Todo Lists:" => $countArchived,
	"Complete Items:" => $countItems - $countIncompleteItems,
	"Incomplete Items:" => $countIncompleteItems,
	"Total Items:" => $countItems
);

//load members
require_once(dirname(__FILE__) . "/classes/User.php");
$members = User::getAllByProject($project->projectId);

?>
<div class="container">
	<h1 class="center">Project: <span class="strong"><?= htmlentities($project->title) ?></span></h1>
	<h2 class="strong">Statistics</h2>
	<table class="statistics">
		<tbody>
			<?php foreach ($statistics as $name => $value) : ?>
			<tr>
				<td><?= $name ?></td>
				<td><?= $value ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<h2 class="strong">Description</h2>
	<div>
		<?= htmlentities($project->description) ?>
	</div>

	<form class="grid" action="actions/project/edit_project.php" method="post">
		<?php insertCsrfToken(); ?>
		<div class="modalContent cardForm grid">
			<div class="formHeader">
				<h3 class="formTitle">New Project</h3>
			</div>
			<div class="formBody">
				<input type="hidden" name="projectId" value="<?= $project->projectId ?>">
				<div>
					<input type="text" name="title" placeholder="Project title" value="<?= htmlentities($project->title) ?>" required>
				</div>
				<div>
					<textarea name="description" placeholder="Project Description"><?= htmlentities($project->description) ?></textarea>
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
	<h1 class="center strong">Members</h1>
	<div class="members">
	<?php foreach ($members as $member) :
	$base = "public/images/profile/";
$filename = $base . $member->userId . ".jpg";
if (!file_exists($filename)) $filename = $base . "default.png" ?>
		<div class="memberContainer">
			<img class="member" src="<?= $filename ?>"/>
			<h2 class="center"><?= $member->username; ?></h2>
			<hr/>
			<a title="remove member"><i class="material-icons">delete</i></a>
		</div>
		<?php endforeach ?>
		<div class="memberContainer">
			<img class="member" src="public/images/profile/2.jpg">
			<h2 class="center"><?= $member->username; ?></h2>
			<hr/>
			<a><i class="material-icons">delete</i></a>
		</div>
		<div class="memberContainer">
			<img class="member" src="public/images/profile/3.jpg">
			<h2 class="center"><?= $member->username; ?></h2>
			<hr/>
			<a><i class="material-icons">delete</i></a>
		</div>
	</div>


	<hr/>
	<h1 class="center strong">Actions</h1>

	<ul>
		<li class="strong"><a> Delete Project</a></li>
		<li class="strong"><a id="addMember"> Add member</a></li>
	</ul>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>