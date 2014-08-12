'use strict';

describe('Controllers', function() {

    var $filter, scope, ctrl, mockAsync, mockPaths;

    beforeEach(function() {
        module('vshell.controllers');
        module('vshell.filters');
        module('vshell.services');
    });

    beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
        scope = $rootScope.$new();
        mockAsync = mockService(async);
        mockPaths = mockService(paths);
    }));

    describe('Page', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

    describe('Hosts', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('HostsCtrl', {
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

    describe('Host Details', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('HostDetailsCtrl', {
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

    describe('Host Groups', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('HostGroupsCtrl', {
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

    describe('Host Group Details', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('HostGroupDetailsCtrl', {
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

    describe('Host Services', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('HostServicesCtrl', {
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

    describe('Services', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('ServicesCtrl', {
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

    describe('Service Details', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('ServiceDetailsCtrl', {
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

    describe('Service Groups', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('ServiceGroupsCtrl', {
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

    describe('Service Group Details', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('ServiceGroupDetailsCtrl', {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
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

    describe('Options', function() {

        beforeEach(inject(function($rootScope, $controller, $httpBackend, $location, $filter, async, paths) {
            ctrl = $controller('OptionsCtrl', {
                $scope: scope,
                $http: $httpBackend,
                paths: mockPaths
            });
        }));

        it('Should be defined', inject(function($controller) {
            expect(ctrl).toBeDefined();
        }));

        it('Should have an init method', inject(function($rootScope, $controller, async) {
            expect(scope.init).toBeDefined();
        }));

    });

});
