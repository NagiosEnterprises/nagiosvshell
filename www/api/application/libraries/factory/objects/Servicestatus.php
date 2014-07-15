<?php

class Servicestatus extends NagiosObject
{

	protected static $_count;

	protected $_type = 'servicestatus';

	protected $_index = array(
		'host_name' => array(),
		'service_description' => array(),
		'current_state' => array(),
		);



}