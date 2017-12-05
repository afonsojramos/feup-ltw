<?php
require_once(dirname(__FILE__)."/QueryBuilder.php");

class Project extends QueryBuilder{
	public $projectId;//attributes that do not require update can be public
	protected $title;
	protected $description;
	protected $colour;

	public static $validationRules = array(//validation rules for update method
		"title"=>"length:3:30",
		"description"=>"length:3:200",
		"colour"=>"in:white:red:orange:yellow:green:teal:blue:purple:pink:brown"
	);

	public function __construct($projectId = null, $title = "", $description = "", $colour = null){
		$this->projectId = $projectId;
		$this->title = $title;
		$this->description = $description;
		$this->colour = $colour;
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}

}
