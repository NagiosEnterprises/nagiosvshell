<?php

abstract class NagiosObject extends StdClass
{

	private $_type = null;

	protected static $_count;

	public $id = null;



	function __construct($properties = array(),$strict = false ) {

		if($strict) {

			foreach($properties as $key => $value) {

				if(property_exists($this,$key) ) {
					$this->$key = $value; 
				}
			}

		} else {
			foreach($properties as $key => $value) {
				$this->$key = $value;
			}	
		}

		//assign a unique ID for this object type
		static::$_count++;
		$this->id = static::$_count;

		//echo get_class($this)." ID: ".$this->id."<br />";



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

		if(!class_exists($classname) && file_exists($path)){
			include_once($path);
		}

		if(!class_exists($classname)){
			throw new Exception('Cannot create class: '.$classname.', class is not a valid nagios object');
		}

		return new $classname($properties);
	}


	public function get_count(){
		return self::$_count;
	}


	public function get_name(){
		$namefield = $this->_type.'_name';
		return $this->$namefield;
	}



}