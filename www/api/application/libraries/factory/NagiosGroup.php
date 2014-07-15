<?php

class NagiosGroup extends NagiosObject
{


	protected $memberstring = '';

	public function get_members(){

		$members = explode(',',$memberstring);

		array_walk($members,function(&$n){
			trim($n);
		});

		return $members;
	}


}