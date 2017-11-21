<?php

abstract class Validator {//requires $this->class
	protected $errors;

	/**
	 * User static $validationRules to validate this classes properties and fill $this->errors
	 */
	public function validate($overideRules = null){
		if($overideRules==null){
			if(!isset($this->class)){
				throw new Exception('Cannot use Validator class without setting $this->class', 1);
			}

			$reflector = new ReflectionClass($this->class);
			$staticProperties = $reflector->getStaticProperties();

			if(!isset($staticProperties["validationRules"])){
				throw new Exception("Cannot validate a class without having static property 'validationRules'", 1);
			}
			$overideRules = $staticProperties["validationRules"];

		}
		$this->errors = array();

		foreach ($this as $key => $value) {
			if (isset($overideRules[$key])) {//if this is a property to validate
				$this->validateProperty($key, $value, $overideRules[$key]);
			}
		}
		return count($this->errors) == 0;//true if no errors
	}

	/**
	 * Adds an error to the errors array, if echo is true also displays the message inside <error></error> tags
	 */
	protected function addError($newError, $echo){
		$newError = htmlentities($newError);
		if($echo){
			echo "<error>Validation error: $newError</error><br/>";
		}
		$this->errors[]=$newError;
	}

	/**
	 * validate a variable according to a list of pipe separated rules
	 * @param rules can be: [integer, length:min:max, >:than]
	 * @param echo if true then errors are echoed
	 */
	protected function validateProperty($name, $value, $rules, $echo = false){
		$rules = explode("|", $rules);

		foreach ($rules as $rule) {//for each '|' pipe separated rule
			$parts = explode(":", $rule);//rules is a ':' separated string, eg. length:1:20
			$type = $parts[0];//validation type
			switch ($type) {
				case "integer"://integer
					if(!is_numeric($value)){
						$this->addError("$name expected an integer but got '$value'", $echo);
					}
					break;

				case ">"://greater than
					if($value <= intval($parts[1])){
						$this->addError("$name expected to be greater than " . $parts[1] . "  but got '$value'", $echo);
					}
					break;

				case "length"://length:minLen:maxLen
					//min length validation
					$minLen = $parts[1];
					$this->validateProperty("$name:internal:$type:minLen", $minLen, "integer|>:-1", true);
					//max length validation
					$maxLen = $parts[2];
					$this->validateProperty("$name:internal:$type:maxLen", $maxLen, "integer|>:$minLen", true);
					//works for strings and arrays
					if(is_string($value)){//string
						$len = strlen($value);
					}elseif (is_array($value)) {//array
						$len = count($value);
					}else{//not string and not array
						$this->addError("$name: expected a string or an array", $echo);
						break;
					}
					//validate min length
					if($len < $minLen){
						$this->addError("$name: minimum length is $minLen", $echo);
						break;
					}
					//validate max length
					if($len > $maxLen){
						$this->addError("$name: maxlength length is $maxLen", $echo);
						break;
					}

					break;
				case "no"://no:stringNotIn
					$notAllowed = $parts[1];
					if(strpos($value, $notAllowed) !== false){
						$this->addError("$name: cannot contain: '$notAllowed'", $echo);
					}
					break;
				case "in"://in:listItemAllowed1:listItemAllowed2:...
					$allowedValues = array_splice($parts, 1);
					if(!in_array($value, $allowedValues)){
						$imploded = implode("', '", $allowedValues);
						$this->addError("$name: must be on of : '$imploded'", $echo);
					}
					break;

				case "email":
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
						$this->addError("$name: invalid email address: '$value", $echo);
					}
					break;

				default://command not recognized
					throw new Exception("Unknown validation command: $type", 1);
					break;
			}
		}
	}

}
