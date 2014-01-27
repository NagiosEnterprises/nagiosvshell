<?php

class Collection extends ArrayObject
{


	public $columns;

	private $_conditions = array('==','===','<','>','<=','>=','!=');

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

}