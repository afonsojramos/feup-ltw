<?php
require_once 'token.php';

sessionStart();

function sessionStart() {
	if (session_status() != PHP_SESSION_ACTIVE) {
		session_start();
		session_regenerate_id(true);
		if (!isset($_SESSION['csrf'])) {
			$_SESSION['csrf'] = generate_random_token();
		}
	}
}

function loggedIn() {
	return isset($_SESSION['userId']);
}
