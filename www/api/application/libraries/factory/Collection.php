<?php

class Collection extends ArrayObject
{


	public $columns;

	private $_conditions = array('==','===','<','>','<=','>=','!=');

    protected $_index = array();

    protected $_type;



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



    /**
     * Get all items from this collection where $field == $value
     * @param  string $field object property
     * @param  mixed  $value property value
     * @return Collection
     */
    public function get_where($field,$value) {
  		
        $classname = get_class($this);
        $Collection = new $classname();

        foreach($this as $key => $Object){
            if($Object->$field == $value){
                $Collection->add($Object);
            }
        }

        return $Collection;

    }


    /**
     * Get all items from this collection where $field != $value
     * @param  string $field object property
     * @param  mixed  $value property value
     * @return Collection
     */
    public function get_not_where($field,$value) {
        
        $classname = get_class($this);
        $Collection = new $classname();

        foreach($this as $key => $Object){
            if($Object->$field != $value){
                $Collection->add($Object);
            }
        }

        return $Collection;
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
                $return[] = get_object_vars($row);
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

        if( isset($Object[$key]) ){
            return new static($Object[$key]);
        }
    }



}
