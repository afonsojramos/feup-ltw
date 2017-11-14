<?php
	sessionStart();

	function sessionStart(){
		if(session_status() != PHP_SESSION_ACTIVE){
			session_start();
			session_regenerate_id(true);
		}
	}

	function loggedIn() {
		return isset($_SESSION['userId']);
	}