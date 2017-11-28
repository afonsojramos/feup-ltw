<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
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

	public function __construct($itemId = null, $completed = false, $content = "", $dueDate = null, $todoListId = -1){
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
}
