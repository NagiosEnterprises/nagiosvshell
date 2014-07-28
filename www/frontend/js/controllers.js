'use strict';

angular.module('vshell2.controllers', [])

    .controller('QuicksearchCtrl', ['$scope', '$http', '$location', '$filter', 'vshell_uri', function ($scope, $http, $location, $filter, vshell_uri) {

        var callback = function(e, item){
            var base = $filter('uri')(item.type),
                path = base + '/' + item.uri;
            $location.path(path);
            $scope.$apply();
        }

        $scope.getQuicksearchData = function () {

            $http({ method: 'GET', url: vshell_uri + 'api/quicksearch' })
                .success(function(data, status, headers, config) {
                    quicksearch.init(data, callback);
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Quicksearch data from the V-Shell2 API');
                });

        };

    }])

    .controller('NavCtrl', ['$scope', '$http', 'vshell_uri', function ($scope, $http, vshell_uri) {

        $scope.getNav = function () {

            $scope.nav = [];

            $http({ method: 'GET', url: vshell_uri + 'api/vshellconfig' })
                .success(function(data, status, headers, config) {
                    $scope.nav = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load VShell config information from the V-Shell2 API');
                });

        };

    }])

    .controller('StatusCtrl', ['$scope', '$http', 'vshell_uri', function ($scope, $http, vshell_uri) {

        $scope.getStatus = function () {

            $scope.status = [];

            $http({ method: 'GET', url: vshell_uri + 'api/status' })
                .success(function(data, status, headers, config) {
                    $scope.status = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostStatusCtrl', ['$scope', '$http', '$routeParams', '$filter', 'vshell_uri', function ($scope, $http, $routeParams, $filter, vshell_uri) {

        $scope.getHostStatus = function () {

            $scope.is_loading = true;
            $scope.hoststatus = [];
            $scope.statefilter = $routeParams.state || '';

            $http({ method: 'GET', url: vshell_uri + 'api/hoststatus' })
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    data = $filter('by_state')(data, 'host', $scope.statefilter);
                    $scope.hoststatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Host Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('HostStatusDetailsCtrl', ['$scope', '$http', '$routeParams', 'vshell_uri', function ($scope, $http, $routeParams, vshell_uri) {

        $scope.getHostStatusDetails = function () {

            $scope.host = [];

            $http({ method: 'GET', url: vshell_uri + 'api/hoststatus/' + $routeParams.host })
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

    .controller('HostgroupStatusCtrl', ['$scope', '$http', 'vshell_uri', function ($scope, $http, vshell_uri) {

        $scope.getHostgroupStatus = function () {

            $scope.is_loading = true;
            $scope.hostgroupstatus = [];

            $http({ method: 'GET', url: vshell_uri + 'api/hostgroupstatus' })
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

    .controller('HostgroupStatusDetailsCtrl', ['$scope', '$http', '$routeParams', 'vshell_uri', function ($scope, $http, $routeParams, vshell_uri) {

        $scope.getHostgroupStatusDetails = function () {

            $scope.is_loading = true;
            $scope.hostgroup = [];

            $http({ method: 'GET', url: vshell_uri + 'api/hostgroupstatus/' + $routeParams.group })
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

    .controller('ServiceHostStatusCtrl', ['$scope', '$http', '$routeParams', 'vshell_uri', function ($scope, $http, $routeParams, vshell_uri) {

        $scope.getServiceHostStatus = function () {

            $scope.host_name = $routeParams.host;

            $scope.is_loading = true;
            $scope.servicestatus = [];

            $http({ method: 'GET', url: vshell_uri + 'api/servicestatus/' + $routeParams.host })
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

    .controller('ServiceStatusCtrl', ['$scope', '$http', '$routeParams', '$filter', 'vshell_uri', function ($scope, $http, $routeParams, $filter, vshell_uri) {

        $scope.getServiceStatus = function () {

            $scope.is_loading = true;
            $scope.servicestatus = [];
            $scope.statefilter = $routeParams.state;

            $http({ method: 'GET', url: vshell_uri + 'api/servicestatus' })
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    data = $filter('by_state')(data, 'service', $scope.statefilter);
                    $scope.servicestatus = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Service Status information from the V-Shell2 API');
                });

        };

    }])

    .controller('ServiceStatusDetailsCtrl', ['$scope', '$http', '$routeParams', 'vshell_uri', function ($scope, $http, $routeParams, vshell_uri) {

        $scope.getServiceStatusDetails = function () {

            $scope.service = [];

            $http({ method: 'GET', url: vshell_uri + 'api/servicestatus/' + $routeParams.host + '/' + $routeParams.service })
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

    .controller('ServicegroupStatusCtrl', ['$scope', '$http', 'vshell_uri', function ($scope, $http, vshell_uri) {

        $scope.getServicegroupStatus = function () {

            $scope.is_loading = true;
            $scope.servicegroupstatus = [];

            $http({ method: 'GET', url: vshell_uri + 'api/servicegroupstatus' })
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

    .controller('ServicegroupStatusDetailsCtrl', ['$scope', '$http', '$routeParams', 'vshell_uri', function ($scope, $http, $routeParams, vshell_uri) {

        $scope.getServicegroupStatusDetails = function () {

            $scope.is_loading = true;
            $scope.servicegroup = [];

            $http({ method: 'GET', url: vshell_uri + 'api/servicegroupstatus/' + $routeParams.group })
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

    .controller('ConfigurationsCtrl', ['$scope', '$http', '$routeParams', '$filter', 'vshell_uri', function ($scope, $http, $routeParams, $filter, vshell_uri) {

        $scope.getConfigurations = function () {

            $scope.is_loading = true;
            $scope.configurations = [];

            var type = $routeParams.type || '';

            $http({ method: 'GET', url: vshell_uri + 'api/configurations/' + type})
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    if( type ){ data = data[type] || {} };
                    $scope.configurations = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Configuration information from the V-Shell2 API');
                });

        };

    }])

    .controller('ConfigurationDetailsCtrl', ['$scope', '$http', '$routeParams', '$filter', 'vshell_uri', function ($scope, $http, $routeParams, $filter, vshell_uri) {

        $scope.getConfigurationDetails = function () {

            $scope.is_loading = true;
            $scope.configuration_type = $routeParams.type;
            $scope.configuration_name = $routeParams.name;
            $scope.configuration = [];

            var type = $scope.configuration_type,
                name = $scope.configuration_name,
                name_key = $filter('configuration_anchor_key')(type);

            $http({ method: 'GET', url: vshell_uri + 'api/configurations/' + type})
                .success(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    data = data[type]['items'];
                    data = $filter('property')(data, name_key, name)[0];
                    $scope.configuration = data;
                }).
                error(function(data, status, headers, config) {
                    $scope.is_loading = false;
                    messages.error('failed to load Configuration detail information from the V-Shell2 API');
                });

        };

    }])
