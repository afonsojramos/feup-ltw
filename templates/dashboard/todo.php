<div class="todo show-on-hover-parent color-<?= $todos->colour ?>" id = "todo_<?= $todos->todoListId; ?>">
	<h3 class="noMargin"><?= htmlentities($todos->title) ?></h3>
	<?php
		foreach ($todos->items as $item) {
			?><div><input id = "item-<?= $item->itemId ?>" type="checkbox" class="checkbox" checked> Limpar a casa</div><?php
		}
	?>
	<hr/>
	<span class="listFooter show-on-hover">
		<span class="archive"><a href="#"><i class="material-icons">archive</i></a></span>
		<span class="delete"><a href="#"><i class="material-icons">delete</i></a></span>
		<span class="share"><a href="#"><i class="material-icons">share</i></a></span>
	</span>
</div>