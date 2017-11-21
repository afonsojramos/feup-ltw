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

	public function __construct($userId = null, $username = "", $email = "", $password = ""){
		$this->userId = $userId;
		$this->username = $username;
		$this->email = $email;
		$this->password = $password;
		parent::__construct();//call parent constructor, necessary for QueryBuilder
	}

	public function insert($autoIncrement = true, $columnsToInsert = NULL){
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		return parent::insert($autoIncrement, $columnsToInsert);
	}

	/**
	 * Test if an account with a given username already exists
	 */
	function duplicateUsername(){
		return $this->select()->where("username = :username")->get() != false;
	}
	/**
	 * Test if an account with a given email already exists
	 */
	function duplicateEmail(){
		return $this->select()->where("email = :email")->get() != false;
	}

	/**
	 * Login a user given an array with at least "username" and "password" fields (tests if username is not the email address)
	 */
	public function login($params){
		$key = "username";
		$value = $params["username"];
		if(strpos($value, "@") !== false){//this is actually an email
			$key = "email";
		}

		$this->select()->where("$key = :$key");
		$this->$key = $value;

		if($line = $this->get()){
			if(password_verify($params["password"], $line["password"])){
				//session creation
				ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);  // 7 day cookie lifetime
				sessionStart();
				$_SESSION['userId'] = $line["userId"];
				$_SESSION['username'] = $line["username"];
				return true;
			}
		}
		return false;
	}
}