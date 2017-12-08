<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/TodoList.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/User.php");
$todo;
if (!isset($todo)) {
	if (isset($_GET["todoListId"])) {
		global $todo;
		$todo = new TodoList();
		if (!$todo->load($_GET["todoListId"]))
			die("Unable to find list");
	} else {
		die("Missing parameters");
	}
}

//check permission
if (!$todo->verifyOwnership($_SESSION["userId"]))
	die("No permission to see list");
//get members from project
$members = User::getAllByProject($todo->projectId);


require(dirname(__FILE__) . "/modal_edit_list.php");
?>

<div class="todo show-on-hover-parent colour-<?= $todo->colour ?>" id = "todo_<?= $todo->todoListId; ?>"  data-todoListId="<?= $todo->todoListId ?>" >
	<h3 class="noMargin"><span class="todoTitle"><?= htmlentities($todo->title) ?></span></h3>
	<div class="errors"></div>
	<input type="text" class="hidden" id="editTitle_<?= $todo->todoListId ?>">
	<div class="items">
		<?php foreach ($todo->items as $item){
			include("item.php");
		} ?>
	</div>
	<div class="addItemContainer">
		<span class="addItemText"><i class="material-icons floatLeft">add</i> Add a new item</span>
		<input type="text" class="addItemText hidden" id="addItem_<?= $todo->todoListId ?>">
	</div>
	<hr/>
	<div class="tags">
	<?php foreach ($todo->getTags() as $tag) :?>
		<a href="dashboard.php?tags=<?= urlencode($tag) ?>"class="tag"><?= htmlentities($tag) ?></a>
	<?php endforeach ?>
	</div>
	<div class="todoMembersContainer">
		<?php foreach ($members as $member):
			$base = "public/images/profile/";
			$filename = $base . "thumb" . $member->userId . ".jpg";
			if (!file_exists(dirname(__FILE__) . "/../../" . $filename)) $filename = $base . "default.png" ?>
				<a class="memberLink" href="user.php?userId=<?= $member->userId ?>" title="user: <?= htmlentities($member->username) ?>"><img alt="profile picture" class="todoMember" src="<?= $filename ?>"/></a>
		<?php endforeach ?>
	</div>
	<?php
		if ($todo->projectId != 0):
			$project = new Project($todo->projectId);
			$project->load();
		?>
		<a class="todoProject" href="project.php?projectId=<?= $project->projectId ?>" title="Todo List belongs to project: <?= htmlentities($project->title) ?>"><?= htmlentities($project->title) ?></a>
	<?php endif ?>
	<span class="listFooter show-on-hover">
		<span class="archive"><a href="#"><i class="material-icons"><?= $todo->archived ? "unarchive" : "archive" ?></i></a></span>
		<span class="colour" id="openEditListModal-<?= $todo->todoListId ?>"><a href="#"><i class="material-icons">mode_edit</i></a></span>
		<span class="delete"><a href="#"><i class="material-icons">delete</i></a></span>
		<span class="share"><a href="#"><i class="material-icons">share</i></a></span>
	</span>
</div>