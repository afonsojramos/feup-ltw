<div class="todo show-on-hover-parent color-<?= $todo->colour ?>" id = "todo_<?= $todo->todoListId; ?>"  data-todoListId="<?= $todo->todoListId ?>" >
	<h3 class="noMargin"><?= htmlentities($todo->title) ?></h3>
	<?php foreach ($todo->items as $item) : ?>
		<div>
			<input class="checkbox todoItem" id = "todoItem_<?= $item->itemId ?>" data-itemId="<?= $item->itemId ?>" type="checkbox"  <?= $item->completed ? "checked" : "" ?>><label class="todoItemLabel" for="todoItem_<?= $item->itemId ?>"><?= $item->content ?></label>
		</div>
	<?php endforeach ?>
	<div class="errors"></div>
	<hr/>
	<span class="listFooter show-on-hover">
		<span class="archive"><a href="#"><i class="material-icons">archive</i></a></span>
		<span class="delete"><a href="#"><i class="material-icons">delete</i></a></span>
		<span class="share"><a href="#"><i class="material-icons">share</i></a></span>
	</span>
</div>