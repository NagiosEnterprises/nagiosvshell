'use strict';

angular.module('vshell2.controllers', [])

    .controller('QuicksearchCtrl', ['$scope', '$http', '$location', '$filter', function ($scope, $http, $location, $filter) {

        var callback = function(e, item){
            var base = $filter('uri')(item.type),
                path = base + '/' + item.uri;
            $location.path(path);
            $scope.$apply();
        }

        $scope.getQuicksearchData = function () {

            $http({ method: 'GET', url: '/vshell2/api/quicksearch' })
                .success(function(data, status, headers, config) {
                    quicksearch.init(data, callback);
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Quicksearch data from the V-Shell2 API');
                });

        };

    }])

    .controller('StatusCtrl', ['$scope', '$http', function ($scope, $http) {

        $scope.getStatus = function () {

            $scope.status = [];

            $http({ method: 'GET', url: '/vshell2/api/status' })
                .success(function(data, status, headers, config) {
                    $scope.status = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostStatusCtrl', ['$scope', '$http', '$routeParams', '$filter', function ($scope, $http, $routeParams, $filter) {

        $scope.getHostStatus = function () {

            $scope.is_loading = true;
            $scope.hoststatus = [];
            $scope.statefilter = $routeParams.state || '';

            $http({ method: 'GET', url: '/vshell2/api/hoststatus' })
                .success(function(data, status, headers, config) {
                    data = $filter('by_state')(data, 'host', $scope.statefilter);
                    $scope.is_loading = false;
                    $scope.hoststatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Host Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostStatusDetailsCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $scope.getHostStatusDetails = function () {

            $scope.host = [];

            $http({ method: 'GET', url: '/vshell2/api/hoststatus/' + $routeParams.host })
                .success(function(data, status, headers, config) {
                    if( data[0] ){
                        data = data[0];
                    }
                    $scope.host = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Host Detail information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostgroupStatusCtrl', ['$scope', '$http', function ($scope, $http) {

        $scope.getHostgroupStatus = function () {

            $scope.is_loading = true;
            $scope.hostgroupstatus = [];

            $http({ method: 'GET', url: '/vshell2/api/hostgroupstatus' })
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    $scope.hostgroupstatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Host Group Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostgroupStatusDetailsCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $scope.getHostgroupStatusDetails = function () {

            $scope.is_loading = true;
            $scope.hostgroup = [];

            $http({ method: 'GET', url: '/vshell2/api/hostgroupstatus/' + $routeParams.group })
                .success(function(data, status, headers, config) {
                    if( data[0] ){
                        data = data[0];
                    }
                    $scope.is_loading = false;
                    $scope.hostgroup = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Host Group Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServiceHostStatusCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $scope.getServiceHostStatus = function () {

            $scope.host_name = $routeParams.host;

            $scope.is_loading = true;
            $scope.servicestatus = [];

            $http({ method: 'GET', url: '/vshell2/api/servicestatus/' + $routeParams.host })
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    $scope.servicestatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Service Host Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServiceStatusCtrl', ['$scope', '$http', '$routeParams', '$filter', function ($scope, $http, $routeParams, $filter) {

        $scope.getServiceStatus = function () {

            $scope.is_loading = true;
            $scope.servicestatus = [];
            $scope.statefilter = $routeParams.state;

            $http({ method: 'GET', url: '/vshell2/api/servicestatus' })
                .success(function(data, status, headers, config) {
                    data = $filter('by_state')(data, 'service', $scope.statefilter);
                    $scope.is_loading = false;
                    $scope.servicestatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Service Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServiceStatusDetailsCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $scope.getServiceStatusDetails = function () {

            $scope.service = [];

            $http({ method: 'GET', url: '/vshell2/api/servicestatus/' + $routeParams.host + '/' + $routeParams.service })
                .success(function(data, status, headers, config) {
                    if( data[$routeParams.service] ){
                        data = data[$routeParams.service];
                    }
                    $scope.service = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Service Detail information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServicegroupStatusCtrl', ['$scope', '$http', function ($scope, $http) {

        $scope.getServicegroupStatus = function () {

            $scope.is_loading = true;
            $scope.servicegroupstatus = [];

            $http({ method: 'GET', url: '/vshell2/api/servicegroupstatus' })
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    $scope.servicegroupstatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Service Group Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServicegroupStatusDetailsCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $scope.getServicegroupStatusDetails = function () {

            $scope.is_loading = true;
            $scope.servicegroup = [];

            $http({ method: 'GET', url: '/vshell2/api/servicegroupstatus/' + $routeParams.group })
                .success(function(data, status, headers, config) {
                    if( data[0] ){
                        data = data[0];
                    }
                    $scope.is_loading = false;
                    $scope.servicegroup = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Service Group Status information from the V-Shell2 API');
                });

        };

    }])
