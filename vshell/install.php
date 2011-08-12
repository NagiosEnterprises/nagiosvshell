#!/usr/bin/php
<?php
// Nagios V-Shell
// Copyright (c) 2010-2011 Nagios Enterprises, LLC.
// Install script written by Mike Guthrie <mguthrie@nagios.com>
//

// ***********MODIFY THE DIRECTORY LOCATIONS BELOW TO MATCH YOUR NAGIOS INSTALL*********************

//target directory where vshell's web files will be stored  
define('TARGETDIR',"/usr/local/vshell");
//target directory where your current apache configuration directory is located
define('APACHECONF',"/etc/httpd/conf.d"); 


/////////////////////////////////DO NOT EDIT BELOW THIS LINE////////////////////////
$errors = 0; 
$errorstring = ''; 

//apache config 
echo "Copying apache configuration file...\n"; 
$output = system('/bin/cp config/vshell_apache.conf '.APACHECONF.'/vshell.conf', $code);
if($code > 0) 
{
	$errorstring .= "Failed to copy apache configuration file config/vshell_apache.conf to ".APACHECONF."\n $output\n";	
	$errors++; 
}

//main v-shell config 
echo "Copying vshell configuration file...\n"; 		
$output = system('/bin/cp config/vshell.conf /etc/',$code); 
if($code > 0)
{
	$errors++;
	$errorstring.="Failed to copy main vshell.conf file to /etc directory \n$output\n"; 
}
//making web directory
echo "Creating web directory...\n"; 
$output = system('/bin/mkdir '.TARGETDIR,$code); 
if($code > 0)
{
	$errors++;
	$errorstring.="Failed to create ".TARGETDIR." directory \n$output\n"; 
}

echo "Copying files...\n"; 
$output = system('/bin/cp -r * '.TARGETDIR.'/',$code); 
if($code > 0)
{
	$errors++;
	$errorstring.="Failed to copy files to ".TARGETDIR." directory \n$output\n"; 
}
echo "Cleaning up...\n"; 
$output = system('/bin/rm -f'.TARGETDIR.'/install.php',$code);
if($code > 0)
{
	$errors++;
	$errorstring.="Failed to delete install script from web directory \n$output\n"; 
}

$service = ''; 
//look for apache init script 
if(file_exists('/etc/init.d/httpd'))
	$service = '/etc/init.d/httpd'; 
elseif(file_exists('/etc/init.d/apache2'))
	$service = '/etc/init.d/apache2';
else 	
	$service =false; 

if($service)
{
	echo "Restarting apache...\n"; 
	$output = system($service." restart",$code); 
	if($code > 0)
	{
		$errors++;
		$errorstring.="Failed to restart apache, please restart apache manually \n$output\n"; 
	}
}
else 
{
	$errors++;
	$errorstring.="Failed to restart apache, please restart apache manually \n$output\n"; 
}

echo "Checking for file locations...\n"; 

//look for object.cache file
$objectfile = '/usr/local/nagios/var/objects.cache'; 
if(file_exists($objectsfile))
{
	echo "Objects file found at: $objectfile\n"; 
}
elseif(file_exists('/var/nagios/object.cache'))  
{
	echo "Objects file found at: $objectfile\n";
	$objectfile = 
}
else
{
	echo "objects.cache file not found.  Please specify the location of this file in your /etv/vshell.conf file\n"; 
	$objectfile = false; 
}

//look for status.dat file
$statusfile = '/usr/local/nagios/var/status.dat'; 
if(file_exists($statusfile))
{
	echo "Status file found at: $statusfile\n"; 
}
elseif(file_exists('/var/nagios/status.dat'))  
{
	echo "Status file found at: $statusfile\n";
	$statusfile = '/var/nagios/status.dat'
}
else
{
	echo "status.dat file not found.  Please specify the location of this file in your /etv/vshell.conf file\n"; 
	$statusfile = false; 
}

//look for cgi.cfg file
$cgifile = '/usr/local/nagios/etc/cgi.cfg'; 
if(file_exists($objectsfile))
{
	echo "cgi.cfg file found at: $cgifile\n"; 
}
elseif(file_exists('/etc/nagios/cgi.cfg'))  
{
	echo "cgi.cfg file found at: $cgifile\n";
	$cgifile = '/etc/nagios/cgi.cfg'
}
else
{
	echo "cgi.cfg file not found.  Please specify the location of this file in your /etv/vshell.conf file\n"; 
	$objectfile = false; 
}


//look for nagios.cmd file 
$nagcmd = '/usr/local/nagios/var/rw/nagios.cmd'
if(file_exists($nagcmd)) 
{
	echo "Nagios cmd file found at: $nagcmd\n"; 
}
elseif(file_exists('/var/nagios/rw/nagios.cmd'))  
{
	echo "Nagios cmd file found at: $nagcmd\n";
	$cgifile = '/var/nagios/rw/nagios.cmd';
}
else
{
	echo "nagios.cmd file not found.  Please specify the location of this file in your /etv/vshell.conf file\n"; 
	$nagcmd = false; 
}

//all done
echo "Script Complete!\n"; 
exit($errors); 


?>