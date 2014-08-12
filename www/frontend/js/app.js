'use strict';

angular.module('vshell', [
    'ngRoute',
    'vshell.filters',
    'vshell.services',
    'vshell.directives',
    'vshell.controllers'
])

.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.when('/overview', {
            templateUrl: 'frontend/partials/overview.html'
        });
        $routeProvider.when('/hosts', {
            templateUrl: 'frontend/partials/hosts.html'
        });
        $routeProvider.when('/hosts/state/:state', {
            templateUrl: 'frontend/partials/hosts.html'
        });
        $routeProvider.when('/hosts/problems/:handled', {
            templateUrl: 'frontend/partials/hosts.html'
        });
        $routeProvider.when('/hosts/:host*', {
            templateUrl: 'frontend/partials/host-details.html'
        });
        $routeProvider.when('/hostgroups/summary', {
            templateUrl: 'frontend/partials/host-groups-summary.html'
        });
        $routeProvider.when('/hostgroups/grid', {
            templateUrl: 'frontend/partials/host-groups-grid.html'
        });
        $routeProvider.when('/hostgroups/:group*', {
            templateUrl: 'frontend/partials/host-group-details.html'
        });
        $routeProvider.when('/services', {
            templateUrl: 'frontend/partials/services.html'
        });
        $routeProvider.when('/services/state/:state', {
            templateUrl: 'frontend/partials/services.html'
        });
        $routeProvider.when('/services/problems/:handled', {
            templateUrl: 'frontend/partials/services.html'
        });
        $routeProvider.when('/services/on-host/:host', {
            templateUrl: 'frontend/partials/host-services.html'
        });
        $routeProvider.when('/services/:host/:service*', {
            templateUrl: 'frontend/partials/service-details.html'
        });
        $routeProvider.when('/servicegroups/summary', {
            templateUrl: 'frontend/partials/service-groups-summary.html'
        });
        $routeProvider.when('/servicegroups/grid', {
            templateUrl: 'frontend/partials/service-groups-grid.html'
        });
        $routeProvider.when('/servicegroups/:group*', {
            templateUrl: 'frontend/partials/service-group-details.html'
        });
        $routeProvider.when('/configurations', {
            templateUrl: 'frontend/partials/configurations-grid.html'
        });
        $routeProvider.when('/configurations/:type', {
            templateUrl: 'frontend/partials/configurations.html'
        });
        $routeProvider.when('/configurations/:type/:name*', {
            templateUrl: 'frontend/partials/configuration-details.html'
        });
        $routeProvider.when('/comments', {
            templateUrl: 'frontend/partials/comments.html'
        });
        $routeProvider.when('/options', {
            templateUrl: 'frontend/partials/options.html'
        });
        $routeProvider.otherwise({
            redirectTo: '/overview'
        });
    }
]);
