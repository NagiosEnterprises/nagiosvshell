<?php

class Servicecomment extends NagiosObject
{

	protected static $_count;

	protected $_type = 'servicecomment';

	protected $_index = array(
		'host_name'	=> array(),
		'service_description' => array(),
		);


}