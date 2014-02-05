<?php

class NagiosCollection extends Collection
{

	protected $_type = null;

	/**
	 * Mass instantiation for Nagios object and status collections. Also handles autoloading file includes 
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public static function factory($type) {

		$classname = ucfirst($type).'Collection';

		$path = dirname(__FILE__).'/collections/'.$classname.'.php';

		if(class_exists($classname)){
			$Object = new $classname();
			return $Object;
		} else {
			throw new Exception('Class: '.$classname.' does not exist in path: '.$path.'');
		}

	}

	public function get_type(){
		return $this->_type; 
	}


	public function get_by_id($id){
		return $this[$id];
	}

}