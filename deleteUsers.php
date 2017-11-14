<?php

require_once(dirname(__FILE__)."/classes/User.php");

$query = new QueryBuilder(User::class);
$query->where("1")->delete();

header("Location: index.php");