<?php

class Collection extends ArrayObject
{


	public $columns;

	private $_conditions = array('==','===','<','>','<=','>=','!=');

    protected $_index = array();



    function __construct($array = null){
        if(is_array($array)){
            foreach($array as $key=>$val){
                $this[$key] = $val;
            }
        }

        parent::__construct();
    }


    /**
     * Return first element of collection
     * @return Collection[]
     */
    public function first() {
        return reset( $this ); 
    }

    /**
     * Return the last element of collection
     * @return Collection[]
     */
    public function last() {
        $return = end( $this ); // Get last instance
        reset( $this );             // rewind array pointer
        return $return;             // Return instance
    }



    public function get_where($field,$value,$condition='==') {

  		return;

    }


    public function get_not_where(){

    	return;
    }

    public function get_where_callback($field,$value,$callback){
    	return;
    }


    /**
     * Convert Collection to associative array
     * @return array
     */
    public function to_array() {

        $return = array();
        foreach( (array) $this as $row ) {
            if (is_object($row)) {
                $return[] = $row->to_array();
            } else {
                $return[] = array($row);
            }
        }

        return $return;

    }


    /**
     * [add description]
     * @param [type] $Item [description]
     */
    public function add($Object){

        $this[$Object->id] = $Object;

        foreach($this->_index as $key => &$Collection ){
            //$Collection[] = &$this[$Object->id];
            $this->_index[$key][$Object->$key][] = &$this[$Object->id];
        }

    }


    public function get_index($name){
        if(isset($this->_index[$name])){
            return $this->_index[$name];
        } else {
            throw new Exception('Unable to retrieve index: '.$name.' from '.get_class($this));
        }
    }

    public function get_index_key($name,$key){
        $Object =  $this->get_index($name);

        return new static($Object[$key]);
    }



}