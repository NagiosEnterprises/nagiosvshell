angular.module('status', ['stateFilters'])

    .controller('StatusCtrl', function ($scope, $http) {

        $scope.getStatus = function () {

            $scope.status = [];

            $http({ method: 'GET', url: '/vshell2/api/status' })
                .success(function(data, status, headers, config) {
                    $scope.status = data;
                    /*
                    hostsTotal
                    hostsProblemsTotal

                    hostsUpTotal
                    hostsDownTotal
                    hostsUnreachableTotal
                    hostsPending

                    servicesTotal
                    servicesProblemsTotal

                    servicesOkTotal
                    servicesWarningTotal
                    servicesUnknownTotal
                    servicesPendingTotal
                    servicesCriticalTotal
                    */
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Status information from the VShell2 API');
                });

        };

    })
