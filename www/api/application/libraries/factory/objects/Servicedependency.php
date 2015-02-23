<?php

class Servicedepencency extends NagiosObject
{

	protected static $_count;

	protected $_type = 'servicedependency';

	protected $_index = array(
		'host_name' => array(),
		'service_description' => array(),
		);



}