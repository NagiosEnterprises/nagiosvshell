<?php

class Servicegroupstatus extends NagiosObject
{

	protected static $_count;

	protected $_type = 'servicegroupstatus';

	protected $_index = array(
		'host_name'	=> array(),
		'service_description' => array(),
		'current_state' => array(),
		);


}