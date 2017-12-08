<?php
require_once(dirname(__FILE__) . "/../../classes/TodoList.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");
require_once(dirname(__FILE__) . "/../../classes/User.php");
$todo;
if (!isset($todo)) {
	die("Unable to find list");
}

//get members from project
$members = User::getAllByProject($todo->projectId);
?>

<div class="todo show-on-hover-parent colour-<?= $todo->colour ?>" >
	<h3 class="noMargin"><span class="todoTitle"><?= htmlentities($todo->title) ?></span></h3>
	<input type="text" class="hidden">
	<div class="items">
		<?php foreach ($todo->items as $item){
			include("item_readonly.php");
		} ?>
	</div>
	<hr/>
	<div class="tags">
	<?php foreach ($todo->getTags() as $tag) :?>
		<a href="dashboard.php?tags=<?= urlencode($tag) ?>" class="tag"><?= htmlentities($tag) ?></a>
	<?php endforeach ?>
	</div>
	<div class="todoMembersContainer">
		<?php foreach ($members as $member):
			$base = "public/images/profile/";
			$filename = $base . "thumb" . $member->userId . ".jpg";
			if (!file_exists(dirname(__FILE__) . "/../../" . $filename)) $filename = $base . "default.png" ?>
				<a class="memberLink" href="user.php?userId=<?= $member->userId ?>" title="User: <?= htmlentities($member->username) ?>"><img alt="profile picture" class="todoMember" src="<?= $filename ?>"/></a>
		<?php endforeach ?>
	</div>
	<?php
		if ($todo->projectId != 0):
			$project = new Project($todo->projectId);
			$project->load();
		?>
		<a class="todoProject" href="project.php?projectId=<?= $project->projectId ?>" title="Todo List belongs to project: <?= htmlentities($project->title) ?>"><?= htmlentities($project->title) ?></a>
	<?php endif ?>
</div>