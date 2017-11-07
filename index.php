<?php

	require_once(dirname(__FILE__)."/classes/User.php");

	function test($q){
		//$q->setKey(["id1", "id2"]);
		display($q->select()->where()->getAll());
		display($q->select()->where(1)->getAll());
		echo "<h3>CLEAR</h3>";
		display($q->clear()->select()->where()->getAll());
		display($q->clear()->select()->where(1)->getAll());
		echo "<h3>ORDER BY</h3>";
		display($q->clear()->select()->where()->orderBy("userId DESC")->getAll());
		display($q->clear()->select()->orderBy("userId DESC")->getAll());
		echo "<h3>LIMIT</h3>";
		display($q->clear()->select()->where()->limit(10)->getAll());
		display($q->clear()->select()->limit(100)->getAll());
		echo "<h3>OFFSET</h3>";
		display($q->clear()->select()->limit(100)->offset(15)->getAll());
		display($q->clear()->select()->limit(100)->offset(150)->getAll());
		echo "<h3>COMPLETE</h3>";
		display($q->clear()->select()->where()->orderBY("name DESC, userId ASC")->limit(100)->offset(1)->getAll());
	}


	//$user = new User(10, "Afonso", 20);
	function display($lines){
		echo "<ul>";
		foreach ($lines as $line) {
			echo "<li>";
			var_dump($line);
			echo"</li>";
		}
		echo"</ul>";
	}
	function selectAll(){
		$query = new QueryBuilder(User::class);
		display($query->select()->getAll());
	}
	selectAll();

 	echo "<hr/>";


	$user = new User();
	$user->load(random_int(1, 10));
	$user->userId = 100;
	//$user->name = ("André Alves Alvarinho" . random_int(0, 100));
	$user->update();


	$user = new User(null, "Eduardo Elias", 40);
	$user->insert();

	/*
	//Delete all Users:
	$query = new QueryBuilder(User::class);
	$query->where("1")->delete(); */


/* 	require_once(dirname(__FILE__)."/database/connection.php");
	$query = "UPDATE users SET name = :name WHERE userId = :userId";
	$stmt = $connection->prepare($query);
	$pname = ":name";
	$pId = ":userId";
	$name = "AAA2";
	$id = "1";
	$stmt->bindParam($pname, $name);
	$stmt->bindParam($pId, $id);
	$stmt->execute(); */

?>