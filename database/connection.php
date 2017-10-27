<?php
	$dbFilePath = dirname(__FILE__)."/todo.db";
	$connection = new PDO('sqlite:'.$dbFilePath);