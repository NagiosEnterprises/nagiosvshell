'use strict';

angular.module('vshell2.directives', [])

    .directive('footabledata', function($timeout){

        // Fix footable initiation to happen after AngularJS finishes loading
        // asynchronous API data.
        //
        // Daniel Stucki's StackOverflow answer was a great starting point.
        // But instead of adding each row individually, it has been
        // modified to wait until ng-repeat is finished, then call footable
        // on the entire table.
        // http://stackoverflow.com/a/21057869/657661
        //
        // Also see footable API docs:
        // http://fooplugins.com/footable/demos/api.htm#docs

        var init_cooldown_is_over = function(ft, id){
            var last_init_id = ft.data('last_init_id'),
                is_initial_request = last_init_id == id,
                last_init_time = ft.data('last_init_time'),
                cooldown = 900,
                time_difference;

            if( is_initial_request ) {
                return false; 
            }

            time_difference = Date.now() - last_init_time;

            return time_difference > cooldown;
        };

        return function(scope, element) {
            var footableTable = element.parents('table');

            if( !scope.$last ) {
                return false;
            }

            $timeout(function () {

                if (! footableTable.hasClass('footable-loaded')) {
                    footableTable
                        .footable({
                            memory: {
                                enabled: true
                            }
                        })
                        .data('last_init_time', Date.now())
                        .data('last_init_id', scope.$id);
                }

                if( init_cooldown_is_over(footableTable, scope.$id) ){
                    footableTable.trigger('footable_initialized');
                    footableTable.data('footable').redraw();
                }

            });
        };

    })

    .directive('hostEntry', function(){

        // Accepts both host and service iterables.
        //
        // Since both types have overlapping attribute names, the template
        // has a secondary check if it is a host or service type.
        //
        // This makes sure icons, like flapping, are displayed correctly.
        // Without the extra check icons caused by the service state would
        // incorrectly get added to the host display.

        return {
            restrict: 'A',
            replace: true,
            scope: {
                item: '='
            },
            link: function (scope, element, attrs) {

            },
            templateUrl: 'frontend/partials/directives/host-entry.html',
        };

    })

    .directive('serviceEntry', function(){

        return {
            restrict: 'A',
            replace: true,
            scope: {
                item: '='
            },
            link: function (scope, element, attrs) {

            },
            templateUrl: 'frontend/partials/directives/service-entry.html',
        };

    })
