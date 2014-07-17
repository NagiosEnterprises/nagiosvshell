'use strict';

angular.module('vshell2.filters', [])

    .filter('is_active', function() {
        return function(input) {
            var as_int = parseInt(input, 10);
            return as_int > 0 ? 'active' : '';
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

            return lookup[input];
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

            return lookup[input];
        };
    })
