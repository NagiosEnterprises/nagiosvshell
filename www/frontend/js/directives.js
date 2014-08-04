'use strict';

angular.module('vshell2.directives', [])

    .directive('activeNav', ['$location', function(location) {

        return function(scope, element, attrs) {

            var starts_with = function(path, match){
                return path && match && path.indexOf(match) === 0;
            }

            scope.$on('$locationChangeSuccess', function(event) {
                var anchors = element.find('a'),
                    path = location.path();

                $.each(anchors, function(){
                    var anchor = $(this),
                        match = anchor.attr('active-when');
                     
                    if( match && starts_with(path, match) ){
                        anchor.addClass('active');
                    } else {
                        anchor.removeClass('active');
                    }
                });
            });

        };

    }])

    .directive('footabledata', function(){

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

        return function(scope, element, attrs) {
            var footableTable = element.parents('table');

            if( !scope.$last ) {
                return false;
            }

            scope.$evalAsync(function(){

                if (! footableTable.hasClass('footable-loaded')) {
                    footableTable.footable();
                }

                footableTable.trigger('footable_initialized');
                footableTable.trigger('footable_resize');
                footableTable.data('footable').redraw();

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
