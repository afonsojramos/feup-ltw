<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/TodoList.php");
require_once(dirname(__FILE__) . "/modal_edit_list.php");
$todo;
if (!isset($todo)) {
	if (isset($_GET["todoListId"])) {
		global $todo;
		$todo = new TodoList();
		if (!$todo->load($_GET["todoListId"]))
			die("Unable to find list");
		if (!$todo->verifyOwnership($_SESSION["userId"]))
			die("No permission to see list");
	} else {
		die("Missing parameters");
	}
}
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
		<i class="material-icons floatLeft">add</i>
		<span class="addItemText"> Add a new item</span>
		<input type="text" class="addItemText hidden" id="addItem_<?= $todo->todoListId ?>">
	</div>
	<hr/>
	<div class="tags">
	<?php foreach ($todo->getTags() as $tag) :?>
		<a href="dashboard.php?tag=<?= urlencode($tag) ?>"class="tag"><?= htmlentities($tag) ?></a>
	<?php endforeach ?>
	</div>
	<span class="listFooter show-on-hover">
		<span class="archive"><a href="#"><i class="material-icons"><?= $todo->archived ? "unarchive" : "archive" ?></i></a></span>
		<span class="colour" id="openEditListModal"><a href="#"><i class="material-icons">mode_edit</i></a></span>
		<span class="delete"><a href="#"><i class="material-icons">delete</i></a></span>
		<span class="share"><a href="#"><i class="material-icons">share</i></a></span>
	</span>
</div>