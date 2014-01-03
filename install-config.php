<?php

## Custom values 
#
# Add any custom values here. If a value is null, the default value defined 
# below is used. 

$custom = array(
	'targetdir'      => '',
	'apacheconf'     => '',
	# See config/vshell.conf.php for explanations of each value
	'vshell_baseurl' => '',
	'nagios_coreurl' => '',
	'resultlimit'    => '',
	'lang'           => '',
	'TTL'            => '',
);

## Default values
#
# Do not edit these defaults

$defaults = array();

$defaults['debian'] = array(
	'targetdir'      => '/usr/local/vshell',
	'apacheconf'     => '/etc/apache2/conf.d',
	'vshell_baseurl' => 'vshell',
	'nagios_coreurl' => 'nagios',
	'resultlimit'    => '100',
	'lang'           => 'en_GB',
	'TTL'            => '90',
);

$defaults['redhat'] = array(
	'targetdir'      => '/usr/local/vshell',
	'apacheconf'     => '/etc/httpd/conf.d',
	'vshell_baseurl' => 'vshell',
	'nagios_coreurl' => 'nagios',
	'resultlimit'    => '100',
	'lang'           => 'en_GB',
	'TTL'            => '90',
);

## Create defintions
#
# Determine the OS family, merge custom values with defaults,
# and create PHP definitions for each key.

$default_values = $defaults[get_os_family()];
$custom_values = array_filter($custom);
$config = array_merge($default_values, $custom_values);

create_definitions($config);

## Helper functions

function get_os_family(){
	# Simple uname check, default to redhat
	$output = system('uname -a');
	$is_debian = stripos($output, 'debian') !== False || stripos($output, 'ubuntu') !== False;
	return $is_debian ? 'debian' : 'redhat';
}

function create_definitions($values){
	foreach($values as $key => $value){
		$key = strtoupper($key);
		define($key, $value);
	}
}

// End of file install-config.php
