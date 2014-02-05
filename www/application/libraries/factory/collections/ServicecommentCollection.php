<?php

class ServicecommentCollection extends NagiosCollection
{


	protected $_type = 'servicecomment';

	protected $_index = array(
		'host_name'	=> array(),
		'service_description' => array(),
		);


}