<div class="todo show-on-hover-parent color-<?= $todo->colour ?>" id = "todo_<?= $todo->todoListId; ?>"  data-todoListId="<?= $todo->todoListId ?>" >
	<h3 class="noMargin"><?= htmlentities($todo->title) ?></h3>
	<?php foreach ($todo->items as $item) : ?>
		<div class="todoListItem">
			<input class="checkbox todoItem" id = "todoItem_<?= $item->itemId ?>" data-itemId="<?= $item->itemId ?>" type="checkbox"  <?= $item->completed ? "checked" : "" ?>><label class="todoItemLabel" data-itemId="<?= $item->itemId ?>" ><?= $item->content ?></label>
			<input type="text" class="addItemTextBox hidden" id="editItem_<?= $item->itemId ?>">
			<i class="material-icons close removeListItem show-on-hover" id="deleteItem_<?= $item->itemId ?>" data-itemId="<?= $item->itemId ?>" type="button">close</i>
		</div>
	<?php endforeach ?>
	<div class="addItemContainer">
		<span> 
			<i class="material-icons">add</i>
			<span class="addItemText"> Add a new item</span>
		</span>
	</div>
	<div class="errors"></div>
	<hr/>
	<span class="listFooter show-on-hover">
		<span class="archive" data-todoListId="<?= $todo->todoListId ?>"><a href="#"><i class="material-icons"><?= $todo->archived ? "unarchive" : "archive" ?></i></a></span>
		<span class="delete" data-todoListId="<?= $todo->todoListId ?>"><a href="#"><i class="material-icons">delete</i></a></span>
		<span class="share"><a href="#"><i class="material-icons">share</i></a></span>
	</span>
</div>