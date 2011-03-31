This document describes how to install and setup the Nagios V-Shell or “Visual”
Shell for Nagios Core and Nagios XI installations.  


Target Audience
---------------

This document is intended for use by Nagios XI and Nagios Core Administrators, 
and assumes that Nagios Core is installed on the target system.    


Overview
--------

The Nagios V-Shell is a web interface written in PHP that is designed to render
as valid XHTML and be fully styled and formatted using CSS classes, while 
maintaining the power of Nagios Core for issuing system and node commands.  


Installation 
------------

Prerequisites:  

You will need to know the location of Apache configuration directory where your
nagios.conf file is located, as well as the htpasswd.users file that contains 
your Nagios Core authorizations.  These can be found using the following 
command: locate <filename>

Edit TARGETDIR and APACHECONF in install.sh as appropriate.

V-Shell takes advantage of the Alternative PHP Cache system (APC), which is 
of particular importance if you are managing a large installation.  For 
information about APC and installation instructions see 
  http://php.net/manual/en/book.apc.php

To install:
Download and untar the latest V-Shell tarball from:
  http://assets.nagios.com/downloads/exchange/nagiosvshell/vshell.tar.gz
*or* download the current development version from Subversion
  https://nagios.svn.sourceforge.net/svnroot/nagios/nagiosvshell/trunk

Most people will NOT need to do this part:
(The defaults are appropriate for a default Nagios install on CentOS / RHEL.)
If necessary, to modify your apache configuration, modify the contents of the
apache_vshell.conf file to match the directory structure of your distribution
and your installation of Nagios BEFORE running th install script.  Use a text
editor to modify this file as needed, then copy this file to the same directory
as your nagios.conf file.

From where you unpacked / checked out the tree:
cd vshell
./install.sh


Configuration
--------------

*Important Notes*:
For custom configurations with Apache, changes may need to be made to the 
/etc/vshell.conf file, which contains variables for the V-shell URL, 
as well as the Nagios Core cgi's.  

V-Shell assumes that your Nagios Core installation can be accessed via 
http://<servername>/nagios.  If your configuration differs from this, make 
changes are appropriate to the /etc/vshell.conf file.  If errors occur after 
installation, verify that the constants point to the correct web URLS.  

Future versions will include a separate config file and possibly a wizard to 
verify system configuration.  


Getting Started
---------------

Nagios V-Shell gets authorization information from the existing Nagios / Apache
access controll mechanisms (usually a htpasswd.users file), as well as the 
cgi.cfg file for Nagios Core.  Most permissions for Nagios Core should be 
reflected in the Nagios V-Shell as well.  To get started, log into your Nagios 
webserver at http://<yourserver>/vshell, and enter your Nagios Core 
authentication information.  

V-Shell maintains most of the same features of Nagios Core, while utilizing 
a top menu bar for site navigation.  This is done to maximize space for table 
viewing for hosts and services.  

Nagios Core system commands, reports, and the Core interface can all be 
accessed by direct links from V-Shell. 


Getting Help

For problems, feeback, or support, use the V-Shell forum at the following address:
http://support.nagios.com/forum/viewforum.php?f=19



Developer Notes:

We're looking for people who want to contribute to the V-Shell project, see the TODO file for more information.   
