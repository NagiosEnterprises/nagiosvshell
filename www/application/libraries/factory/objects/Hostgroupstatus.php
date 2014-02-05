<?php

class Hostgroupstatus extends NagiosObject
{

	protected static $_count;

	protected $_type = 'hostgroupstatus';

	protected $_index = array(
		'host_name'	=> array(),
		'current_state' => array(),
		);


}