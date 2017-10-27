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
	/**
	 * The order of the Class's attributes is important
	 * . If no key is specified then the first attribute is used
	 * . The order of the attributes must match the ORDER of the database values (for the load function)
	 */
	class QueryBuilder	{
		protected $class;
		protected $parameters;//array of key-values to use for the binds, if needed
		protected $keys = array();//a list of the columns composing the primary key
		protected $columns = array();
		protected $table = "";
		protected $isGeneric = false;
		protected $toUpdate = false;//list of properties changed since last update
		//query creation attributes
		protected $select;
		protected $update;
		protected $orderBy;
		protected $limit;
		protected $offset;
		protected $insert;
		protected $where;

		/**
		 * @param class is a Class:class
		 * @param keys is a parameter that will be passed to ->setKey($keys);
		 */
		function __construct($class = null, $keys = null){
			$this->loadClass($class);//load the class
			$this->loadColumns();//load the class columns
			$this->setKey($keys);//set the default primary key value
			$this->setTable();//set the default table name
			$this->clear();//initialize the variables
			$this->toUpdate = $this->columns;
			//$this->debug();
			return $this;
		}

		static public function execute($query, $matches = array()){
			global $connection;
			//TODO: make accessible via User::class
			echo "<br>$query<br>";
			//PDO query building and execution
			try {
				$stmt = $connection->prepare($query);
				foreach ($matches as $key => $value) {
					$pdoType = PDO::PARAM_STR;
					if(is_bool($value) === true){
						$pdoType = PDO::BOOL;
					}elseif(is_numeric($value) === true){
						$pdoType = PDO::PARAM_INT;
					}
					$key = ":$key";
					echo "PREPARE: $key------>$value<br/>";
					$stmt->bindValue($key, $value, $pdoType);
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
		public function update($what = null){
			$this->select = false;
			if($what == null){//update everything
				$what = $this->getUpdateColumns();
				if($what == null){//there is nothing to update
					$update = false;
					return $this;
				}
			}
			$this->update = "UPDATE " . $this->table . " SET " . $what;
			return $this;
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
			}elseif($where === true){//if this is an object, use the default id
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
				unset($this->parameters["limit"]);
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
				unset($this->parameters["offset"]);
			}
			return $this;
		}

		public function get($parameters = array(), $fetchAll = false){
			$this->addParams($parameters);//adds the last passed parameters before executing
			if($this->select){//case a select is the operation
				$query = $this->appendTry($this->select, $this->where);
				$query = $this->appendTry($query, $this->orderBy);
				$query = $this->appendTry($query, $this->limit);
				$query = $this->appendTry($query, $this->offset);
				if($result = self::execute($query, $this->getQueryParameters($query))){
					if($fetchAll){
						return $result->fetchAll(PDO::FETCH_ASSOC);
					}else{
						return $result->fetch(PDO::FETCH_ASSOC);
					}
				}
			}elseif($this->update){
				if(!$this->where){
					$this->where(true);//Default where uses the id
				}
				$query = $this->update . $this->where;
				if($result = self::execute($query, $this->getQueryParameters($query))){
					$this->toUpdate = false;
					return $result->rowCount();
				}
			}

			return false;
		}

		public function getAll($parameters = array()){
			return $this->get($parameters, true);
		}

		public function load($ids = null){
			if(is_numeric($ids) && count($this->keys) == 1){//single id
				$this->addParam($this->keys[0], $ids);
				if(!$this->isGeneric){
					$key = $this->keys[0];
					$this->$key = $ids;
				}
			}elseif(is_array($ids) && count($ids) == count($this->keys)){//primary key has many ids
				for ($i=0; $i < count($ids); $i++) {//add all the keys to the parameters
					$this->addParam($this->keys[$i], $ids[$i]);
				}
			}elseif($this->isGeneric){//if this a class and not an inheritance
				throw new Exception("QueryBuilder: load function should receive a number, if the id is just one column or an array of ordered values that match the multiple columns in the primary key", 1);
			}
			if($objectLoad = $this->select()->where(true)->limit(1)->get()){
				$this->clear();//remove extra conditions
				$this->toUpdate = array();
				if($this->isGeneric){//return an object of the generic type, argument order must match
					$reflector = new ReflectionClass($this->class);
					return $reflector->newInstanceArgs(array_values($objectLoad));
				}
				$allColumns = $this->getAllColumns();//the columns that are keys and not
				foreach ($allColumns as $column) {
					$this->$column = $objectLoad[$column];
				}
				return $this;
			}
			return false;
		}

		/**
		 * resets all the changes made after the constructor is called to the dynamic queries
		 */
		public function clear(){
			//empty query creation variables
			$this->parameters = array();
			$this->select = false;
			$this->update = false;
			$this->orderBy = false;
			$this->limit();
			$this->offset();
			$this->insert = false;
			$this->where = false;
			return $this;
		}

		/**
		 * If the first parameter of the class is not the id then pass:
		 * 1. an array (empty or not) of the id's
		 * 2. just the id column name
		 * 3. if an integer is given then it is assumed the $keys first attributes constitute the primary key
		 * default is the first parameter
		 *
		 */
		public function setKey($keys = null){
			if(is_array($keys)){//multiple primary keys
				$this->keys = $keys;
			}elseif(is_string($keys)){//single primary key
				$this->keys = array("$keys");
			}elseif(is_numeric($keys) && count($this->columns) <= $keys){
				$this->keys = array_slice($this->columns, 0, $keys);
			}if(isset($this->columns[0])){//default is the first property of the class
				$this->keys = array($this->columns[0]);
			}else{//else no primary key is given
				$this->keys = false;
			}
			$this->loadColumns();//update columns by removing the keys
		}

		/**
		 * if a valid table is given -> load its name, else derive the name from the class name
		 * an array of table names is also accepted
		 */
		public function setTable($table = ""){
			if(is_array($table)){
				$table = implode(", ", $table);
			}elseif(is_string($table)){//if a string is passed use that (default is "")
				if(strlen($table) < 1){//if empty string load the expected table name
					$table = strtolower(substr($this->class, 0, 1). substr($this->class, 1))."s";
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

		//adds a parameter to the $this->parameters
		public function addParam($param, $value){
			if(is_string($param)){
				$this->parameters[$param] = $value;
			}
			return $this;
		}

		public function __set($name, $value){
			echo "Setting '$name' to '$value'\n";
			$this->$name = $value;
			$this->toUpdate[] = $name;
		}

		public function __get($name){
			if(isset($this->$name)){
				return $this->$name;
			}
		}
		//-------------------------Protected functions

		//get the class from this inherited instance or from the classname
		protected function loadClass($class){
			if(is_subclass_of($this, QueryBuilder::class)){
				$this->class = get_class($this);
			}elseif($class != null && class_exists($class)){
				$this->class = $class;
				$this->isGeneric = true;
			}else{
				throw new Exception("QueryBuilder: either inherit this class or pass a valid class as the first constructor parameter." );
			}
		}

		protected function loadColumns(){
			$this->columns = array_diff(
				array_keys(get_class_vars($this->class)),
				$this->getIgnoreAttributes()
			);
		}

		//return something like "id1 = :id1 AND id2 = :id2" from $this->keys
		protected function getWhereKeys(){
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

		protected function getAllColumns(){
			return array_merge($this->columns, $this->keys);
		}
		//return something like "column1 = :column1, column2 = :column2" from $this->columns, ignore the keys
		//only return the columns to update
		protected function getUpdateColumns(){
			$updateColumns = null;
			if($this->toUpdate){
				$updateColumns = array();
				foreach ($this->toUpdate as $column) {
					$updateColumns[] = "$column = :$column";
				}
				$updateColumns = implode(", ", $updateColumns);
			}
			return $updateColumns;
		}

		//append a string to another if the second is a string
		protected function appendTry($str1, $str2){
			if(!is_string($str2)){
				return $str1;//original if str2 invalid
			}
			return $str1.$str2;//concatenation if str valid
		}

		//get the names of the query parameters - the index 1 contains the variable name to search, index 0 to bind
		static protected function getQueryBindings($query){
			$regexBindings = '/:([^\s,]*)/m';
			preg_match_all($regexBindings, $query, $matches, PREG_SET_ORDER, 0);
			return $matches;
		}

		protected function getIgnoreAttributes(){
			if($this->keys){
				return array_merge(array_keys(get_class_vars(QueryBuilder::class)), $this->keys);
			}else{
				return array_keys(get_class_vars(QueryBuilder::class));
			}
		}

		//$this->getQueryParameters($query)
		protected function getQueryParameters($query){
			$matches = QueryBuilder::getQueryBindings($query);
			$parameters = array();
			$doNotGivePriorityTo = array_keys(get_class_vars(QueryBuilder::class));
			foreach ($matches as $match) {
				//for each required parameter, try to find it's value
				$columnName = $match[1];
				if(isset($this->$columnName) && !in_array($columnName, $doNotGivePriorityTo)){//use object parameter value if it is not a parameter of QueryBuidler
					$res = $this->$columnName;
				}elseif(isset($this->parameters[$columnName])){
					$res = $this->parameters[$columnName];
				}else{//abort if not found
					throw new Exception("QueryBuilder: Cannot execute query ($query) without all parameters, stopped at missing parameter: '" . $columnName . "'", 1);
				}
				$parameters[$columnName] = $res;
			}
			return $parameters;
		}


	}
