'use strict';

angular.module('vshell.directives', [])

.directive('activeNav', ['$location',
    function($location) {

        // Assigns active navigation class based on $location changes
        //
        // Used as an attribute on the parent container, <nav active-nav>
        //
        // When location changes each child anchor element is checked against
        // the new location value.
        //
        // Anchors declare what to match against using the active-when attribute,
        // <a href="#/some-page" active-when="/some-page">. Multiple matches can
        // be given using a separator (default is ';').
        //
        // Matches are made against the beginning of the path, and are done
        // with indexOf() instead of a regex for speed.

        return function(scope, element, attrs) {

            var child_attribute = 'active-when',
                active_class = 'active',
                match_separator = ';';

            function starts_with(path, match) {
                return path && match && path.indexOf(match) === 0;
            }

            scope.$on('$locationChangeSuccess', function(event) {
                var anchors = element.find('a'),
                    path = $location.path();

                anchors.each(function(index) {
                    var anchor = $(this),
                        when = anchor.attr(child_attribute),
                        matches;

                    anchor.removeClass(active_class);

                    if (!when) {
                        return false;
                    }

                    matches = when.split(match_separator);

                    $.each(matches, function(index, match) {
                        if (match && starts_with(path, match)) {
                            anchor.addClass(active_class);
                        }
                    });
                });
            });

        };

    }
])

.directive('footabledata', function() {

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

        if (!scope.$last) {
            return false;
        }

        scope.$evalAsync(function() {

            if (!footableTable.hasClass('footable-loaded')) {
                footableTable.footable();
            }

            footableTable.trigger('footable_initialized');
            footableTable.trigger('footable_resize');
            footableTable.data('footable').redraw();

        });
    };

})

.directive('hostEntry', function() {

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
        link: function(scope, element, attrs) {

        },
        templateUrl: 'frontend/partials/directives/host-entry.html'
    };

})

.directive('serviceEntry', function() {

    return {
        restrict: 'A',
        replace: true,
        scope: {
            item: '='
        },
        link: function(scope, element, attrs) {

        },
        templateUrl: 'frontend/partials/directives/service-entry.html'
    };

});
