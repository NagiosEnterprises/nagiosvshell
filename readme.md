# Nagios V-Shell

[![Build Status](https://travis-ci.org/chrislaskey/nagiosvshell.svg?branch=2.x)](https://travis-ci.org/chrislaskey/nagiosvshell)

A visual shell for Nagios


## Installation


### Prerequisites:

Nagios V-Shell is compatible with Nagios Core 3.X and Nagios IX.

V-Shell requires Apache and PHP 5.3 or higher. 


### Installing

The latest version of Nagios V-Shell is available on the official
[GitHub](https://github.com/NagiosEnterprises/nagiosvshell) project page.

With root permissions clone the latest version:

    # cd /tmp
    # git clone https://github.com/NagiosEnterprises/nagiosvshell

Next, verify the values in `config.php` are correct. Sensible defaults are
included for RHEL/CentOS 6 and Debian 7, and may require no action.

    # vim nagiosvshell/config.php

The last step is to run the installation script:

    # ./nagiosvshell/install.php

The installer will output meaningful errors if anything goes wrong during
installation. Once complete, Nagios V-Shell will be available at
`http://yourserver.com/vshell2`.


### Updating and Installation Details

Nagios V-Shell mean to be installed with little risk involved. It does not
touch existing Nagios Core or Nagios IX system files. The official nagios
console always remains accessible in the default location.

The newest V-Shell v2 is backwards compatible with V-Shell v1, both versions
can run side-by-side.

Updating V-Shell is done through the same installation process. Existing vshell
files in `/usr/local/vshell2` are automatically backed up into time stamped
directories in the `/usr/local/` directory.

Configuration details can be updated in the `/etc/vshell2.conf` file.
Note, some configuration options will require restarting Apache and PHP.


### Large Installations and APC Caching

Nagios V-Shell can take advantage of the Alternative PHP Cache system (APC),
which is of particular importance if you are managing a large installation. For
information about APC and installation instructions see the [APC
Documentation](http://php.net/manual/en/book.apc.php).

Nagios V-Shell is configured to automatically detect and use APC caching
if it installed. APC caching caches object configuration data each time there is
a change to the objects.cache file, and it caches status data based on the TTL
config option in the vshell.conf file. If TTL=90, V-Shell will cache data from
the status.dat file for 90 seconds before re-parsing the file.

Note: since APC uses system memory to cache data, it is recommended to increase
the `memory_limit` in the `php.ini`.


## Project Details, Getting Help, and Contributing

Nagios V-Shell v2 is an open source project released under GPL v2 and the
Nagios Open Source license. It is developed by Mike Guthrie and Chris Laskey.

Help requests and issues can be opened on the official
[GitHub](https://github.com/NagiosEnterprises/nagiosvshell) project page.

Both Mike and Chris develop V-Shell in our spare time, and will respond to
help requests as soon as we are able.

Contributions from other developers are encouraged and welcomed! For more
information on contributing to the project see the
[readme-developers.md](readme-developer.md) file.
