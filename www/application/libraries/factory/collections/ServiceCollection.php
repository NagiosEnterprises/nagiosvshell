<?php

class ServiceCollection extends NagiosCollection
{


	protected $_type = 'service';

	protected $_index = array(
		'host_name' => array(),
		'service_description' => array()
		);

}