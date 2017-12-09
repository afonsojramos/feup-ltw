<?php
require_once(dirname(__FILE__) . "/QueryBuilder.php");
require_once(dirname(__FILE__) . "/Item.php");
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
		"title" => "length:3:200",
		"colour" => "in:white:red:orange:yellow:green:teal:blue:purple:pink:brown"
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
		if ($name == "tags") {//REGEX to remove all whitespaces from the tags
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
		foreach (array('members', 'tags', 'projects', 'words', 'expressions') as $p) {
			if (array_key_exists($p, $query)) {
				return true;
			}
		}
		return false;
	}

	private static function buildSearchQuery($query, $userId, $qb) {
		$params = array();
		$params["userId"] = $userId;
		$pq = array();

		//merge words and expressions into $words
		if (isset($query["words"])) {
			$words = explode(',', $query["words"]);
		} else {
			$words = array();
		}
		if (isset($query["expressions"])) {
			$expressions = explode(',', $query["expressions"]);
		} else {
			$expressions = array();
		}
		$words = array_merge($words, $expressions);


		$masterQuery = array();
		/* SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (
			SELECT projectId FROM members WHERE userId IN (SELECT userId FROM users WHERE username IN ("msramalho", "dannyps"))
		) */
		if (isset($query["members"])) {
			$members = explode(',', $query["members"]);
			$subquery = "SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM members WHERE userId IN (SELECT userId FROM users WHERE username IN (";
			$i = 0;
			$temp = array();
			foreach ($members as $m) {
				$temp[] = ":username{$i}";
				$params["username{$i}"] = $m;
				$i++;
			}
			$subquery .= implode(", ", $temp);
			$subquery .= ")))";
			$masterQuery[] = $subquery;
		}


		/*
		SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM projects WHERE title IN ("rcom","ltw")
		*/
		if (isset($query["projects"])) {
			$projects = explode(',', $query["projects"]);
			$subquery = "SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM projects WHERE title IN (";

			$i = 0;
			$temp = array();
			foreach ($projects as $p) {
				$temp[] = ":project{$i}";
				$params["project{$i}"] = $p;
				$i++;
			}
			$subquery .= implode(", ", $temp);

			$subquery .= "))";
			$masterQuery[] = $subquery;
		}


		/*
		SELECT DISTINCT todoListId FROM todolists WHERE (
			(tags LIKE "rcom" OR tags LIKE "rcom,%" OR tags LIKE "%,rcom,%" OR tags LIKE "%,rcom")
			OR
			(tags LIKE "ltw" OR tags LIKE "ltw,%" OR tags LIKE "%,ltw,%" OR tags LIKE "%,ltw")
		)
		*/
		if (isset($query["tags"])) {
			//tag(s) are set.
			//pq stands for partial query
			$subquery = "SELECT DISTINCT todoListId FROM todolists WHERE (";
			$tags = explode(',', $query["tags"]);

			$i = 0;
			$temp = array();
			foreach ($tags as $t) {
				$temp[] = "tags LIKE :tag{$i}0 OR tags LIKE :tag{$i}1 OR tags LIKE :tag{$i}2 OR tags LIKE :tag{$i}3";
				$params["tag{$i}0"] = "{$t}";
				$params["tag{$i}1"] = "%,{$t}";
				$params["tag{$i}2"] = "%,{$t},%";
				$params["tag{$i}3"] = "{$t},%";
				$i++;
			}
			$subquery .= implode(" OR ", $temp);
			$subquery .= ")";
			$masterQuery[] = $subquery;
		}

		/*
		--title de list
		SELECT DISTINCT todoListId FROM todolists WHERE (title LIKE "%rcom%" OR title LIKE "%ltw%")
		--content of items
		UNION SELECT DISTINCT todoListId FROM items WHERE (content LIKE "%rcom%" OR content LIKE "%ltw%")
		--title or description of projects
		UNION SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM projects WHERE ((title LIKE "%rcom%" OR description LIKE "%rcom%") OR (title LIKE "%ltw%" OR description LIKE "%ltw%"))
		--tags
		UNION SELECT DISTINCT todoListId FROM todolists WHERE tags LIKE "%rcom%" OR tags LIKE "%ltw%"
		*/
		if (count($words) > 0) {
			//SELECT DISTINCT todoListId FROM todolists WHERE (title LIKE "%rcom%" OR title LIKE "%ltw%")
			$subquery = "SELECT DISTINCT todoListId FROM todolists WHERE (";

			$i = 0;
			$temp = array();
			foreach ($words as $w) {
				$temp[] = "title LIKE :word{$i}";
				$params["word{$i}"] = "%$w%";
				$i++;
			}
			$subquery .= implode(" OR ", $temp);

			//UNION SELECT DISTINCT todoListId FROM items WHERE (content LIKE "%rcom%" OR content LIKE "%ltw%")
			$subquery .= ") UNION SELECT DISTINCT todoListId FROM items WHERE (";

			$i = 0;
			$temp = array();
			foreach ($words as $w) {
				$temp[] = "content LIKE :word{$i}";
				$i++;
			}
			$subquery .= implode(" OR ", $temp);

			//UNION SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM projects WHERE ((title LIKE "%rcom%" OR description LIKE "%rcom%") OR (title LIKE "%ltw%" OR description LIKE "%ltw%"))
			$subquery .= ") UNION SELECT DISTINCT todoListId FROM todolists WHERE projectId IN (SELECT projectId FROM projects WHERE (";

			$i = 0;
			$temp = array();
			foreach ($words as $w) {
				$temp[] = "title LIKE :word{$i} OR description LIKE :word{$i}";
				$i++;
			}
			$subquery .= implode(" OR ", $temp);

			//UNION SELECT DISTINCT todoListId FROM todolists WHERE tags LIKE "%rcom%" OR tags LIKE "%ltw%"
			$subquery .= ")) UNION SELECT DISTINCT todoListId FROM todolists WHERE (";

			$i = 0;
			$temp = array();
			foreach ($words as $w) {
				$temp[] = "tags LIKE :word{$i}";
				$i++;
			}
			$subquery .= implode(" OR ", $temp);

			$subquery .= ")";
			$masterQuery[] = $subquery;
		}

		$gluedMasterQuery = implode(" INTERSECT ", $masterQuery);
		var_dump($masterQuery);
		// var_dump($params);

		$finalQuery = "SELECT * FROM todolists WHERE todoListId IN (
			$gluedMasterQuery
			INTERSECT
			SELECT DISTINCT todoListId FROM todolists WHERE userId = :userId OR (projectId IN (SELECT m.projectId FROM members as m where userId = :userId))
		)";
		echo $finalQuery;
		if($result = $qb->execute($finalQuery, $params)){
			return $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;
	}

	//all the lists this user can see, query is an array of key values with the possible search values
	//example ["tags"=>array("harcore", "tag1"), "search" => "aquela nota", "users" => array("maps", "dannyps")]
	public static function getAllQuery($query, $userId, $loadItemsAsWell = true){

		$qb = new QueryBuilder(self::class);

		if (self::isSearchQuery($query)) {
			//special build here
			$lines = self::buildSearchQuery($query, $userId, $qb);
		} else {
			$lines = $qb->select()->where("userId = :userId OR (projectId IN (SELECT m.projectId FROM members as m where userId = :userId))")->addParam("userId", $userId)->getAll();
		}

		return self::loadTodoFromDatabase($lines, $loadItemsAsWell);
	}

 	//all the lists this user can see
	public static function getAllByUser($userId, $loadItemsAsWell = true){
		$qb = new QueryBuilder(self::class);
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
			$ids = array_unique(array_map(function ($line) {
				return $line["todoListId"];
			}, $lines));
			//get all the items for all the lists
			$items = Item::getAllForLists($ids);
		}
		$todos = array();//an array of TodoList
		foreach ($lines as $line) {
			$todo = new TodoList();
			$todo->loadFromArray($line);
			//load respective items
			foreach ($items as $item)
				if ($item->todoListId == $todo->todoListId)
				$todo->items[] = $item;
			$todos[] = $todo;
		}
		return $todos;
	}


	/**
	 * Returns true if the supplied user has access to this list, wither it's his or it belongs to a project of which he is a member
	 */
	public function verifyOwnership($userId){
		if ($this->userId == $userId) {//the user is the owner
			return true;
		}
		//check if the user has permission for the list
		return $this->select()->where("todoListId = :todoListId AND projectId IN (SELECT m.projectId FROM members as m where userId = :userId)")->addParam("userId", $userId)->get() !== false;
	}

	public function getTags(){
		return array_filter(explode(",", trim($this->tags)));
	}
}
