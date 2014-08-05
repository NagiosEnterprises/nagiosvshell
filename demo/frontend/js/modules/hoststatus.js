angular.module('hoststatus', ['stateFilters'])

    .controller('HoststatusCtrl', function ($scope, $http) {

        $scope.getHoststatus = function () {

            $scope.hoststatus = [];

            $http({ method: 'GET', url: '/vshell2/api/hoststatus' })
                .success(function(data, status, headers, config) {
                    $scope.hoststatus = data;
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load Host Status information from the VShell2 API');
                });

        };

    })

    .directive('footabledata', function(){
        // Fix footable initiation to happen after AngularJS finishes loading
        // asynchronous API data.
        //
        // See Daniel Stucki's excellent StackOverflow answer:
        // http://stackoverflow.com/a/21057869/657661
        //
        // Also see footable API docs:
        // http://fooplugins.com/footable/demos/api.htm#docs

        return function(scope, element) {
            var footableTable = $(element).parents('table'),
                footableObject;

            if (scope.$last && ! footableTable.hasClass('footable-loaded')) {
                footableTable.footable();
            };

            footableObject = footableTable.data('footable');

            if (footableObject !== undefined) {
                footableObject.appendRow($(element));
            };
        };

    })
