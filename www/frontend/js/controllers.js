'use strict';

angular.module('vshell2.controllers', [])

    .controller('PageCtrl', ['$scope', 'async', 'paths', function ($scope, async, paths) {

        $scope.initPage = function () {

            paths.core_as_promise.then(function(value) {
                $scope.nagios_core = value;
            });

        };

    }])

    .controller('QuicksearchCtrl', ['$scope', '$location', '$filter', 'async', function ($scope, $location, $filter, async) {

        var callback = function(data, status, headers, config){
                var quicksearch_callback = function(e, item){
                    var base = $filter('uri')(item.type),
                        path = base + '/' + item.uri;
                    $location.path(path);
                    $scope.$apply();
                }

                quicksearch.init(data, quicksearch_callback);

                return data;
            }

        $scope.getQuicksearchData = function () {

            var options = {
                name: 'quicksearch',
                url: 'quicksearch',
                callback: callback
            };

            async.api($scope, options);

        };

    }])

    .controller('OverviewCtrl', ['$scope', 'async', function ($scope, async) {
    
        $scope.getOverview = function () {

            var options = {
                name: 'overview',
                url: 'overview',
            };

            async.api($scope, options);

        }

    }])

    .controller('StatusCtrl', ['$scope', 'async', function ($scope, async) {

        $scope.getStatus = function () {

            var options = {
                name: 'status',
                url: 'status',
            };

            async.api($scope, options);

        };

    }])

    .controller('HostStatusCtrl', ['$scope', '$routeParams', '$filter', 'async', function ($scope, $routeParams, $filter, async) {

        var callback = function(data, status, headers, config){
                var filter = $routeParams.state || '';
                return $filter('by_state')(data, 'host', filter);
            }

        $scope.getHostStatus = function () {

            var options = {
                name: 'hoststatus',
                url: 'hoststatus',
                callback: callback,
            };

            async.api($scope, options);

        };

    }])

    .controller('HostStatusDetailsCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        $scope.getHostStatusDetails = function () {

            var options = {
                name: 'host',
                url: 'hoststatus/' + $routeParams.host,
            };

            async.api($scope, options);

        };

    }])

    .controller('HostgroupStatusCtrl', ['$scope', 'async', function ($scope, async) {

        $scope.getHostgroupStatus = function () {

            var options = {
                name: 'hostgroupstatus',
                url: 'hostgroupstatus',
            };

            async.api($scope, options);

        };

    }])

    .controller('HostgroupStatusDetailsCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        var callback = function(data, status, headers, config){
                return (data && data[0]) ? data[0] : data; 
            }

        $scope.getHostgroupStatusDetails = function () {

            var options = {
                name: 'hostgroup',
                url: 'hostgroupstatus/' + $routeParams.group,
                callback: callback,
            };

            async.api($scope, options);

        };

    }])

    .controller('ServiceHostStatusCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        $scope.getServiceHostStatus = function () {

            var options = {
                    name: 'servicestatus',
                    url: 'servicestatus/' + $routeParams.host,
                };

            $scope.host_name = $routeParams.host;

            async.api($scope, options);

        };

    }])

    .controller('ServiceStatusCtrl', ['$scope', '$routeParams', '$filter', 'async', function ($scope, $routeParams, $filter, async) {

        var callback = function(data, status, headers, config){
                return $filter('by_state')(data, 'service', $routeParams.state);
            }

        $scope.getServiceStatus = function () {

            var options = {
                    name: 'servicestatus',
                    url: 'servicestatus',
                    callback: callback,
                };

            async.api($scope, options);

        };

    }])

    .controller('ServiceStatusDetailsCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        $scope.getServiceStatusDetails = function () {

            var options = {
                    name: 'service',
                    url: 'servicestatus/' + $routeParams.host + '/' + $routeParams.service,
                };

            async.api($scope, options);

        };

    }])

    .controller('ServicegroupStatusCtrl', ['$scope', 'async', function ($scope, async) {

        $scope.getServicegroupStatus = function () {

            var options = {
                    name: 'servicegroupstatus',
                    url: 'servicegroupstatus',
                };

            async.api($scope, options);

        };

    }])

    .controller('ServicegroupStatusDetailsCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        var callback = function(data, status, headers, config){
                return (data && data[0]) ? data[0] : data; 
            }

        $scope.getServicegroupStatusDetails = function () {

            var options = {
                    name: 'servicegroup',
                    url: 'servicegroupstatus/' + $routeParams.group,
                    callback: callback,
                };

            async.api($scope, options);

        };

    }])

    .controller('ConfigurationsCtrl', ['$scope', '$routeParams', 'async', function ($scope, $routeParams, async) {

        var type = $routeParams.type || '', 
            callback = function(data, status, headers, config){
                if( type ){
                    data = data[type] || {};
                }
                return data; 
            }

        $scope.getConfigurations = function () {

            var options = {
                    name: 'configurations',
                    url: 'configurations/' + type,
                    callback: callback,
                };

            async.api($scope, options);

        };

    }])

    .controller('ConfigurationDetailsCtrl', ['$scope', '$routeParams', '$filter', 'async', function ($scope, $routeParams, $filter, async) {

        var type = ($routeParams.type || ''), 
            name = $routeParams.name,
            name_key = $filter('configuration_anchor_key')(type),
            callback = function(data, status, headers, config){
                if( ! data || ! data[type] ){
                    return data;
                }
                data = data[type]['items'];
                data = $filter('property')(data, name_key, name)[0];
                return data; 
            }

        $scope.getConfigurationDetails = function () {

            $scope.configuration_type = $routeParams.type;
            $scope.configuration_name = $routeParams.name;

            var options = {
                    name: 'configuration',
                    url: 'configurations/' + type,
                    callback: callback,
                };

            async.api($scope, options);

        };

    }])

    .controller('CommentsCtrl', ['$scope', 'async', function ($scope, async) {

        $scope.getComments = function () {

            var options = {
                    name: 'comments',
                    url: 'comments',
                };

            async.api($scope, options);

        };

    }])
