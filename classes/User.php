<?php

require_once(dirname(__FILE__)."/QueryBuilder.php");
class User extends QueryBuilder{
	//properties must be protected, but relax QueryBuilder allows them to be accessed as if they were public
	//if they are public, update will have nothing to update when you do $user->name = "newName"
	public $userId;//attributes that do not require update can be public
	protected $name;
	protected $age;
	//protected $gender;//this property can be dynamically created and it will be updated, by doing $user->gender = "male"; $user->update();
	//public static $primaryKeys = array("userId", "questionId");//define this static variable for custom primary keys or see QueryBuilder->setKey();
	public function __construct($userId = null, $name = "", $age = 0){
		$this->userId = $userId;
		$this->name = $name;
		$this->age = $age;
		parent::__construct();//call parent constructor
	}

    public function __toString(){//optional to string function
        return sprintf("<br>USER: userId:%d - name:%s - age:%d", $this->userId, $this->name, $this->age);
    }
}
