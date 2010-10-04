This document describes how to install and setup the Nagios V-Shell or “Visual” Shell for Nagios Core and Nagios XI installations.  


Target Audience

This document is intended for use by Nagios XI and Nagios Core Administrators, and assumes that Nagios Core is installed on the target system.    


Overview

The Nagios V-Shell is a web interface written in PHP that is designed to render as valid XHTML and be fully styled and formatted using CSS classes, while maintaining the power of Nagios Core for issuing system and node commands.  


Installation 

Before installation:  You will need to know the location of Apache configuration directory where your nagios.conf file is located, as well as the htpasswd.users file that contains your Nagios Core authorizations.  These can be found using the following command:

locate <filename>

To download and install, login to your Nagios server and type the following commands from the command-line: 

cd /tmp
wget http://assets.nagios.com/ <downloadURL>
tar zxf vshell.tar.gz
cd vshell
mkdir /usr/local/vshell
cp * /usr/local/vshell

To set up your apache configuration, modify the contents of the vshell.conf file to match the directory structure of your distribution and your installation of Nagios.  Use a text editor to modify this file as needed, then copy this file to the same directory as your nagios.conf file.  


V-Shell Constants
For custom configurations with Apache, changes may need to be made to the /vshell/constants.inc.php file, which contains variables for the V-shell URL, as well as the Nagios Core cgi's.  Important Note: V-Shell assumes that your Nagios Core installation can be accessed via http://<servername>/nagios.  If your configuration differs from this, make changes are appropriate to the constants.inc.php file.  If errors occur after installation, verify that the constants point to the correct web URLS.  Future versions will include a separate config file and possibly a wizard to verify system configuration.  

Getting Started

Nagios V-Shell gets authorization information from the existing Nagios htpasswd.users file, as well as the cgi.cfg file for Nagios Core.  Most permissions for Nagios Core should be reflected in the Nagios V-Shell as well.  To get started, log into your Nagios webserver at http://<yourserver>/vshell, and enter your Nagios Core authentication information.  

Vshell maintains most of the same features of Nagios Core, while utilizing a top menu bar for site navigation.  This is done to maximize space for table viewing for hosts and services.  

Nagios Core system commands, reports, and the Core interface can all be accessed by direct links from V-Shell.  