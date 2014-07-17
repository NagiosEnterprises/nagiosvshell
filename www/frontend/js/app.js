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
        $routeProvider.when('/hostgroups', {templateUrl: 'frontend/partials/hostgroups.html'});
        $routeProvider.otherwise({redirectTo: '/overview'});
    }])
