<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
class TodoList extends QueryBuilder{
	public $todoListId;//attributes that do not require update can be public
	protected $title;
	protected $tags;
	protected $colour;
	protected $archived;
	protected $link;
	protected $userId;
	protected $projectId;

	public $items; // a list of Item

	public static $ignoreProperties = array("items");//properties to ignore on the sql building
	public static $validationRules = array(//validation rules for update method
		"title"=>"length:3:200",
		"colour"=>"in:white:red:orange:yellow:green:teal:blue:indigo:purple:pink:brown:grey"
	);

	public function __construct($todoListId = null, $title = "", $tags = "", $colour = "white", $archived = false, $link = "", $userId = -1, $projectId = 0){
		$this->todoListId = $todoListId;
		$this->title = $title;
		$this->tags = $tags;
		$this->colour = $colour;
		$this->archived = $archived;
		$this->link = $link;
		$this->userId = $userId;
		$this->projectId = $projectId;
		$this->items = array();
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}

	public function loadItems(){
		//TODO load this list's items
	}

	public static function getAllByUser($userId){
		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("userId = :userId")->addParam("userId", $userId)->getAll();
		var_dump($lines);
		$lists = array();//an array of TodoList
		foreach ($lines as $line) {
			# code...
		}

	}
}
