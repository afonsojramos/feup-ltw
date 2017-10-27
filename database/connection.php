<?php
	$dbFilePath = dirname(__FILE__)."/todo.db";
	$connection = new PDO('sqlite:'.$dbFilePath);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);