'use strict';

angular.module('vshell2.filters', [])

    .filter('capitalize', function() {
        return function(input) {
            var first, rest;
            if( ! input ){ return ''; }
            first = input.substring(0,1).toUpperCase(),
            rest = input.substring(1).toLowerCase();
            return first + rest;
        }
    })

    .filter('size', function() {
        return function(input) {
            var type = typeof input;
            return type == "object" ? _.size(input) : 0;
        };
    })

    .filter('plural', function() {
        return function(input) {
            var size = _.size(input);
            return size == 1 ? '' : 's';
        };
    })

    .filter('percent', function(numberFilter) {
        return function(input, total, places) {
            var places = places || 1,
                fraction = parseInt(input, 10) / parseInt(total, 10),
                percent = fraction * 100;

            if( percent == 0 || percent == 100 ){
                return percent + '%';
            } else {
                return numberFilter(percent, places) + '%';
            }
        };
    })

    .filter('object_to_array', function() {
        return function(input) {
            var i, arr = [];
            for(i in input){
                arr.push(input[i]);
            }
            return arr;
        }
    })

    .filter('property', function() {
        // Filter on Deep Object Property
        // By: Anton Kropp
        // See: http://onoffswitch.net/filter-deep-object-properties-angularjs/
        // Dependencies: underscore.js

        function parseString(input){
            return input.split(".");
        };

        function getValue(element, propertyArray){
            var value = element;

            _.forEach(propertyArray, function(property){
                value = value[property];
            });

            return value;
        };

        return function (input, propertyString, target){
            var properties = parseString(propertyString);

            return _.filter(input, function(item){
                return getValue(item, properties) == target;
            });
        };
    })

    .filter('uri', function() {
        // Decouples type (hosts) from URI path (/hosts)
        return function(input) {
            if( input == 'host' ){ return 'hosts'; }
            if( input == 'service' ){ return 'services'; }
            if( input == 'hostgroup' ){ return 'hostgroups'; }
            if( input == 'servicegroup' ){ return 'servicegroups'; }
            if( input == 'configuration' ){ return 'configurations'; }
            return 'overview';
        };
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

    .filter('check_type', function() {
        return function(input) {
            var as_int = parseInt(input, 10);

            if( as_int > 1 ) {
                return 'Unknown';
            }

            return as_int === 0 ? 'Active' : 'Passive';
        };
    })

    .filter('state_type', function() {
        return function(input) {
            var as_int = parseInt(input, 10);

            if( as_int > 1 ) {
                return 'Unknown';
            }

            return as_int === 0 ? 'Soft' : 'Hard';
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
        return function(input, reverse) {
            var lookup = {
                    '0': 'Up',
                    '1': 'Down',
                    '2': 'Unreachable',
                    '3': 'Pending',
                    '4': 'Problems',
                    '5': 'Unhandled',
                    '6': 'Acknowledged'
                };

            if( reverse ){
                lookup = _.invert(lookup);
            }

            return lookup[input] || 'Undefined';
        };
    })

    .filter('hostcommand', function() {
        return function(input, type) {
            var commands = {
                    'acknowledge_problem': 'cgi-bin/cmd.cgi?cmd_typ=33&host=',
                    'active_checks': 'cgi-bin/cmd.cgi?cmd_typ=48&host=',
                    'comment_add': 'cgi-bin/cmd.cgi?cmd_typ=1&host=',
                    'comment_delete': 'cgi-bin/cmd.cgi?cmd_typ=2&com_id=',
                    'custom_notification': 'cgi-bin/cmd.cgi?cmd_typ=159&host=',
                    'flap_detection': 'cgi-bin/cmd.cgi?cmd_typ=58&host=',
                    'host_in_nagios_core': 'cgi-bin/extinfo.cgi?type=1&host=',
                    'map_host': 'cgi-bin/statusmap.cgi?host=',
                    'notifications': 'cgi-bin/cmd.cgi?cmd_typ=25&host=',
                    'obsession': 'cgi-bin/cmd.cgi?cmd_typ=102&host=',
                    'passive_checks': 'cgi-bin/cmd.cgi?cmd_typ=39&host=',
                    'schedule_check_for_all': 'cgi-bin/cmd.cgi?cmd_typ=17&host=',
                    'schedule_downtime': 'cgi-bin/cmd.cgi?cmd_typ=55&host=',
                    'schedule_downtime_for_all': 'cgi-bin/cmd.cgi?cmd_typ=86&host=',
                },
                core = paths.core_for_filters.value;

            return commands[type] ? core + commands[type] + input : '#';
        };
    })

    .filter('servicestate', function() {
        return function(input, reverse) {
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

            if( reverse ){
                lookup = _.invert(lookup);
            }

            return lookup[input] || 'Undefined';
        };
    })

    .filter('servicecommand', function(paths) {
        return function(host, service, type) {
            var commands = {
                    'acknowledge_problem': 'cgi-bin/cmd.cgi?cmd_typ=34&host=' + host + '&service=' + service,
                    'active_checks': 'cgi-bin/cmd.cgi?cmd_typ=6&host=' + host + '&service=' + service,
                    'comment_add': 'cgi-bin/cmd.cgi?cmd_typ=3&host=' + host + '&service=' + service,
                    'comment_delete': 'cgi-bin/cmd.cgi?cmd_typ=4&com_id=' + host,
                    'custom_notification': 'cgi-bin/cmd.cgi?cmd_typ=160&host=' + host + '&service=' + service,
                    'flap_detection': 'cgi-bin/cmd.cgi?cmd_typ=60&host=' + host + '&service=' + service,
                    'notifications': 'cgi-bin/cmd.cgi?cmd_typ=23&host=' + host + '&service=' + service,
                    'obsession': 'cgi-bin/cmd.cgi?cmd_typ=100&host=' + host + '&service=' + service,
                    'passive_checks': 'cgi-bin/cmd.cgi?cmd_typ=40&host=' + host + '&service=' + service,
                    'schedule_check': 'cgi-bin/cmd.cgi?cmd_typ=7&host=' + host + '&service=' + service,
                    'schedule_downtime': 'cgi-bin/cmd.cgi?cmd_typ=56&host=' + host + '&service=' + service,
                },
                core = paths.core_for_filters.value;

            return commands[type] ? core + commands[type] : '#';
        };
    })

    .filter('overviewstate', function() {
        return function(input) {
            if( ! input ) {
                return 'undefined';
            }

            if( input.hostsDownTotal > 0 || input.servicesCriticalTotal > 0 ){
                return 'critical';
            } else if( input.hostsUnreachableTotal > 0 || input.servicesUnknownTotal > 0 ){
                return 'unknown';
            } else if( input.servicesWarningTotal > 0 ){
               return 'warning';
            } else if( input.hostsPendingTotal > 0 || input.servicesPendingTotal > 0 ){
               return 'pending';
            } else if( input.hostsUpTotal > 0 || input.servicesOkTotal > 0 ){
               return 'ok';
            }
            return 'undefined';
        };
    })

    .filter('groupstate', function() {
        return function(input) {
            if( ! input ) {
                return 'undefined';
            }

            if( input.hostsDown > 0 || input.servicesCritical > 0 ){
                return 'critical';
            } else if( input.hostsUnreachable > 0 || input.servicesUnknown > 0 ){
                return 'unknown';
            } else if( input.servicesWarning > 0 ){
               return 'warning';
            } else if( input.hostsPending > 0 || input.servicesPending > 0 ){
               return 'pending';
            } else if( input.hostsUp > 0 || input.servicesOk > 0 ){
               return 'ok';
            }
            return 'undefined';
        };
    })

    .filter('configuration_anchor_key', function() {
        return function(input) {
            var lookup = {
                'commands': 'command_name',
                'contactgroups': 'contactgroup_name',
                'contacts': 'contact_name',
                'hostgroups': 'hostgroup_name',
                'hosts': 'host_name',
                'servicegroups': 'servicegroup_name',
                'services': 'service_description',
                'timeperiods': 'name'
            };

            return lookup[input] || 'undefined';
        };
    })

    .filter('configuration_display_key', function() {
        return function(input) {
            var lookup = {
                'commands': 'name',
                'contactgroups': 'alias',
                'contacts': 'alias',
                'hostgroups': 'alias',
                'hosts': 'host_name',
                'servicegroups': 'alias',
                'services': 'service_description',
                'timeperiods': 'alias'
            };

            return lookup[input] || 'undefined';
        };
    })

    .filter('by_state', function(capitalizeFilter, hoststateFilter, servicestateFilter, propertyFilter) {
        // Filter table results by each objects current_state value
        return function(input, type, filter) {
            var state = filter,
                state_capitalized, state_id_lookup, state_id;

            if( state ){
                state_capitalized = capitalizeFilter(state);
                state_id_lookup = (type == 'host') ? hoststateFilter : servicestateFilter;
                state_id = state_id_lookup(state_capitalized, 'reverse-lookup');
                input = propertyFilter(input, 'current_state', state_id);
            }

            return input;
        };
    })
