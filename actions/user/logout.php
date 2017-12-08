<?php
require_once(dirname(__FILE__) . "/../../includes/common/session.php");
require_once(dirname(__FILE__) . "/../../classes/User.php");

User::logout();

header("Location: ../../index.php");//redirect to index