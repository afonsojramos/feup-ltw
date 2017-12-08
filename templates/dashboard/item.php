<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Item.php");
$item;
if (!isset($item)) {
	if (isset($_GET["itemId"])) {
		global $item;
		$item = new Item();
		if (!$item->load($_GET["itemId"]))
			die("Unable to find list");
		if (!$item->verifyOwnership($_SESSION["userId"]))
			die("No permission to see list");
	} else {
		die("Missing parameters");
	}
}
?>
<div class="todoListItem" data-itemId="<?= $item->itemId ?>">
	<input class="checkbox todoItem" id = "todoItem_<?= $item->itemId ?>" type="checkbox"  <?= $item->completed ? "checked" : "" ?>><label class="todoItemLabel" ><?= htmlentities($item->content) ?></label>
	<input type="text" class="editItemTextBox hidden" id="editItem_<?= $item->itemId ?>">
	<i class="material-icons close removeListItem show-on-hover">close</i>
</div>