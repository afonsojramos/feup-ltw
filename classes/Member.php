<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
class Member extends QueryBuilder{
	public $projectId;//attributes that do not require update can be public
	protected $userId;
	public static $primaryKeys = array("projectId", "userId");
	public function __construct($projectId = null, $userId = null){
		$this->projectId = $projectId;
		$this->userId = $userId;
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}
}
