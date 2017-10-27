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
		protected $parameters;//array of key-values to use for the binds, if needed
		protected $keys;
		//query creation attributes
		protected $select;
		protected $orderBy;
		protected $limit;
		protected $offset;
		protected $insert;
		protected $where;

		/**
		 * Object is a class instance or a Class:class
		 */
		function __construct($object, $table = ""){
			$this->object = $object;
			$this->table = $table;
			$this->clear();//initialize the variables
			//$this->debug();
			return $this;
		}

		static public function execute($query, $parameters = array()){
			global $connection;

			echo "<br>$query<br>";

			//get all the values to bind
			$regexBindings = '/:([^\s,]*)/m';
			preg_match_all($regexBindings, $query, $matches, PREG_SET_ORDER, 0);

			//check if all the values to bind are matched by a parenthesis
			$uniqueMatches = self::assertQueryBindings($query, $parameters);//fails if any parameter is missing
			//PDO query building and execution
			try {
				$stmt = $connection->prepare($query);
				foreach ($uniqueMatches as $key => $value) {
					echo "$key=>$value<br/>";
					$stmt->bindParam($key, $value);
				}
				$stmt->execute();
				if($stmt){
					return $stmt;
				}
			} catch (PDOException $e) {
				echo "QueryBuilder PDO error: ". $e->getMessage();
			}
			return false;
		}


		public function debug(){
			//echo "object: (".var_dump($this->object).")<br>";
			echo "columns: (".var_dump($this->columns).")<br>";
			echo "parameters: (".var_dump($this->parameters).")<br>";
			echo "keys: (".var_dump($this->keys).")<br>";
		}

		/**
		 * no parameter -> SELECT *
		 * pass a string with "column1, column2 as c2, ..."
		 */
		public function select($what = "*"){
			if(is_string($what)){
				$this->select = "SELECT " . $what . " FROM " . $this->table;
			}else{
				$this->select = false;
			}
			return $this;
		}

		/**
		 * no parameter -> UPDATE all the values, except the keys
		 * pass a string with "column1 = :column2, column2 as c2, ..."
		 * pass an array of key=>values
		 * automatically updates, return query
		 */
		public function update($what = null, $parameters = array()){
			if($what == null){//update everything
				$what = $this->getUpdateColumns();
			}
			$this->where(true);
			$query = "UPDATE " . $this->table . " SET " . $what . $this->where;

			$this->addParams($parameters);
			return self::execute($query, $this->parameters);
		}

		/**
		 * @param where
		 * if this function is not called, no WHERE is added for Class but the object has the default where
		 * if a string is passed as a parameter that string will be used as the where condition (extra parameters must be given), it is added after "WHERE "
		 * if it is called with no parameters (or none of the above) then the where is cancelled
		 * if it is true then the default key values will be used (for class, parameters must be passed before or at execute/update)
		 */
		public function where($where = false){
			if(is_string($where)){//custom where
				$this->where = " WHERE " . $where;
			}elseif(is_numeric($where) && $this->keys && count($this->keys) == 1){//if this is a number and there is only one primary key, use this as the where condition
				$this->addParam($this->keys[0], $where);//update the parameter
				$this->where = " WHERE " . $this->keys[0] . " = :" . $this->keys[0];
			}elseif(is_object($this->object) || $where === true){//if this is an object, use the default id
				$this->where = " WHERE " . $this->getWhereKeys();
			}else{//clears the where clause
				$this->where = false;
			}
			return $this;
		}

		/**
		 * add orderBy clause
		 * no parameters -> removes orderBy
		 * parameter decide the new order: ex: "dateUpdated DESC, score ASC"
		 */
		public function orderBy($order = ""){
			if(is_string($order) && strlen($order) > 0){
				$this->orderBy = " ORDER BY " . $order;
			}else{
				$this->orderBy = false;
			}
			return $this;
		}

		/**
		 * add the LIMIT clause to the query
		 * no parameter -> removes $limit
		 * $limit is numeric => create new limit for get_all
		 */
		public function limit($limit = ""){
			if(is_numeric($limit)){
				$this->limit = " LIMIT :limit";
				$this->addParam("limit", $limit);
			}else{
				$this->limit = false;
			}
			return $this;
		}

		/**
		 * add the OFFSET clause to the query
		 * no parameter -> removes $offset
		 * $offset is numeric => create new offset for get_all
		 */
		public function offset($offset = ""){
			if(is_numeric($offset)){
				$this->offset = " OFFSET :offset";
				$this->addParam("offset", $offset);
			}else{
				$this->offset = false;
			}
			return $this;
		}

		public function get($parameters = array()){
			if($this->select && is_string($this->select)){//case a select is the operation
				$query = $this->appendTry($this->select, $this->where);
				$query = $this->appendTry($query, $this->orderBy);
				$query = $this->appendTry($query, $this->limit);
				$query = $this->appendTry($query, $this->offset);
				$this->addParams($parameters);//adds the last passed parameters before executing
			}

			if($result = self::execute($query, $this->parameters)){
				return $result->fetch(PDO::FETCH_ASSOC);
			}
			return false;
		}

		public function load($ids = null){
			$this->clear();
			if(!is_object($this->object)){//if this a class instance
				if(is_numeric($ids) && count($this->keys) == 1){//single id
					$this->addParam($this->keys[0], $ids);
				}elseif(is_array($ids) && count($ids) == count($this->keys)){//primary key has many ids
					$this->addParams($ids);
				}else{
					throw new Exception("QueryBuilder: load function should receive a number, if the id is just one column or an array of keyvalues", 1);
				}
			}
			return $this->select()->where(true)->limit(1)->get();
		}

		public function getAll(){

			return $this;
		}

		/**
		 * resets all the changes made after the constructor is called
		 * loads the object parameters
		 * loads the table name (this is not recycled)
		 */
		public function clear($table = ""){
			$this->parameters = array();//array of key-values to use for the binds, if needed
			$this->keys = array();//array of key-values to use for the binds, if needed

			$this->loadObject($this->object);//load the parameters from scratch
			$this->setTable($table);//loads the table name
			//empty query creation variables
			$this->select = false;
			$this->orderBy = false;
			$this->limit = false;
			$this->offset = false;
			$this->insert = false;
			$this->where = false;
			$this->setKey();//load the object's id, by default it's the first parameter on the class
			return $this;
		}

		/**
		 * If the first parameter of the class is not the id then pass:
		 * 1. an array (empty or not) of the id's
		 * or
		 * 2. just the id column name
		 * default is the first parameter
		 */
		public function setKey($keys = null){
			if(is_array($keys)){//multiple primary keys
				$this->keys = $keys;
			}elseif(is_string($keys)){//single primary key
				$this->keys = array("$keys");
			}elseif(isset($this->columns[0])){//default is the first property of the class
				$this->keys = array($this->columns[0]);
			}else{//else no primary key is given
				$this->keys = false;
			}
		}

		/**
		 * if a valid table is given -> load its name, else derive the name from the class name
		 * an array of table names is also accepted
		 */
		public function setTable($table){
			if(is_array($table)){
				$table = implode(", ", $table);
			}elseif(is_string($table)){//if a string is passed use that (default is "")
				if(strlen($table) < 1){//if empty string load the expected table name
					//get the classname from either Class::class or object (class instance)
					$className = is_string($this->object)?$this->object:get_class($this->object);
					//convert the first letter to lower case as per the table naming system
					$table = strtolower(substr($className, 0, 1). substr($className, 1))."s";
				}
			}else{
				throw new Exception("QueryBuilder: table name must be a string or an array of strings", 1);
			}

			$this->table = $table;
			return $this;
		}

		/**
		 * Adds one or more parameters to $this->parameters, so they are used when binding key values in the query
		 */
		public function addParams($parameters){
			if(is_array($parameters)){
				foreach ($parameters as $key=>$value) {
					$this->addParam($key, $value);
				}
			}
			return $this;
		}

		//-------------------------Private functions

		//adds a parameter to the $this->parameters
		private function addParam($param, $value){
			if(is_string($param)){
				$this->parameters[$param] = $value;
			} else {
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

		//return something like "column1 = :column1, column2 = :column2" from $this->columns, ignore the keys
		private function getUpdateColumns(){
			$whereColumns = "1";
			if($this->columns){
				$whereColumns = array();
				foreach ($this->columns as $column) {
					if(!in_array($column, $this->keys)){
						$whereColumns[] = "$column = :$column";
					}
				}
				$whereColumns = implode(", ", $whereColumns);
			}
			return $whereColumns;
		}

		//append a string to another if the second is a string
		private function appendTry($str1, $str2){
			if(!is_string($str2)){
				return $str1;//original if str2 invalid
			}
			return $str1.$str2;//concatenation if str valid
		}

		//fails if any parameter is missing
		static private function assertQueryBindings($query, $parameters){
			//get all the values to bind
			$regexBindings = '/:([^\s,]*)/m';
			preg_match_all($regexBindings, $query, $matches, PREG_SET_ORDER, 0);

			//check if all the values to bind are matched by a parenthesis
			$uniqueMatches = array();
			foreach ($matches as $match) {//the index 1 contains the variable name to search, index 0 to bind
				if(!isset($parameters[$match[1]])){
					throw new Exception("QueryBuilder: Cannot execute query ($query) without all parameters, stopped at missing parameter: '" . $match[1] . "'", 1);
				}
				if (!in_array($match[0], $uniqueMatches)){//if unique not yet save key => value
					$uniqueMatches[$match[0]] = $parameters[$match[1]];
				}
			}

			return $uniqueMatches;
		}
	}
