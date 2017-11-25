<?php

	require_once(dirname(__FILE__)."/../database/connection.php");
	require_once(dirname(__FILE__)."/Validator.php");

	/**
	 * The order of the Class's attributes is important
	 * . If no key is specified then the first attribute is used
	 * . The order of the attributes must match the ORDER of the database values (for the load function)
	 */
	class QueryBuilder extends Validator{
		protected $class;
		protected $parameters;//array of key-values to use for the binds, if needed
		protected $keys = array();//a list of the columns composing the primary key
		protected $columns = array();
		protected $toIgnore = array();
		protected $table = "";
		protected $isGeneric = false;
		protected $toUpdate = false;//list of properties changed since last update
		//query creation attributes
		protected $select;
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
			$this->loadToIgnore();//load the class columns
			$this->loadColumns();//load the class columns
			$this->setKey($keys);//set the default primary key value
			$this->setTable();//set the default table name
			$this->clear();//initialize the variables
			$this->toUpdate = $this->columns;
			//$this->debug();
			return $this;
		}

		//-----------------QUERY BUILDING FUNCTONS

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

		//-------------------QUERY EXECUTION FUNCTIONS

		/**
		 * Execute a query given it's binding parameters
		 * It is used internally, but can be invoked like a static method of QueryBuilder
		 * @param query the sql query to execute
		 * @param parameters an array of key=>value, where all the query binding variables are declared, without ":"
		 * @return false on failure, the PDO::statement on success after it is .executed()
		 */
		static public function execute($query, $parameters = array()){
			global $connection;
			//echo "<br>$query<br>";
			//var_dump($parameters);
			//PDO query building and execution
			try {
				$stmt = $connection->prepare($query);
				foreach ($parameters as $key => $value) {
					//echo "PREPARE: $key------>$value<br/>";
					$stmt->bindValue(":$key", $value);
				}
				$stmt->execute();
				return $stmt?$stmt:false;//return $stmt is successful, false otherwise
			} catch (PDOException $e) {
				echo "QueryBuilder PDO error: ". $e->getMessage();
			}
			return false;
		}

		/**
		 * execute a query and return the result
		 * @param $parameters the parameters that are required to execute the query
		 * @param fetchAll boolean, on true returns all matches, on false only the first
		 * @return the data when successful, false otherwise
		 */
		public function get($parameters = array(), $fetchAll = false){
			$this->addParams($parameters);//adds the last passed parameters before executing
			if($this->select){//case a select is the operation
				$query = $this->appendTry($this->select, $this->where);
				$query = $this->appendTry($query, $this->orderBy);
				$query = $this->appendTry($query, $this->limit);
				if($this->limit){//only add offset if limit is supplied
					$query = $this->appendTry($query, $this->offset);
				}
				if($result = self::execute($query, $this->getQueryParameters($query))){
					if($fetchAll){
						return $result->fetchAll(PDO::FETCH_ASSOC);
					}else{
						return $result->fetch(PDO::FETCH_ASSOC);
					}
				}
			}
			return false;
		}

		/**
		 * wrapper that calls QueryBuilder->get() with flag to get all set to true
		 * @see QueryBuilder::get
		 * @param $parameters the parameters that are required to execute the query
		 * @return the data when successful, false otherwise
		 */
		public function getAll($parameters = array()){
			return $this->get($parameters, true);
		}

		/**
		 * Load an object into this instance or return a new instance of the class if QueryBuilder is used directly
		 * @param ids
		 * 		1. a number if there is a single column in the primary key
		 * 		2. an array of $key=>$value where $key matches every key in the primary key
		 * 		3. an ordered array of values that match the multiple columns in the primary key
		 * 		4. nothing if this is an extended class results in loading from the current primaryKey
		 */
		public function load($ids = null){
			if(is_numeric($ids) && count($this->keys) == 1){//primary key has one column
				$this->addParam($this->keys[0], $ids);
			}elseif(is_array($ids) && count($ids) == count($this->keys)){//primary key has many ids
				try{
					for ($i=0; $i < count($ids); $i++) {//add all the keys to the parameters
						//add each key, assuming $ids is $key=>$value where the key matches
						$this->addParam($this->keys[$i], $ids[$this->keys[$i]]);
					}
				}catch(Exception $e){
					//load is assuming ordered list of keys
					for ($i=0; $i < count($ids); $i++) {//add all the keys to the parameters
						//add each key, assuming $ids is $value and the order is the same as $this->keys
						$this->addParam($this->keys[$i], $ids[$i]);
					}
				}
			}elseif($this->isGeneric){//if this a class and not an inheritance and has no id, fails
				throw new Exception('QueryBuilder: load function should receive a number, if the id is just one column; or an array of $key=>$value where $key matches every key in the primary key; or an ordered array of values that match the multiple columns in the primary key', 1);
			}
			//load the object
			if($objectLoad = $this->select()->where(true)->limit(1)->get()){
				$this->toUpdate = array();
				if($this->isGeneric){//return an object of the generic type, argument order must match
					$reflector = new ReflectionClass($this->class);
					return $reflector->newInstanceArgs(array_diff(array_values($objectLoad), $this->toIgnore));
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
		 * execute an INSERT query
		 * @param autoIncrement this decides whether the primary key is inserted or returned, only for single primary key, for multiple this is irrelevant as both are used
		 * @param columnsToInsert an array with the name of the columns to insert
		 * @return false if fails or reference to itself on success
		 */
		public function insert($autoIncrement = true, $columnsToInsert = null){
			global $connection;
			//create the query
			$query = "INSERT INTO " . $this->table . " ". $this->getInsertColumns($autoIncrement, $columnsToInsert) . " VALUES " . $this->getInsertValues($autoIncrement, $columnsToInsert);
			//execute the query
			if($result = self::execute($query, $this->getQueryParameters($query))){
				$this->toUpdate = false;
				if(count($this->keys) == 1 && $autoIncrement){
					$keyName = $this->keys[0];
					$this->$keyName = $connection->lastInsertId();
				}
				return $this;
			}
			return false;
		}

		/**
		 * Run an UPDATE query
		 * @param what
		 * 		1. default -> UPDATE all the values, except the keys
		 * 		2. pass a string with "column1 = :column2, column2 as c2, ..."
		 * @param where the arguments to call QueryBuilder->where() [optional, could have been called before]
		 * @return integer with the number of updated rows, false on query fail
		 */
		public function update($what = null, $where = null){
			$this->select = false;
			$what = ($what == null? $this->getUpdateColumns() : $what);//update from parameter or everything
			if($what == null){//there is nothing to update ->abort
				return 0;
			}
			//use the supplied query if valid, execute default if not
			$this->checkIfWhere($where);
			//create the query
			$query = "UPDATE " . $this->table . " SET " . $what . $this->where;
			//execute the query
			if($result = self::execute($query, $this->getQueryParameters($query))){
				$this->toUpdate = false;
				return $result->rowCount();
			}
			return false;
		}

		/**
		 * Run a DELETE query
		 * @param where the arguments to call QueryBuilder->where() [optional, could have been called before]
		 * @return integer with the number of updated rows, false on query fail
		 */
		public function delete($where = null){
			//use the supplied query if valid, execute default if not
			$this->checkIfWhere($where);
			//create the query
			$query = "DELETE FROM " . $this->table . $this->where;
			if($result = self::execute($query, $this->getQueryParameters($query))){
				return $result->rowCount();
			}
			return false;
		}

		//------------HELPER PUBLIC FUCNTIONS

		/**
		 * resets all the changes made after the constructor is called to the dynamic queries
		 */
		public function clear(){
			//empty query creation variables
			$this->parameters = array();
			$this->select = false;
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
			}elseif(is_numeric($keys) && count($this->columns) >= $keys){
				$this->keys = array_slice($this->columns, 0, $keys);
			}elseif(isset($this->class::$primaryKeys) && is_array($this->class::$primaryKeys)){//else if static primaryKeys is defined, those are the keys
				$this->keys = $this->class::$primaryKeys;
			}elseif(isset($this->columns[0])){//default is the first property of the class
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
		 * Load the value of the columns from a key=>value array
		 */
		public function loadFromArray($array){
			foreach ($array as $key => $value) {//iterate given array key=>values
				if(in_array($key, $this->columns)){//if this key belongs to the valid columns
					$this->__set($key, $value);//save it
				}
			}
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

		public function __toString(){//display query builder real content
			$str = "<br/>" . strtoupper($this->class);
			$str .= "<br/>Primary Key(s): ";
			foreach ($this->keys as $key => $value) {
				$str .= "($value => ".$this->$value.")";
			}
			$str .= "<br/>Properties: ";
			foreach ($this->columns as $key => $value) {
				$str .= "($value => ".$this->$value.")";
			}
			return $str;
		}
		//------------MAGICK METHODS (to improve the overall behaviour)

		public function __set($name, $value){
			$this->$name = $value;
			$this->toUpdate[] = $name;
			return $value;
		}

		public function __get($name){
			return (isset($this->$name)?$this->$name:null);
		}
		//------------HELPER PROTECTED FUCNTIONS

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

		//fill $this->columns from the valid columns to use (without $this->keys)
		protected function loadColumns(){
			$this->columns = array_diff(
				array_keys(get_class_vars($this->class)),
				$this->getIgnoreAttributes()
			);
		}

		//return an array of the columns of the inheriting class, keys + values
		protected function getAllColumns(){
			return array_merge($this->columns, $this->keys);
		}

		//get an array with the names of the attributes not to include in the queries
		protected function getIgnoreAttributes(){
			$reflector = new ReflectionClass($this->class);
			$staticProperties = array_keys($reflector->getStaticProperties());
			if($this->keys){
				return array_merge(
						array_keys(get_class_vars(QueryBuilder::class)),
						$this->keys,
						$staticProperties,
						$this->toIgnore);
			}else{
				return array_merge(
					array_keys(get_class_vars(QueryBuilder::class), $staticProperties),
					$this->toIgnore);
			}
		}

		//in case there are some static $ignoreProperties, load them into $this->toIgnore
		protected function loadToIgnore(){
			$this->toIgnore = array();
			if(isset($this->class::$ignoreProperties) && is_array($this->class::$ignoreProperties)){
				$this->toIgnore = $this->class::$ignoreProperties;
			}
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

		//get the column names of the columns to use in insert query
		protected function getInsertColumnNames($autoIncrement, $columnsToInsert){
			//decide which columns to insert
			if(!is_array($columnsToInsert) || count($columnsToInsert) == 0){
				$columnsToInsert = $this->columns;//ignoring primary keys
				if(!$autoIncrement || count($this->keys) > 1){//load the autoincrement
					$columnsToInsert = array_merge($columnsToInsert, $this->keys);
				}
			}
			return $columnsToInsert;
		}

		//get the first piece of INSERT like so: (column1, column2, column3, ...)
		protected function getInsertColumns($autoIncrement, $columnsToInsert){
			$columnsToInsert = $this->getInsertColumnNames($autoIncrement, $columnsToInsert);
			//create the query piece
			$insertColumns = "";
			if(count($columnsToInsert)>0){
				$insertColumns = "(".implode(", ", $columnsToInsert).")";
			}
			return $insertColumns;
		}

		//get the second piece of INSERT like so: (:value1, :value2, :value3)
		protected function getInsertValues($autoIncrement, $columnsToInsert){
			$columnsToInsert = $this->getInsertColumnNames($autoIncrement, $columnsToInsert);
			//create the query piece
			$insertValues = "";
			if(count($columnsToInsert)>0){
				$insertValues = array();
				foreach ($columnsToInsert as $column) {
					$insertValues[] = ":$column";
				}
				$insertValues = "(".implode(", ", $insertValues).")";
			}
			return $insertValues;
		}

		//get the names of the query parameters - the index 1 contains the variable name to search, index 0 to bind
		static protected function getQueryBindings($query){
			$regexBindings = '/:([^\s,\)\(]*)/m';
			preg_match_all($regexBindings, $query, $matches, PREG_SET_ORDER, 0);
			return $matches;
		}

		//return an array of $key=>$value for each parameter in the query, fails if any of them does not exist
		protected function getQueryParameters($query){
			$matches = QueryBuilder::getQueryBindings($query);
			$parameters = array();
			$doNotGivePriorityTo = array_keys(get_class_vars(QueryBuilder::class));
			foreach ($matches as $match) {
				$columnName = $match[1];
				//for each required parameter, try to find it's value
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

		//append a string to another if the second is a string
		protected function appendTry($str1, $str2){
			if(!is_string($str2)){
				return $str1;//original if str2 invalid
			}
			return $str1.$str2;//concatenation if str valid
		}

		//checks if the passed where is valid and calls it if so, otherwise executes the default
		protected function checkIfWhere($where){
			if($where){//if this where is set, use it
				$this->where($where);
			}
			if(!$this->where){//check if where condition is set, use default if not
				$this->where(true);//Default where uses the id
			}
		}


	}
