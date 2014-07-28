'use strict';

angular.module('vshell2.directives', [])

    .directive('footabledata', function(){

        // Fix footable initiation to happen after AngularJS finishes loading
        // asynchronous API data.
        //
        // See Daniel Stucki's excellent StackOverflow answer:
        // http://stackoverflow.com/a/21057869/657661
        //
        // Also see footable API docs:
        // http://fooplugins.com/footable/demos/api.htm#docs

        return function(scope, element) {
            var footableTable = element.parents('table'),
                footableObject;

            if (scope.$last && ! footableTable.hasClass('footable-loaded')) {
                footableTable.footable();
            };

            footableObject = footableTable.data('footable');

            if (footableObject !== undefined) {
                footableObject.appendRow(element);
            };
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
                console.log(scope.item);
            },
            templateUrl: 'frontend/partials/directives/service-entry.html',
        };

    })
