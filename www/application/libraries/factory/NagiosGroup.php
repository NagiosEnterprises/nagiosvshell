<?php

abstract class NagiosGroup extends NagiosObject
{

	public $name;

	public $alias;

	protected $memberstring = '';

	public function get_members(){

		$members = explode(',',$memberstring);

		array_walk($members,function(&$n){
			trim($n);
		});

		return $members;
	}


}