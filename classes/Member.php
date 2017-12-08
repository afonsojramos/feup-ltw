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

	static public function countByProject($projectId){
		$qb = new QueryBuilder(self::class);
		$line = $qb->select("COUNT (userId) AS total")->where("projectId = :projectId GROUP BY projectId")->addParam("projectId", $projectId)->get();
		return $line ? $line["total"] : 0;
	}

	public function countMembersInProject(){
	}
}
