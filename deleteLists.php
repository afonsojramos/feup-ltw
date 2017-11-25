<?php

require_once(dirname(__FILE__)."/classes/TodoList.php");

$query = new QueryBuilder(TodoList::class);
$query->where("1")->delete();

header("Location: dashboard.php");