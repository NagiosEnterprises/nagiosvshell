<?php

class ServiceStatusCollection extends NagiosCollection
{


	protected $_type = 'servicestatus';

	protected $_index = array(
		'host_name' => array(),
		'service_description' => array(),
		'current_state' => array(),
		);

}