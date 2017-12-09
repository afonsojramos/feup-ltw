<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
require_once(dirname(__FILE__)."/Item.php");
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
		"colour"=>"in:white:red:orange:yellow:green:teal:blue:purple:pink:brown"
	);

	public function __construct($todoListId = null, $title = "", $tags = "", $colour = "white", $archived = 0, $link = "", $userId = -1, $projectId = 0){
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

	public function __set($name, $value){
		if($name == "tags"){//REGEX to remove all whitespaces from the tags
			$value = preg_replace('/\s+/', '', $value);
		}
		return parent::__set($name, $value);
	}

	public function loadByLink($link){
		$this->link = $link;
		$line = $this->select()->where("link = :link")->get();
		if ($line) {
			$this->loadFromArray($line);
			$this->items = self::loadTodoFromDatabase(array($line), true)[0]->items;
			return true;
		}
		return false;
	}

	public function share(){
		$this->__set("link", bin2hex(mcrypt_create_iv(64, MCRYPT_DEV_URANDOM)));
		return $this->update();
	}

	// Determine if query (copy os $_GET) is a search query, or a regular query.
	private static function isSearchQuery($query){
		foreach( array('members', 'flags', 'projects', 'words', 'expressions') as $p){
			if(array_key_exists($p, $query)){
				return true;
			}
		}
		return false;
	}

	private static function buildSearchQuery($query, $userId, $qb){

		$params=array();
		$params['userId']=$userId;
		$pq=array();

		if(isset($query['words'])){
			$words=explode(',', $_GET['words']);
		}else{
			$words=array();
		}
		if(isset($query['expressions'])){
			$expressions=explode(',', $_GET['expressions']);
		}else{
			$expressions=array();
		}
		$words = array_merge($words, $expressions);
		

		/**
		 * (SELECT projectId FROM members WHERE userId IN (
         *		SELECT userId FROM users WHERE username IN (:username1, username2) AND projectId in (SELECT projectId FROM members WHERE userId = :userId)) --
         * )
		 */
		if(isset($query['members'])){
			$members=explode(',', $_GET['members']);
			$pq['members']="SELECT projectId FROM members WHERE userId IN (SELECT userId FROM users WHERE username in (";
			$i = 0;
			foreach($members as $m){
				$pq['members'].=":username{$i}, ";
				$params["username{$i}"] = $m;

				$i++;
			}

			$pq['members']=substr($pq['members'], 0, -1*strlen(", ")); // remove '"'from end of string
			$pq['members'].=") AND projectId IN (SELECT projectId FROM members WHERE userId = :userId)";
		}else{
			$pq['members']="SELECT projectId FROM members WHERE userId IN (SELECT userId FROM users WHERE username in () AND projectId IN (SELECT projectId FROM members WHERE userId = :userId)";
		}

		/**
		 * (SELECT * FROM projects WHERE projectId IN (SELECT projectId FROM members WHERE userId = :currentUser) AND (projects.title IN ("proj1", "proj2")))
		 */
		if(isset($query['projects'])){
			$projects=explode(',', $_GET['projects']);
			$pq['projects']="SELECT todoListId FROM projects WHERE projectId IN (SELECT projectId FROM members WHERE userId = :userId) AND (projects.title IN (";
			$i = 0;
			foreach($projects as $p){
				$pq['projects'].=":project{$i}, ";
				$params["project{$i}"] = $p;
				
				$i++;
			}
			$pq['projects']=substr($pq['projects'], 0, -1*strlen(", ")); // remove ', 'from end of string
			$pq['projects'].="))";
		}else{
			$pq['projects']="SELECT todoListId FROM projects WHERE projectId IN (SELECT projectId FROM members WHERE userId = :userId) AND (projects.title IN ())";
			
		}

		/** 	(SELECT todoListId FROM todolists WHERE (
        *			(tags LIKE "rcom" OR tags LIKE "rcom,%" OR tags LIKE "%,rcom,%" OR tags LIKE "%,rcom") 
  		*	 	OR
        *			(tags LIKE "ltw" OR tags LIKE "ltw,%" OR tags LIKE "%,ltw,%" OR tags LIKE "%,ltw") 
		*		))
		*/
		if(isset($query['tags'])){
			//tag(s) are set.
			//pq stands for partial query
			$pq['tags']="SELECT todoListId FROM todolists WHERE (";
			$tags=explode(',', $_GET['tags']);
			$i=0;
			foreach($tags as $t){
				$pq['tags'].="(tags LIKE :tag{$i}0 OR tags LIKE :tag{$i}1 OR tags LIKE :tag{$i}2 OR tags LIKE :tag{$i}3) OR ";
				$params["tag{$i}0"]="{$t}";
				$params["tag{$i}1"]="{$t},%";
				$params["tag{$i}2"]="%,{$t},%";
				$params["tag{$i}3"]="{$t},%";

				$i++;
			}
			$pq['tags']=substr($pq['tags'], 0, -1*strlen(" OR ")); // remove " OR " from end of string
			$pq['tags'].=")";
		}else{
			$pq['tags']="SELECT todoListId FROM todolists WHERE 1"; //verificar esse 1
		}


		/**
		 * (REPLACE_ME LIKE "%search1%" OR REPLACE_ME LIKE "%search2%"))
		 * 
		 */

		 if(count($words)>0){
			 $pq['words']="(REPLACE_ME LIKE ";
			 $i = 0;
			 foreach($words as $w){
				$pq['words'].=":word{$i} OR REPLACE_ME LIKE ";
				$params["word{$i}"]=$w;

				$i++;
			 }
			 $pq["words"]=substr($pq["words"], 0, -1*strlen(" OR REPLACE_ME LIKE ")); // remove " OR REPLACE_ME LIKE " from end of string
			 $pq['words'].=')';
		 }else{
			$pq['words']="1";
		 }
		
		var_dump($pq);
		var_dump($params);


		/** |--------------------------------------WHOLE QUERY-----------------------------------|
		 * 	|																					 |
		 * 	|-------------------1st sq--------------------|---2nd sq---|---3rd sq---|---4th sq---|
		 *  |																					 |
		 *  |----1.1 sq----|----1.2 sq----|----1.3 sq---- |										 |
		 * 
		 */

		 $pq['sq11']=$pq['members'];
		 $pq['sq12']=$pq['projects'];
		 $pq['sq13']="SELECT projectId FROM projects WHERE ".str_replace("REPLACE_ME", "title", $pq['words'])." OR ".str_replace("REPLACE_ME", "description", $pq['words']);
		 
		 $pq['sq1']="SELECT todoListId FROM todolists WHERE projectId IN(".$pq['sq11']." UNION ".$pq['sq12']. " UNION " . $pq['sq13']. "))";
		 
		 $pq['sq2']=$pq['tags'];

		 $pq['sq3']="SELECT todoListId FROM todolists WHERE " . str_replace("REPLACE_ME", "title", $pq['words']);

		 $pq['sq4']="SELECT todoListId FROM items WHERE " . str_replace("REPLACE_ME", "content", $pq['words']);

		 $fullWhere="todoListId IN (";
		 $fullWhere.=$pq['sq1']. " UNION ". $pq['sq2']. " UNION ". $pq['sq3']. " UNION ". $pq['sq4']. ")";

		 ini_set('xdebug.var_display_max_depth', 5);
		 ini_set('xdebug.var_display_max_children', 256);
		 ini_set('xdebug.var_display_max_data', 1024);
		 var_dump($pq);
		 var_dump($fullWhere);
		return $qb->select()->where($fullWhere)->addParams($params);
	}

	//all the lists this user can see, query is an array of key values with the possible search values
	//example ["tags"=>array("harcore", "tag1"), "search" => "aquela nota", "users" => array("maps", "dannyps")]
	public static function getAllQuery($query, $userId, $loadItemsAsWell = true){

		$qb = new QueryBuilder(self::class);

		if(self::isSearchQuery($query)){
			//special build here
			$searchQuery=self::buildSearchQuery($query, $userId, $qb);
			$lines = $searchQuery->getAll();
		}else{
			$lines = $qb->select()->where("userId = :userId OR (projectId IN (SELECT m.projectId FROM members as m where userId = :userId))")->addParam("userId", $userId)->getAll();
		}
		//TODO: maybe load project information and user

		return self::loadTodoFromDatabase($lines, $loadItemsAsWell);
	}

 	//all the lists this user can see
	public static function getAllByUser($userId, $loadItemsAsWell = true){
		$qb = new QueryBuilder(self::class);
	//TODO: maybe load project information
		$lines = $qb->select()->where("userId = :userId OR (projectId IN (SELECT m.projectId FROM members as m where userId = :userId))")->addParam("userId", $userId)->getAll();
		return self::loadTodoFromDatabase($lines, $loadItemsAsWell);
	}
/*
	//all the lists that are not in a project
	public static function getAllByUserPrivate($userId, $loadItemsAsWell = true){
		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("userId = :userId AND projectId = 0")->addParam("userId", $userId)->getAll();
		return self::loadTodoFromDatabase($lines, $loadItemsAsWell);
	}
*/
	//all the lists that are in a project
	public static function getAllByProject($projectId, $loadItemsAsWell = true){
		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("projectId = :projectId")->addParam("projectId", $projectId)->getAll();
		return self::loadTodoFromDatabase($lines, $loadItemsAsWell);
	}

	private static function loadTodoFromDatabase($lines, $loadItemsAsWell = true){
		if ($loadItemsAsWell) {
			//get all the ids
			$ids = array_unique(array_map(function($line){return $line["todoListId"];}, $lines));
			//get all the items for all the lists
			$items = Item::getAllForLists($ids);
		}
		$todos = array();//an array of TodoList
		foreach ($lines as $line) {
			$todo = new TodoList();
			$todo->loadFromArray($line);
			//load respective items
			foreach ($items as $item)
				if($item->todoListId == $todo->todoListId)
					$todo->items[] = $item;
			$todos[] = $todo;
		}
		return $todos;
	}


	/**
	 * Returns true if the supplied user has access to this list, wither it's his or it belongs to a project of which he is a member
	 */
	public function verifyOwnership($userId){
		if($this->userId == $userId){//the user is the owner
			return true;
		}
		//check if the user has permission for the list
		return $this->select()->where("todoListId = :todoListId AND projectId IN (SELECT m.projectId FROM members as m where userId = :userId)")->addParam("userId", $userId)->get() !== false;
	}

	public function getTags(){
		return array_filter(explode(",", trim($this->tags)));
	}
}
