<?php
	session_start();

	function loggedIn() {
		return isset($_SESSION['userId']);
	}
