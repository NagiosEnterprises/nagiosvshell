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
//default for ubuntu/debian installs 
//define('APACHECONF',"/etc/apache2/conf.d"); 





/////////////////////////////////DO NOT EDIT BELOW THIS LINE////////////////////////
$errors = 0; 
$errorstring = ''; 

// @TODO: modify this script to find and parse the main nagios.cfg file for all relevant information.  


////////////////////////////////////////////////apache config 
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
	$errorstring.="Failed to copy config/vshell.conf file to /etc directory \n$output\n"; 
}
//////////////////////////making web directory
echo "Creating web directory...\n"; 
$output = system('/bin/mkdir '.TARGETDIR,$code); 
if($code > 0)
{
	$errors++;
	$errorstring.="ERROR: Failed to create ".TARGETDIR." directory \n$output\n"; 
}

echo "Copying files...\n"; 
$output = system('/bin/cp -r * '.TARGETDIR.'/',$code); 
if($code > 0)
{
	$errors++;
	$errorstring.="ERROR: Failed to copy files to ".TARGETDIR." directory \n$output\n"; 
}
echo "Cleaning up...\n"; 
$output = system('/bin/rm -f '.TARGETDIR.'/install.php',$code);
if($code > 0)
{
	$errors++;
	$errorstring.="ERROR: Failed to delete install script from web directory \n$output\n"; 
}

$service = ''; 
////////////////////////look for apache init script 
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
		$errorstring.="ERROR: Failed to restart apache, please restart apache manually \n$output\n"; 
	}
}
else 
{
	$errors++;
	$errorstring.="ERROR: Failed to restart apache, please restart apache manually \n$output\n"; 
}

echo "Checking for file locations...\n"; 

$change = "***Update this location in your /etc/vshell.conf file***\n"; 
////////////////////////////////look for object.cache file
$objectfile = '/usr/local/nagios/var/objects.cache'; //source installs 
if(file_exists($objectfile))
{
	//echo "Objects file found at: $objectfile\n"; 
}
elseif(file_exists('/var/nagios/objects.cache'))  //yum installs 
{
	$objectfile = '/var/nagios/objects.cache';
	echo "NOTICE: Objects file found at: $objectfile\n" . $change;	
}
elseif(file_exists('/var/cache/nagios3/objects.cache'))  //ubuntu debian nagios3 installs 
{
	$objectfile = '/var/cache/nagios3/objects.cache';
	echo "NOTICE: Objects file found at: $objectfile\n" . $change;	
}
else
{
	echo "NOTICE: objects.cache file not found.  Please specify the location of this file in your /etc/vshell.conf file\n"; 
	$objectfile = false; 
}

/////////////////////look for status.dat file
$statusfile = '/usr/local/nagios/var/status.dat'; //source installs 
if(file_exists($statusfile))
{
	//echo "Status file found at: $statusfile\n"; 
}
elseif(file_exists('/var/nagios/status.dat'))  //yum installs 
{
	$statusfile = '/var/nagios/status.dat';
	echo "NOTICE: Status file found at: $statusfile\n" . $change;

}
elseif(file_exists('/var/cache/nagios3/status.dat'))  //ubuntu debian nagios3 installs 
{
	$statusfile = '/var/cache/nagios3/status.dat';
	echo "NOTICE: Status file found at: $statusfile\n" . $change;	
}
else
{
	echo "NOTICE: status.dat file not found.  Please specify the location of this file in your /etc/vshell.conf file\n"; 
	$statusfile = false; 
}

/////////////////look for cgi.cfg file
$cgifile = '/usr/local/nagios/etc/cgi.cfg';  //source installs 
if(file_exists($cgifile))
{
	//echo "cgi.cfg file found at: $cgifile\n"; 
}
elseif(file_exists('/etc/nagios/cgi.cfg'))  //yum installs 
{
	$cgifile = '/etc/nagios/cgi.cfg';
	echo "NOTICE: cgi.cfg file found at: $cgifile\n" . $change;	
}
elseif(file_exists('/etc/nagios3/cgi.cfg'))  //ubuntu/debian nagios3 installs 
{
	$cgifile = '/etc/nagios3/cgi.cfg';
	echo "NOTICE: cgi.cfg file found at: $cgifile\n" . $change;	
}
else
{
	echo "NOTICE: cgi.cfg file not found.  Please specify the location of this file in your /etc/vshell.conf file\n"; 
	$objectfile = false; 
}


////////////////////////////////////look for nagios.cmd file 
$nagcmd = '/usr/local/nagios/var/rw/nagios.cmd';  //source install
if(file_exists($nagcmd)) 
{
	//echo "Nagios cmd file found at: $nagcmd\n"; 
}
elseif(file_exists('/var/nagios/rw/nagios.cmd'))  //yum install 
{
	$cgifile = '/var/nagios/rw/nagios.cmd';
	echo "NOTICE: Nagios cmd file found at: $nagcmd\n". $change;	
}
elseif(file_exists('/var/lib/nagios3/rw/nagios.cmd'))  //ubuntu/debian nagios3 
{
	$nagcmd = '/var/lib/nagios3/rw/nagios.cmd';
	echo "NOTICE: Nagios cmd file found at: $nagcmd\n". $change;	
}
else
{
	echo "NOTICE: nagios.cmd file not found.  Please specify the location of this file in your /etc/vshell.conf file\n"; 
	$nagcmd = false; 
}



//all done
echo "Script Complete!\n"; 
exit($errors); 


?>