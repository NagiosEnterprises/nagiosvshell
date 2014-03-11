<?php

// Nagios V-Shell
// Copyright (c) 2010 Nagios Enterprises, LLC.
// Written by Mike Guthrie <mguthrie@nagios.com>
//
// LICENSE:
//
// This work is made available to you under the terms of Version 2 of
// the GNU General Public License. A copy of that license should have
// been provided with this software, but in any event can be obtained
// from http://www.fsf.org.
//
// This work is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
// 02110-1301 or visit their web page on the internet at
// http://www.fsf.org.
//
//
// CONTRIBUTION POLICY:
//
// (The following paragraph is not intended to limit the rights granted
// to you to modify and distribute this software under the terms of
// licenses that may apply to the software.)
//
// Contributions to this software are subject to your understanding and acceptance of
// the terms and conditions of the Nagios Contributor Agreement, which can be found
// online at:
//
// http://www.nagios.com/legal/contributoragreement/
//
//
// DISCLAIMER:
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
// INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
// PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
// HOLDERS BE LIABLE FOR ANY CLAIM FOR DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
// OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
// GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, STRICT LIABILITY, TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) OR OTHER ACTION, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


    //build page links based on user's permission level
    $ci = &get_instance();

    //generate links based on permissions
    $base = '/'.BASEURL;


?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
-->
    <title>Nagios V-Shell2</title>

    <!-- <link rel="stylesheet" href="<?php echo STATICURL; ?>/css/style.css"> -->

   <link rel="stylesheet" type="text/css" href="<?php echo STATICURL; ?>/bootstrap/css/bootstrap.min.css" />

    <script src="<?php echo STATICURL; ?>/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/angular.min.js"></script>

    <!-- use CDN's for now -->
    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular.min.js"></script> -->

    <script type="text/javascript" src="<?=STATICURL?>/bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
    <!--
    <div class="container">
            <a class="navbar-brand" href="/<?=BASEURL?>"><img src="<?=IMAGESURL?>/vshell.png"/></a>
            <a class="label" href="<?=COREURL?>" target="_blank" title="<?=gettext('Access Nagios Core')?>"><?=gettext('Access Nagios Core')?></a>
    </div>
-->
<nav class="container">
    <div class="navbar navbar-inverse" role="navigation">
        <div class="container-fluid">

            <ul class="nav navbar-nav">

                <!-- default tactical overview link -->
                <li><a href="<?=$base?>" class="navbar-brand">Nagios V-Shell</a></li>
                <li><a href="<?=$base?>/hosts"  rel="internal"><?=gettext('Hosts')?></a></li>
                <li><a href="<?=$base?>/services"  rel="internal"><?=gettext('Services')?></a></li>
                <li><a href="<?=$base?>/hostgroups"  rel="internal"><?=gettext('Hostgroups')?></a></li>
                <li><a href="<?=$base?>/servicegroups"  rel="internal"><?=gettext('Servicegroups')?></a></li>

<?php
    //Object Views
    if ($ci->nagios_user->if_has_authKey('authorized_for_configuration_information')) {
        //assuming full admin
?>
                <!-- Config Dropdown -->
                <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=gettext('Configurations')?></a>

                    <ul class="dropdown-menu">
                        <li><a href="<?=$base?>/configurations/hosts_objs"><?=gettext('Hosts')?></a></li>
                        <li><a href="<?=$base?>/configurations/services_objs"><?=gettext('Services')?></a></li>
                        <li><a href="<?=$base?>/configurations/hostgroups_objs"><?=gettext('Hostgroups')?></a></li>
                        <li><a href="<?=$base?>/configurations/servicegroups_objs"><?=gettext('Servicegroups')?></a></li>
                        <li><a href="<?=$base?>/configurations/timeperiods"><?=gettext('Timeperiods')?></a></li>
                        <li><a href="<?=$base?>/configurations/contacts"><?=gettext('Contacts')?></a></li>
                        <li><a href="<?=$base?>/configurations/contactgroups"><?=gettext('Contactgroups')?></a></li>
<?php
        if ($ci->nagios_user->is_admin()) {
?>            
                        <li><a href="<?=$base?>/configurations/commands" ><?=gettext('Commands')?></a></li>
<?php
        }
?>
                    </ul>
                </li>
<?php

    }

    //Nagios Core System links dropdown menu
    if ($ci->nagios_user->if_has_authKey('authorized_for_system_commands')) {

?>
                 <li >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=gettext('Commands')?></a>

                    <ul class="dropdown-menu">
                        <li><a  target="_blank" href="<?=CORECGI?>extinfo.cgi?type=3"><?=gettext('Comments')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>extinfo.cgi?type=6"><?=gettext('Downtime')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>extinfo.cgi?type=0"><?=gettext('Process Info')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>extinfo.cgi?type=4"><?=gettext('Performance Info')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>extinfo.cgi?type=7"><?=gettext('Scheduling Queue')?></a></li>
                    </ul>
                </li>
<?php        
    }

    //Nagios Core Reports.  Leaving authorization up to Core for running reports
?>
                <li >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=gettext('Reports')?></a>
                    <ul class="dropdown-menu">
                        <li><a  target="_blank" href="<?=CORECGI?>avail.cgi"><?=gettext('Availability')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>trends.cgi"><?=gettext('Trends')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>history.cgi?host=all"><?=gettext('Alert History')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>summary.cgi"><?=gettext('Alert Summary')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>histogram.cgi"><?=gettext('Alert Histogram')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>notifications.cgi?contact=all"><?=gettext('Notifications')?></a></li>
                        <li><a  target="_blank" href="<?=CORECGI?>showlog.cgi"><?=gettext('Event Log')?></a></li>

                     </ul>

                </li>

            </ul>
        </div>
    </div>
</nav>

<section class="main">
