'use strict';

angular.module('vshell2.filters', [])

    .filter('capitalize', function() {
        return function(input) {
            var first = input.substring(0,1).toUpperCase(),
                rest = input.substring(1);
            return first + rest;
        }
    })

    .filter('objectToArray', function() {
        return function(obj) {
            var i, arr = []; 
            for(i in obj){
                arr.push(obj[i]);
            }
            return arr;
        }
    })

    .filter('is_active', function() {
        return function(input) {
            var as_int = parseInt(input, 10);
            return as_int > 0 ? 'active' : 'inactive';
        };
    })

    .filter('is_enabled', function() {
        return function(input) {
            var as_int = parseInt(input, 10);
            return as_int > 0 ? 'enabled' : 'disabled';
        };
    })

    .filter('ago', function() {
        return function(timestamp) {
            var now = new Date(),
                beginning = new Date(timestamp * 1000),
                seconds_per_minute = 60,
                seconds_per_hour = 3600,
                seconds_per_day = 86400,
                retval = '',
                remaining_duration, days, hours, minutes, seconds;

            remaining_duration = Math.round ( ( now.getTime() / 1000 )  - ( beginning.getTime() / 1000) );
            days = Math.round( remaining_duration / seconds_per_day);
            remaining_duration -= days * seconds_per_day;
            hours = Math.round( remaining_duration / seconds_per_hour );
            remaining_duration -= hours * seconds_per_hour;
            minutes = Math.round( remaining_duration / seconds_per_minute );
            remaining_duration -= minutes * seconds_per_minute;
            seconds = remaining_duration;
            
            if (days > 0) {
                retval += days+'d ';
            }

            if (hours > 0 || days > 0) {
                retval += (hours < 0 ? 0 : hours)+'h ';
            }

            if (minutes > 0 || days > 0 || hours > 0) {
                retval += (minutes < 0 ? 0 : minutes) + 'm ';
            }

            if (seconds > 0 || minutes > 0 || days > 0 || hours > 0) {
                retval += (seconds < 0 ? 0 : seconds) + 's';
            }

            return retval;
        };
    })

    .filter('hoststate', function() {
        return function(input) {
            var lookup = {
                    '0': 'Up',
                    '1': 'Down',
                    '2': 'Unreachable',
                    '3': 'Pending',
                    '4': 'Problems',
                    '5': 'Unhandled',
                    '6': 'Acknowledged'
                };

            return lookup[input] || 'Undefined';
        };
    })

    .filter('hostcommand', function() {
        return function(host, type) {
            var commands = {
                    'acknowledge_problem': '/nagios/cgi-bin/cmd.cgi?cmd_typ=33&host=',
                    'active_checks': '/nagios/cgi-bin/cmd.cgi?cmd_typ=48&host=',
                    'custom_notification': '/nagios/cgi-bin/cmd.cgi?cmd_typ=159&host=',
                    'flap_detection': '/nagios/cgi-bin/cmd.cgi?cmd_typ=58&host=',
                    'map_host': '/nagios/cgi-bin/statusmap.cgi?host=',
                    'notifications': '/nagios/cgi-bin/cmd.cgi?cmd_typ=25&host=',
                    'obsession': '/nagios/cgi-bin/cmd.cgi?cmd_typ=102&host=',
                    'passive_checks': '/nagios/cgi-bin/cmd.cgi?cmd_typ=39&host=',
                    'schedule_check_for_all': '/nagios/cgi-bin/cmd.cgi?cmd_typ=17&host=',
                    'schedule_downtime': '/nagios/cgi-bin/cmd.cgi?cmd_typ=55&host=',
                    'schedule_downtime_for_all': '/nagios/cgi-bin/cmd.cgi?cmd_typ=86&host=',
                    'host_in_nagios_core': '/nagios/cgi-bin/extinfo.cgi?type=1&host=',
                };

            return commands[type] + host || '#';
        };
    })

    .filter('servicestate', function() {
        return function(input) {
            var lookup = {
                    '0': 'Ok',
                    '1': 'Warning',
                    '2': 'Critical',
                    '3': 'Unknown',
                    '4': 'Pending',
                    '5': 'Problems',
                    '6': 'Unhandled',
                    '7': 'Acknowledged'
                };

            return lookup[input] || 'Undefined';
        };
    })
