'use strict';

describe('directives', function() {

    var scope, $compile, $location, element, compiled;

    beforeEach(function() {
        module('vshell.directives');
        module('frontend/partials/directives/host-entry.html');
        module('frontend/partials/directives/service-entry.html');
        inject(function($rootScope, _$location_, _$compile_) {
            scope = $rootScope.$new();
            $location = _$location_;
            $compile = _$compile_;
        });
    });

    function createItems() {
        $compile(element)(scope);
        scope.$digest();
        return element;
    }

    describe('activeNav', function() {

        beforeEach(inject(function () {

            element = angular.element(
                '<nav active-nav>' +
                '<ul>' +
                '<li><a href="#/first" active-when="/first"></a></li>' +
                '<li><a href="#/second" active-when="/second"></a></li>' +
                '<li><a href="#/third" active-when="/third"></a></li>' +
                '</ul>' +
                '</nav>'
            );

            compiled = createItems();

        }));

        it('Should be triggered by test event', function () {
            scope.$broadcast('$locationChangeSuccess');
            expect(scope.activeNavUpdated).toBe(true);
        });

        it('Should have no active links when path does not match', function () {
            $location.path('/no-matches');
            scope.$broadcast('$locationChangeSuccess');

            var anchors = compiled.find('a'),
                active = anchors.filter('.active');

            expect(anchors.length).toBe(3);
            expect(active.length).toBe(0);
        });

        it('Should have one active link when path matches active-when value', function () {
            $location.path('/third');
            scope.$broadcast('$locationChangeSuccess');

            var anchors = compiled.find('a'),
                anchor = anchors.filter('[href="#/third"]');

            expect(anchor.hasClass('active')).toBe(true);
        });

    });

    describe('hostEntry', function() {

        beforeEach(inject(function () {

            element = angular.element(
                '<container>' +
                '<item ng-repeat="item in items" host-entry></item>' +
                '</container>'
            );

            scope.items = [
                {
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': false,
                    'is_flapping': false
                },
                {
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': true,
                    'is_flapping': false
                },
                {
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': true,
                    'is_flapping': true
                }
            ];

            compiled = createItems();

        }));

        it('Should create correct number of test items', function () {
            var items = compiled.find('td');
            expect(items.length).toBe(3);
        });

    });

    describe('serviceEntry', function() {

        beforeEach(inject(function () {

            element = angular.element(
                '<container>' +
                '<item ng-repeat="item in items" service-entry></item>' +
                '</container>'
            );

            scope.items = [
                {
                    'service_description': 'unit-test-service',
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': false,
                    'is_flapping': false
                },
                {
                    'service_description': 'unit-test-service-acknowledged',
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': true,
                    'is_flapping': false
                },
                {
                    'service_description': 'unit-test-service-flapping',
                    'host_name': 'unit-test.hostname',
                    'problem_has_been_acknowledged': true,
                    'is_flapping': true
                }
            ];

            compiled = createItems();

        }));

        it('Should create correct number of test items', function () {
            var items = compiled.find('td');
            expect(items.length).toBe(3);
        });

    });

});
