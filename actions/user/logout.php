<?php
	//Destroy the current user session
	session_start();
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie('PHPSESSID',null, time()-7200,'', 0, 0);//Destroy the cookie

	header("Location: ../../index.php");//redirect to index