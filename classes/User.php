<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
class User extends QueryBuilder{
	//properties must be protected, but relax QueryBuilder allows them to be accessed as if they were public
	//if they are public, update will have nothing to update when you do $user->name = "newName"
	public $userId;//attributes that do not require update can be public
	protected $username;
	protected $email;
	protected $password;
	//protected $gender;//this property can be dynamically created and it will be updated, by doing $user->gender = "male"; $user->update();
	//public static $primaryKeys = array("userId", "questionId");//define this static variable for custom primary keys or see QueryBuilder->setKey();
	//static properties are ignored
	public static $validationRules = array(//validation rules for update method
		"username"=>"length:1:30|no:@",
		"email"=>"email",
		"password"=>"length:8:100"
	);

	public function __construct($userId = null, $username = "", $email = ""){
		$this->userId = $userId;
		$this->username = $username;
		$this->email = $email;
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}

	/**
	 * Hash the current password, to be called befor insert and such
	 */
	public function hashPassword(){
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
	}

	/**
	 * Test if an account with a given username already exists
	 */
	function duplicateUsername(){
		return $this->select()->where("username = :username")->get() != false;
	}

	/**
	 * Test if a username can be changed
	 */
	function duplicateUsernameOnEditProfile(){
		return $this->select()->where("username = :username and userId!=:userId")->get() != false;
	}

	/**
	 * Test if an account with a given email already exists
	 */
	function duplicateEmail(){
		return $this->select()->where("email = :email")->get() != false;
	}

	/**
	 * Test if an email can be changed
	 */
	function duplicateEmailOnEditProfile(){
		return $this->select()->where("email = :email and userId!=:userId")->get() != false;
	}

	/**
	 * Login a user given an array with at least "username" and "password" fields (tests if username is not the email address)
	 */
	public function login($params){
		if($this->loadFromUsernameOrEmail($params["username"])){
			if(password_verify($params["password"], $line["password"])){
				//session creation
				ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);  // 7 day cookie lifetime
				sessionStart();
				session_regenerate_id(true);
				$_SESSION['userId'] = $line["userId"];
				$_SESSION['username'] = $line["username"];
				return true;
			}
		}
		return false;
	}


	/**
	 * Load a user given an array with at least "username" filed (tests if username is not the email address)
	 */
	public function loadFromUsernameOrEmail($username){
		$key = "username";
		$value = strtolower($username);
		if(strpos($value, "@") !== false){//this is actually an email
			$key = "email";
		}

		$this->select()->where("$key = :$key");
		$this->$key = $value;
		if ($line = $this->get()){
			$this->loadFromArray($line);
			return true;
		}
		return false;
	}

	/**
	 * Get all users for a given project
	*/
	public static function getAllByProject($projectId){
		$qb = new QueryBuilder(self::class);
		$lines = $qb->select()->where("userId in (SELECT userId FROM members WHERE projectId = :projectId)")->addParam("projectId", $projectId)->getAll();
		$users = array();
		foreach ($lines as $line) {
			$p = new User;
			$p->loadFromArray($line);
			$users[] = $p;
		}
		return $users;
	}
}
