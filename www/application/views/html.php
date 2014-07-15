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
    <script src="<?php echo STATICURL; ?>/js/typeahead.bundle.min.js"></script>
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
                <a href="#" id="display-options">Display Options</a>
            </div>

        </section>

        <section id="main" class="clearfix">

            <header class="clearfix">
                <div class="logo-container">
                    <a class="logo" href="#">Nagios <span class="emphasis">V-Shell</span></a>
                    <a class="nav-button" id="nav-button-open" href="#">Open Navigation</a>
                </div>
                <div class="search-container">
                    <form id="quicksearch" action="#" method="get">
                        <input class="input" type="text" value="" placeholder="Quick Search"></input>
                    </form>
                </div>
                <div class="info-container">
                    <span class="logged-in-as">Logged in as Status</span><br/>
                    <span class="last-update">Fri May 2 14:30:34 EDT 2014</span>
                </div>
            </header>

            <section id="status" class="clearfix">
                <div id="hosts-status">
                    Hosts
                    <span class="status-block active hosts-up">0 Up</span>
                    <span class="status-block active hosts-down">0 Down</span>
                    <span class="status-block active hosts-unreachable">0 Unreachable</span>
                    <span class="status-block active hosts-pending">0 Pending</span>
                </div>
                <div id="services-status">
                    Services
                    <span class="status-block active services-ok">0 OK</span>
                    <span class="status-block active services-warning">0 Warning</span>
                    <span class="status-block active services-unknown">0 Unknown</span>
                    <span class="status-block active services-critical">0 Critical</span>
                    <span class="status-block active services-pending">0 Pending</span>
                </div>
            </section>

            <script type="text/javascript" src="<?php echo STATICURL; ?>/js/modules/filters.js"></script>
            <script type="text/javascript" src="<?php echo STATICURL; ?>/js/modules/hoststatus.js"></script>

            <div id="content" class="clearfix" ng-app="hoststatus" ng-controller="HoststatusCtrl" ng-init="getHoststatus()">

                <div class="table-container full-width">

                    <h1>Host Status Details For All Host Groups</h1>

                    <div class="table-options">
                        <form action="" class="filter-form" method="post">
                            <div class="filter-icon"></div>
                            <input id="footable-filter-1" class="footable-filter" type="text" placeholder="Filter Table"/>
                        </form>
                        <div class="pagesize-container">
                            Show <a class="active" href="#" data-page-size="25">25</a> <a href="#" data-page-size="100">100</a> <a href="#" data-page-size="50000">All</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table cellspacing="0" cellpadding="0" class="footable" data-page-size="25" data-page-navigation="#footable-pagination-1" data-filter="#footable-filter-1">
                        <thead>
                            <tr>
                                <th><?php echo gettext('Host Name'); ?></th>
                                <th><?php echo gettext('Status'); ?></th>
                                <th data-hide="phone"><?php echo gettext('Duration'); ?></th>
                                <th data-hide="phone"><?php echo gettext('Attempt'); ?></th>
                                <th data-hide="phone"><?php echo gettext('Last Check'); ?></th>
                                <th data-hide="phone"><?php echo gettext('Status Information'); ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <div class="footable-pagination pagination" id="footable-pagination-1"></div>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr ng-repeat="host in hoststatus" class="state-{{ host.current_state | hoststate | lowercase }}" ng-class-odd="'footable-odd'" ng-class-even="'footable-even'" footabledata>
                                <td>{{host.host_name}}</td>
                                <td class="status">{{ host.current_state | hoststate }}</td>
                                <td>{{host.last_state_change}}</td>
                                <td>{{host.current_attempt}} / {{host.max_attempts}}</td>
                                <td>{{host.last_check}}</td>
                                <td>{{host.plugin_output}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="table-row-container">

                    <div class="table-container partial-width">

                        <h3>Host Status Details For All Host Groups</h3>

                        <div class="table-options">
                            <form action="" class="filter-form" method="post">
                                <div class="filter-icon"></div>
                                <input id="footable-filter-2" class="footable-filter" type="text" placeholder="Filter Table"/>
                            </form>
                            <div class="pagesize-container">
                                Show <a class="active" href="#" data-page-size="10">10</a> <a href="#" data-page-size="50">50</a> <a href="#" data-page-size="50000">All</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <table cellspacing="0" cellpadding="0" class="footable" data-page-size="10" data-page-navigation="#footable-pagination-2" data-filter="#footable-filter-2">
                            <thead>
                                <tr>
                                    <th><?php echo gettext('Host Name'); ?></th>
                                    <th><?php echo gettext('Status'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Duration'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Attempt'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Last Check'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Status Information'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="footable-pagination pagination" id="footable-pagination-2"></div>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr ng-repeat="host in hoststatus" class="state-{{ host.current_state | hoststate | lowercase }}" ng-class-odd="'footable-odd'" ng-class-even="'footable-even'" footabledata>
                                    <td>{{host.host_name}}</td>
                                    <td class="status">{{ host.current_state | hoststate }}</td>
                                    <td>{{host.last_state_change}}</td>
                                    <td>{{host.current_attempt}} / {{host.max_attempts}}</td>
                                    <td>{{host.last_check}}</td>
                                    <td>{{host.plugin_output}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-container partial-width">

                        <h3>Host Status Details For All Host Groups</h3>

                        <div class="table-options">
                            <form action="" class="filter-form" method="post">
                                <div class="filter-icon"></div>
                                <input id="footable-filter-3" class="footable-filter" type="text" placeholder="Filter Table"/>
                            </form>
                            <div class="pagesize-container">
                                Show <a class="active" href="#" data-page-size="10">10</a> <a href="#" data-page-size="50">50</a> <a href="#" data-page-size="50000">All</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <table cellspacing="0" cellpadding="0" class="footable" data-page-size="10" data-page-navigation="#footable-pagination-3" data-filter="#footable-filter-3">
                            <thead>
                                <tr>
                                    <th><?php echo gettext('Host Name'); ?></th>
                                    <th><?php echo gettext('Status'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Duration'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Attempt'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Last Check'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Status Information'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="footable-pagination pagination" id="footable-pagination-3"></div>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr ng-repeat="host in hoststatus" class="state-{{ host.current_state | hoststate | lowercase }}" ng-class-odd="'footable-odd'" ng-class-even="'footable-even'" footabledata>
                                    <td>{{host.host_name}}</td>
                                    <td class="status">{{ host.current_state | hoststate }}</td>
                                    <td>{{host.last_state_change}}</td>
                                    <td>{{host.current_attempt}} / {{host.max_attempts}}</td>
                                    <td>{{host.last_check}}</td>
                                    <td>{{host.plugin_output}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="table-container partial-width">

                        <h3>Host Status Details For All Host Groups</h3>

                        <div class="table-options">
                            <form action="" class="filter-form" method="post">
                                <div class="filter-icon"></div>
                                <input id="footable-filter-4" class="footable-filter" type="text" placeholder="Filter Table"/>
                            </form>
                            <div class="pagesize-container">
                                Show <a class="active" href="#" data-page-size="10">10</a> <a href="#" data-page-size="50">50</a> <a href="#" data-page-size="50000">All</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <table cellspacing="0" cellpadding="0" class="footable" data-page-size="10" data-page-navigation="#footable-pagination-4" data-filter="#footable-filter-4">
                            <thead>
                                <tr>
                                    <th><?php echo gettext('Host Name'); ?></th>
                                    <th><?php echo gettext('Status'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Duration'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Attempt'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Last Check'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Status Information'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="footable-pagination pagination" id="footable-pagination-4"></div>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr ng-repeat="host in hoststatus" class="state-{{ host.current_state | hoststate | lowercase }}" ng-class-odd="'footable-odd'" ng-class-even="'footable-even'" footabledata>
                                    <td>{{host.host_name}}</td>
                                    <td class="status">{{ host.current_state | hoststate }}</td>
                                    <td>{{host.last_state_change}}</td>
                                    <td>{{host.current_attempt}} / {{host.max_attempts}}</td>
                                    <td>{{host.last_check}}</td>
                                    <td>{{host.plugin_output}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="clearfix"></div>

                </div>

                <div class="table-row-container">

                    <div class="table-container partial-width">

                        <h3>Host Status Details For All Host Groups</h3>

                        <div class="table-options">
                            <form action="" class="filter-form" method="post">
                                <div class="filter-icon"></div>
                                <input id="footable-filter-5" class="footable-filter" type="text" placeholder="Filter Table"/>
                            </form>
                            <div class="pagesize-container">
                                Show <a class="active" href="#" data-page-size="10">10</a> <a href="#" data-page-size="50">50</a> <a href="#" data-page-size="50000">All</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <table cellspacing="0" cellpadding="0" class="footable" data-page-size="10" data-page-navigation="#footable-pagination-5" data-filter="#footable-filter-5">
                            <thead>
                                <tr>
                                    <th><?php echo gettext('Host Name'); ?></th>
                                    <th><?php echo gettext('Status'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Duration'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Attempt'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Last Check'); ?></th>
                                    <th data-hide="phone,tablet"><?php echo gettext('Status Information'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="footable-pagination pagination" id="footable-pagination-5"></div>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr ng-repeat="host in hoststatus" class="state-{{ host.current_state | hoststate | lowercase }}" ng-class-odd="'footable-odd'" ng-class-even="'footable-even'" footabledata>
                                    <td>{{host.host_name}}</td>
                                    <td class="status">{{ host.current_state | hoststate }}</td>
                                    <td>{{host.last_state_change}}</td>
                                    <td>{{host.current_attempt}} / {{host.max_attempts}}</td>
                                    <td>{{host.last_check}}</td>
                                    <td>{{host.plugin_output}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

            <footer class="clearfix">
                <div class="info-container">
                    <span class="logged-in-as">Logged in as Status</span><br/>
                    <span class="last-update">Fri May 2 14:30:34 EDT 2014</span>
                </div>
            </section>

        </section>

    </section>

</body>
</html>
