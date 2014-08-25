# Nagios V-Shell

[![Build Status](https://travis-ci.org/chrislaskey/nagiosvshell.svg?branch=2.x)](https://travis-ci.org/chrislaskey/nagiosvshell)

A modern visual shell for Nagios


## Alpha Release

Nagios V-Shell `2.0.0-x` is currently documented, tested, and stable.

There are two features are planned for the official `2.0.0` that are not
currently implemented:

- Frontend i18n language support
- Backend caching using PHP APC

Otherwise the alpha release is feature complete.


### Release Feedback

Nagios V-Shell does not modify any Nagios files and installs to a separate
location. Both the alpha and Nagios V-Shell v1 can be run alongside eachother.

Users are encouraged to try the alpha release and give feedback by submitting
[GitHub issues](https://github.com/NagiosEnterprises/nagiosvshell/issues). This
will allow all alpha users a single place to join the discussion and help shape
the future of the project.

Please include the specific release version when starting a [new
issue](https://github.com/NagiosEnterprises/nagiosvshell/issues/new). The
release version is available in the included `package.json` file or by visiting
the options page through the web interface, `/vshell2/#/options`.


## Live Demonstration

A live demonstration is available on the [project
website](https://nagiosenterprises.github.io/nagiosvshell/).

Note the frontend is reading cached API output and rendering the
results. As a result, timestamps in the demo will not be current.


## Installation


### Prerequisites

Nagios V-Shell is compatible with Nagios XI and Nagios Core 3.X.

V-Shell requires Apache and PHP 5.3 or higher. 


### Installing

The latest version of Nagios V-Shell is available on the official
[GitHub project page](https://github.com/NagiosEnterprises/nagiosvshell).

With root permissions clone the latest version:

    cd /tmp
    git clone https://github.com/NagiosEnterprises/nagiosvshell

Next, checkout the alpha release branch:

    cd nagiosvshell
    git checkout 2.x

Then verify the values in `config.php` are correct. Sensible defaults are
included for RHEL/CentOS 6 and Debian 7, and may require no action.

    vim config.php

The last step is to run the installation script:

    ./install.php

The installer will output meaningful errors if anything goes wrong during
installation. Once complete, Nagios V-Shell will be available at
`http://yourserver.com/vshell2`.


### Updating and Installation Details

Nagios V-Shell mean to be installed with little risk involved. It does not
touch existing Nagios XI or Nagios Core system files. The official nagios
web-interface always remains accessible in the default location.

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
[GitHub project page](https://github.com/NagiosEnterprises/nagiosvshell). We
develop this project in our spare time and will respond to help requests as
soon as we are able.

Contributions from other developers are both encouraged and welcomed. For more
information on contributing to the project see the
[readme-developers.md](readme-developers.md) file.
