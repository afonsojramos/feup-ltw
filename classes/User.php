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
		"username"=>"length:1:30",
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

}
