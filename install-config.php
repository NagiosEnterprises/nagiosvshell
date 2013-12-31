<?php

# Custom values 
#
# NOTE: Add any custom values here

$custom = array(
	'targetdir'  => '',
	'apacheconf' => '',
);

# Default values
#
# Make an educated guess at sensible defaults

$defaults = array();

$defaults['debian'] = array(
	'targetdir'  => '/usr/local/vshell',
	'apacheconf' => '/etc/apache2/conf.d',
);

$defaults['redhat'] = array(
	'targetdir'  => '/usr/local/vshell',
	'apacheconf' => '/etc/httpd/conf.d',
);

# Create defintions
#
# Determine the OS family, merge custom values with defaults,
# and create PHP definitions for each key.

$default_values = $defaults[get_os_family()];
$custom_values = array_filter($custom);
$config = array_merge($default_values, $custom_values);

create_definitions($config);

# Helper functions

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
