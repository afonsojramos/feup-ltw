<?php
	require_once(dirname(__FILE__)."/session.php");

	if (loggedIn()) {
		require_once(dirname(__FILE__)."/../../templates/common/navbar_login.php");
	} else {
		require_once(dirname(__FILE__)."/../../templates/common/navbar_logout.php");
	}
