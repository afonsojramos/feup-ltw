<?php

	require_once(dirname(__FILE__)."/../database/connection.php");

	/**
	 * SELECT (*) FROM table
	 * SELECT (*) FROM table WHERE (id = :id AND something = :something)
	 * SELECT (*) FROM table ORDER BY (id DESC, name ASC)
	 * SELECT (*) FROM table WHERE (...) ORDER BY (...,...)
	 * INSERT INTO (column1, column2, column3, ...) table values (:value1, :value2, :value3)
	 * UPDATE table SET column1 = :value1, column2 = :value2 WHERE id = :id
	 */
	/**
	 * In the class, the id must be the first public parameter
	 * Select:
	 * 		*
	 * 		custom
	 * WHERE
	 * 		none
	 * 		id
	 * 		custom
	 * ORDER BY
	 * 		custom
	 * INSERT
	 * 		all but id, returning id
	 * 		all, in case there is no id or is not autoincrement
	 * 		(custom)
	 * UPDATE
	 * 		all but id, identify by id
	 * 		custom, identify by id
	 * 		custom -> (overall)
	 * LIMIT
	 * 		value
	 * Overall
	 * 		executeQuery passed (checks for :values and binds them)
	 *
	 */
	class QueryBuilder	{
		protected $object;
		protected $parameters = array();//array of key-values to use for the binds, if needed
		//query creation attributes
		protected $select = false;
		protected $orderBy = false;
		protected $limit = false;

		protected $update = false;
		protected $insert = false;

		protected $where = false;

		/**
		 * Object is a class instance or a Class:class
		 */
		function __construct($object, $table = ""){
			$this->loadObject($object);
			$this->loadTableName($table);//loads the table name
			$this->setKey();//load the object's id, by default it's the first parameter on the class
			$this->debug();
			return $this;
		}

		public function debug(){
			//echo "object: (".var_dump($this->object).")<br>";
			echo "columns: (".var_dump($this->columns).")<br>";
			echo "parameters: (".var_dump($this->parameters).")<br>";
			echo "keys: (".var_dump($this->keys).")<br>";
		}

		public function addParams($params){
			if(is_array($params)){
				foreach ($params as $param) {
					$this->addParam($param);
				}
			}else{
				$this->addParam($param);
			}
		}
		/**
		 * @param where
		 * if this function is not called, no WHERE is added
		 * if a string is passed as a parameter that string will be used as the where condition (extra parameters must be given)
		 * if a number is given, that number will be used if there is only one primary key set
		 * if it is called with no parameters (or none of the above) then the where is cancelled
		 *
		 */
		public function where($where = null){
			if(is_string($where)){//custom where
				$this->where["value"] = $where;
			}elseif(is_numeric($where)){
				$this->where = "WHERE " . $this->getWhereKeys($where);
			}else{//clears the where clause
				$this->where = false;
			}
			return $this;
		}

		public function get(){

			echo $this->getWhereKeys();
			return $this;
		}

		public function getAll(){

			return $this;
		}


		/**
		 * pass a string with "column1, column2 as c2, ..."
		 */
		public function select($what = "*"){
			$query = "SELETCT " . $what . " FROM " . $this->table;
		}



		/**
		 * If the first parameter of the class is not the id then pass:
		 * 1. an array (empty or not) of the id's
		 * or
		 * 2. just the id column name
		 * default is the first parameter
		 */
		public function setKey($keys = null){
			if(is_array($keys)){
				foreach ($keys as $value) {
					$value = ":$value";
				}
				$this->keys = $keys;
			}elseif(is_string($keys)){
				$this->keys = array(":$keys");
			}elseif(isset($this->columns[0])){
				$this->keys = array(":".$this->columns[0]);
			}else{
				$this->keys = false;
			}
		}
//-------------------------Private functions

		//adds a parameter to the $this->parameters
		private function addParam($param){
			if(count($param) == 2){
				$this->parameters[$param[0]] = $param[1];
			} else{
				return $this;
			}
		}

		//try to load the supplied object into the $this->object and load the $this->columns attributes
		private function loadObject($object){
			if(is_object($object)){//class instance
				$this->columns = array_keys(get_object_vars($object));
				foreach ($this->columns as $key) {//load the instance values into $this->parameters
					$this->parameters[$key] = $object->$key;
				}
			}elseif(class_exists($object)){//class
				$this->columns = array_keys(get_class_vars($object));
			}else{
				throw new Exception("QueryBuilder: first parameter must be an object instance or a ClassName::class");
			}
			$this->object = $object;
		}

		//if a valid table is given load its name, else get the name from the class name
		private function loadTableName($table){
			if(count($table) < 1){
				$table = strtolower(get_class($this->object))."s";
			}
			$this->table = $table;
		}

		//return something like "id1 = :id1 AND id2 = :id2" from $this->keys
		private function getWhereKeys(){
			$whereKeys = "1";
			if($this->keys){
				$whereKeys = array();
				foreach ($this->keys as $key) {
					$whereKeys[] = "$key = :$key";
				}
				$whereKeys = implode(" AND ", $whereKeys);
			}
			return $whereKeys;
		}
/* 		private function clear(){
			$this->query = "";
			return $this;
		} */

	}
