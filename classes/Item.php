<?php
require_once(dirname(__FILE__)."/QueryBuilder.php");
require_once(dirname(__FILE__)."/TodoList.php");

class Item extends QueryBuilder{
	public $itemId;//attributes that do not require update can be public
	protected $completed;
	protected $content;
	protected $dueDate;
	protected $todoListId;

	public static $validationRules = array(//validation rules for update method
		"content"=>"length:3:200",
		"dueDate"=>"date"
	);

	public function __construct($itemId = null, $completed = 0, $content = "", $dueDate = null, $todoListId = -1){
		$this->itemId = $itemId;
		$this->completed = $completed;
		$this->content = $content;
		if ($dueDate == null)
			$this->dueDate = date('Y-m-d H:i:s');
		else
			$this->dueDate = $dueDate;
		$this->todoListId = $todoListId;
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}

	public function loadContent(){
		//TODO load this item's content
	}

	public static function getAllForLists($listsIds){
		//create sql parameter for list of items
		$i = 0;
		$kvIds = array();//key value array for the ids
		$parameterIds = array();//key value array for the query
		foreach ($listsIds as $id) {
			$parameterIds[] = ":list_$i";
			$kvIds["list_$i"] = $id;
			$i++;
		}
		$parameterString = implode(", ", $parameterIds);

		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("todoListId IN ($parameterString)")->addParams($kvIds)->getAll();

		$items = array();//an array of Items
		foreach ($lines as $line) {
			$item = new Item();
			$item->loadFromArray($line);
			$items[] = $item;
		}
		return $items;
	}

	public function verifyOwnership($userId){
		$todoList = new TodoList();
		if ($todoList->load($this->todoListId)) {
			if ($todoList->verifyOwnership($userId)) {
				return true;
			}
		}
		return false;
	}
}
