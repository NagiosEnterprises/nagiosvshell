<?php

class Hoststatus extends NagiosObject
{

	protected static $_count;

	protected $_type = 'hoststatus';

	protected $_index = array(
		'host_name'	=> array(),
		'current_state' => array(),
		);


}