'use strict';

angular.module('vshell2.filters', [])

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

    .filter('is_active', function() {
        return function(input) {
            as_int = parseInt(input, 10);
            return as_int > 0 ? 'active' : '';
        };
    })
