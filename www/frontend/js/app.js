'use strict';

angular.module('vshell2', [
  'ngRoute',
  'vshell2.filters',
  'vshell2.services',
  'vshell2.directives',
  'vshell2.controllers'
])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/overview', {templateUrl: 'frontend/partials/overview.html'});
        $routeProvider.when('/hosts', {templateUrl: 'frontend/partials/hosts.html'});
        $routeProvider.when('/hosts/state/:state', {templateUrl: 'frontend/partials/hosts.html'});
        $routeProvider.when('/hosts/:host', {templateUrl: 'frontend/partials/hostdetails.html'});
        $routeProvider.when('/hostgroups', {templateUrl: 'frontend/partials/hostgroups-summary.html'});
        $routeProvider.when('/hostgroups/grid', {templateUrl: 'frontend/partials/hostgroups-grid.html'});
        $routeProvider.when('/hostgroups/:group', {templateUrl: 'frontend/partials/hostgroups-details.html'});
        $routeProvider.when('/services', {templateUrl: 'frontend/partials/services.html'});
        $routeProvider.when('/services/state/:state', {templateUrl: 'frontend/partials/services.html'});
        $routeProvider.when('/services/on-host/:host', {templateUrl: 'frontend/partials/hostservices.html'});
        $routeProvider.when('/services/:host/:service', {templateUrl: 'frontend/partials/servicedetails.html'});
        $routeProvider.when('/servicegroups/', {templateUrl: 'frontend/partials/servicegroups-summary.html'});
        $routeProvider.when('/servicegroups/grid', {templateUrl: 'frontend/partials/servicegroups-grid.html'});
        $routeProvider.when('/servicegroups/:group', {templateUrl: 'frontend/partials/servicegroups-details.html'});
        $routeProvider.when('/configurations', {templateUrl: 'frontend/partials/configurations.html'});
        $routeProvider.when('/options', {templateUrl: 'frontend/partials/options.html'});
        $routeProvider.otherwise({redirectTo: '/overview'});
    }])
