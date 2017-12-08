<?php
if (!isset($item)) {
	die("No permission to see item");
}
?>
<div class="todoListItem">
	<input class="checkbox todoItem" type="checkbox" <?= $item->completed ? "checked" : "" ?> disabled><label class="todoItemLabel" ><?= htmlentities($item->content) ?></label>
</div>