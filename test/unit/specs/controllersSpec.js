'use strict';

describe('controllers', function(){

    beforeEach(function(){
        module('vshell.controllers');
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


    // HostStatus

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

});
