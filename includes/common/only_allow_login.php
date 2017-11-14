<?php
	require_once(dirname(__FILE__)."/session.php");
	if(!loggedIn()){
		header("Location: index.php");
		die();
	}
