'use strict';

describe('controllers', function(){

    beforeEach(function(){
        module('vshell.controllers');
        module('vshell.filters');
        module('vshell.services');
    });


    // Page

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('PageCtrl', { $scope: {}, async: {}, paths: {} });

        expect( ctrl ).toBeDefined();
    }));


    // Quicksearch

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('QuicksearchCtrl', { $scope: {}, $location: {}, $filter: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, $location, $filter, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('QuicksearchCtrl', { $scope: scope, $location: {}, $filter: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, $location, $filter, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('QuicksearchCtrl', { $scope: scope, $location: $location, $filter: $filter, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Status

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('StatusCtrl', { $scope: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller) {
        var scope = $rootScope.$new(),
            ctrl = $controller('StatusCtrl', { $scope: scope, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('StatusCtrl', { $scope: scope, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Overview

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('OverviewCtrl', { $scope: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller) {
        var scope = $rootScope.$new(),
            ctrl = $controller('OverviewCtrl', { $scope: scope, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('OverviewCtrl', { $scope: scope, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Host Status

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('HostStatusCtrl', { $scope: {}, $routeParams: {}, $filter: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('HostStatusCtrl', { $scope: scope, $routeParams: {}, $filter: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('HostStatusCtrl', { $scope: scope, $routeParams: {}, $filter: $filter, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Host Status Details

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('HostStatusDetailsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('HostStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('HostStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Host Group Status

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('HostgroupStatusCtrl', { $scope: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller) {
        var scope = $rootScope.$new(),
            ctrl = $controller('HostgroupStatusCtrl', { $scope: scope, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('HostgroupStatusCtrl', { $scope: scope, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Host Group Status Details

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('HostgroupStatusDetailsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('HostgroupStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('HostgroupStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Services

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ServiceStatusCtrl', { $scope: {}, $routeParams: {}, $filter: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ServiceStatusCtrl', { $scope: scope, $routeParams: {}, $filter: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ServiceStatusCtrl', { $scope: scope, $routeParams: {}, $filter: $filter, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Services by Host

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ServiceHostStatusCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ServiceHostStatusCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ServiceHostStatusCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Service Status Details

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ServiceStatusDetailsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ServiceStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ServiceStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Service Group Status

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ServicegroupStatusCtrl', { $scope: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ServicegroupStatusCtrl', { $scope: scope, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ServicegroupStatusCtrl', { $scope: scope, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Service Group Status Details

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ServicegroupStatusDetailsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ServicegroupStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ServicegroupStatusDetailsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));

    
    // Configurations

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('ConfigurationsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ConfigurationsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ConfigurationsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Configuration Details

    it('Should be defined', inject(function($controller, $filter) {
        var ctrl = $controller('ConfigurationDetailsCtrl', { $scope: {}, $routeParams: {}, $filter: $filter, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('ConfigurationDetailsCtrl', { $scope: scope, $routeParams: {}, $filter: $filter, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, $filter, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('ConfigurationDetailsCtrl', { $scope: scope, $routeParams: {}, $filter: $filter, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));


    // Comments

    it('Should be defined', inject(function($controller) {
        var ctrl = $controller('CommentsCtrl', { $scope: {}, $routeParams: {}, async: {} });

        expect( ctrl ).toBeDefined();
    }));

    it('Should have an init method', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            ctrl = $controller('CommentsCtrl', { $scope: scope, $routeParams: {}, async: {} });

        expect( scope.init ).toBeDefined();
    }));

    it('Should call async service', inject(function($rootScope, $controller, async) {
        var scope = $rootScope.$new(),
            mockAsync = mockService(async),
            ctrl = $controller('CommentsCtrl', { $scope: scope, $routeParams: {}, async: mockAsync });

        scope.init();

        expect( mockAsync.api ).toHaveBeenCalled();
    }));

});
