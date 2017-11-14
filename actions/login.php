<?php
	require_once(dirname(__FILE__)."./../includes/common/session.php");

	require_once(dirname(__FILE__)."./../classes/User.php");

	$result = array("success"=>false);

	if(isset($_POST["username"]) && isset($_POST["password"])){
		$query = new QueryBuilder(User::class);

		$query->select("password")->where("username = :username");
		$query->addParam("username", $_POST["username"]);

		if($line = $query->get()){
			if(password_verify($_POST["password"], $line["password"])){
				$result["success"] = true;

			}
		}
	}

	echo json_encode($result);