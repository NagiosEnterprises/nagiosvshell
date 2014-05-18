<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!--
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    -->

    <title>Nagios V-Shell2</title>

    <link rel="stylesheet" type="text/css" href="<?php echo STATICURL; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo STATICURL; ?>/css/footable.core.min.css" />

    <script src="<?php echo STATICURL; ?>/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/angular.min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/jquery.cookie.js"></script>
    <script src="<?php echo STATICURL; ?>/js/underscore-min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/moment.min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/footable.all.min.js"></script>
    <script src="<?php echo STATICURL; ?>/js/main.js"></script>

</head>
<body class="theme-dark sidebar-open">

    <section id="page">

        <section id="sidebar">

            <div class="logo-container">
                <a class="logo" href="#">Nagios <span class="emphasis">V-Shell</span></a>
                <a class="nav-button" id="nav-button-close" href="#">Close Navigation</a>
            </div>

            <nav>
                <ul class="local">
                    <li><a href="/vshell2">Tactical Overview</a></li>
                    <li><a href="/vshell2/hosts">Hosts</a></li>
                    <li><a href="/vshell2/services">Services</a></li>
                    <li><a href="/vshell2/hostgroups">Hostgroups</a></li>
                    <li><a href="/vshell2/servicegroups">Servicegroups</a></li>
                    <li>
                        <a href="#">Configurations</a>
                        <ul>
                            <li><a href="/vshell2/configurations/hosts_objs">Hosts</a></li>
                            <li><a href="/vshell2/configurations/services_objs">Services</a></li>
                            <li><a href="/vshell2/configurations/hostgroups_objs">Hostgroups</a></li>
                            <li><a href="/vshell2/configurations/servicegroups_objs">Servicegroups</a></li>
                            <li><a href="/vshell2/configurations/timeperiods">Timeperiods</a></li>
                            <li><a href="/vshell2/configurations/contacts">Contacts</a></li>
                            <li><a href="/vshell2/configurations/contactgroups">Contactgroups</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="external">
                    <li>
                        <a href="#">Commands<span class="icon external"></span></a>
                        <ul>
                            <li><a  target="_blank" href="/nagios/cgi-bin/extinfo.cgi?type=3">Comments</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/extinfo.cgi?type=6">Downtime</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/extinfo.cgi?type=0">Process Info</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/extinfo.cgi?type=4">Performance Info</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/extinfo.cgi?type=7">Scheduling Queue</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Reports<span class="icon external"></span></a>
                        <ul>
                            <li><a  target="_blank" href="/nagios/cgi-bin/avail.cgi">Availability</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/trends.cgi">Trends</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/history.cgi?host=all">Alert History</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/summary.cgi">Alert Summary</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/histogram.cgi">Alert Histogram</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/notifications.cgi?contact=all">Notifications</a></li>
                            <li><a  target="_blank" href="/nagios/cgi-bin/showlog.cgi">Event Log</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div id="sidebar-footer">
                <a href="#">Feedback</a>
            </div>

        </section>

        <section id="main" class="clearfix">

            <header class="clearfix">
                <a class="nav-button" id="nav-button-open" href="#">Open Navigation</a>
                <div class="search-container">
                    <form id="quicksearch" action="#" method="get">
                        <input class="submit" type="submit" value=""/></input>
                        <input class="input" type="text" value="" placeholder="Quick Search"></input>
                    </form>
                </div>
                <div class="info-container">
                    <span class="logged-in-as">Logged in as Status</span><br/>
                    <span class="last-update">Fri May 2 14:30:34 EDT 2014</span>
                </div>
            </header>


        </section>

    </section>

</body>
</html>
