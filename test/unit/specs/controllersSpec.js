'use strict';

describe('Controllers', function() {

    var $filter, scope, ctrl, mockAsync;

    beforeEach(function() {
        module('vshell.controllers');
        module('vshell.filters');
        module('vshell.services');
    });

    beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
        scope = $rootScope.$new();
        mockAsync = mockService(async);
    }));

    describe('Page', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('PageCtrl', {
                $scope: scope,
                async: mockAsync,
                paths: {}
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

    });

    describe('Quicksearch', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('QuicksearchCtrl', {
                $scope: scope,
                $location: {},
                $filter: $filter,
                async: mockAsync
            });
        }));

        it('Should be defined', function() {
            expect(ctrl).toBeDefined();
        });

        it('Should have an init method', function() {
            expect(scope.init).toBeDefined();
        });

        it('Should call async service', function() {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        });

    });

    describe('Status', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('StatusCtrl', {
                $scope: scope,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Overview', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('OverviewCtrl', {
                $scope: scope,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Host Status', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('HostStatusCtrl', {
                $scope: scope,
                $routeParams: {},
                $filter: $filter,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Host Status Details', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('HostStatusDetailsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Host Group Status', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('HostgroupStatusCtrl', {
                $scope: scope,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Host Group Status Details', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('HostgroupStatusDetailsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Service Status', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ServiceStatusCtrl', {
                $scope: scope,
                $routeParams: {},
                $filter: $filter,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Services by Host', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ServiceHostStatusCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Service Status Details', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ServiceStatusDetailsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Service Group Status', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ServicegroupStatusCtrl', {
                $scope: scope,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Service Group Status Details', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ServicegroupStatusDetailsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Configurations', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ConfigurationsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Configuration Details', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('ConfigurationDetailsCtrl', {
                $scope: scope,
                $routeParams: {},
                $filter: $filter,
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller, $filter) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

    describe('Comments', function() {

        beforeEach(inject(function($rootScope, $controller, $location, $filter, async, paths) {
            ctrl = $controller('CommentsCtrl', {
                $scope: scope,
                $routeParams: {},
                async: mockAsync
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

        it('Should call async service', inject(function($rootScope, $controller, async) {
            scope.init();
            expect(mockAsync.api).toHaveBeenCalled();
        }));

    });

});
