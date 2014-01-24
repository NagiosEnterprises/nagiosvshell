<?php

## Custom values 
#
# Add any custom values here. If a value is empty, the default value defined 
# below is used. 

$custom = array(
	'targetdir'      => '',
	'apacheconfdir'  => '',
	'apacheconffile' => '',
	'etc_conf'       => '',
	'htpasswd_file'  => '',
	# See config/vshell.conf for explanations of each value
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
	'targetdir'      => '/usr/local/vshell2',
	'apacheconfdir'  => '/etc/apache2/conf.d',
	'apacheconffile' => 'vshell2.conf',
	'etc_conf'       => 'vshell2.conf',
	'htpasswd_file'  => '/etc/nagios3/htpasswd.users',
	'vshell_baseurl' => 'vshell2',
	'nagios_coreurl' => 'nagios3',
	'resultlimit'    => '100',
	'lang'           => 'en_GB',
	'TTL'            => '90',
);

$defaults['redhat'] = array(
	'targetdir'      => '/usr/local/vshell2',
	'apacheconfdir'  => '/etc/httpd/conf.d',
	'apacheconffile' => 'vshell2.conf',
	'etc_conf'       => 'vshell2.conf',
	'htpasswd_file'  => '/etc/nagios/passwd',
	'vshell_baseurl' => 'vshell2',
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
