<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
class User{
	public $id;
	public $name;
	public $age;
	private $query;

	public function __construct($id, $name, $age){
		$this->id = $id;
		$this->name = $name;
		$this->age = $age;
		$this->query = new QueryBuilder($this);
	}
}
