<?php

class HostStatusCollection extends NagiosCollection
{


	protected $_type = 'hoststatus';

	protected $_index = array(
		'host_name'	=> array(),
		'current_state' => array(),
		);


}