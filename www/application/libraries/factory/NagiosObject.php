<?php

abstract class NagiosObject extends StdClass
{

	protected $_type = null;

	protected static $_count;

	public $id = null;

	protected $_namefield;

	public $name;


	function __construct($properties = array(),$strict = false ) {

		if($strict) {

			foreach($properties as $key => $value) {

				if(property_exists($this,$key) ) {
					$this->$key = $value; 
				}
			}

		} else {
			foreach($properties as $key => $value) {
				if(!empty($key)){
					$this->$key = $value;
				}	
			}	
		}

		//assign a unique ID for this object type
		static::$_count++;
		$this->id = static::$_count;

		//echo get_class($this)." ID: ".$this->id."<br />";

		//set the name if we can
		$this->_set_namefield(); 

		if(!empty($properties[$this->get_namefield()])){
			$this->name = $properties[$this->get_namefield()];
		}	

	}

	/**
	 * Returns all model properties as an array
	 * @return array 
	 */
	public function to_array() {

		$properties = array();

		foreach(get_class_vars(get_class($this)) as $prop => $value ){
			$properties[$prop] = $value; 
		}	

		return $properties; 
	}

	/**
	 * Returns new NagiosObject based on the object type 
	 * Handles autoloading of the object 
	 * @param  string $objectType valid Nagios Object type
	 * @param  array $properties
	 * @return NagiosObject	NagiosObject of type $objectType 
	 */
	public static function factory($objectType,$properties = array()){

		$classname = ucfirst($objectType);

		$path = dirname(__FILE__).'/objects/'.$classname.'.php';

		if(!class_exists($classname)){
			throw new Exception('Cannot create class: '.$classname.', class is not a valid nagios object');
		}

		return new $classname($properties);
	}


	public function get_count(){
		return self::$_count;
	}


	public function get_namefield(){
		return $this->_namefield;
	}


	protected function _set_namefield(){
		$this->_namefield = $this->_type.'_name';
	}


	public function get_type(){
		return $this->_type;
	}


}