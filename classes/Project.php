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

	public function loadContent(){
		//TODO load this projects's content
	}

	public static function getAllForProjects($projectsIds){
		//create sql parameter for list of projects
		$i = 0;
		$kvIds = array();//key value array for the ids
		$parameterIds = array();//key value array for the query
		foreach ($projectsIds as $id) {
			$parameterIds[] = ":list_$i";
			$kvIds["list_$i"] = $id;
			$i++;
		}
		$parameterString = implode(", ", $parameterIds);

		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("projectId IN ($parameterString)")->addParams($kvIds)->getAll();

		$projects = array();//an array of projects
		foreach ($lines as $line) {
			$itprojectem = new Project();
			$project->loadFromArray($line);
			$projects[] = $iprojecttem;
		}
		return $projects;
	}
}
