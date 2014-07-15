angular.module('hoststatus', [])

    .controller('HoststatusCtrl', function ($scope, $http) {

        $scope.getHoststatus = function () {

            $scope.hoststatus = [];

            $http({method: 'GET', url: '/vshell2/api/hoststatus'})
                .success(function(data, status, headers, config) {
                    $scope.hoststatus = data;
                }).
                error(function(data, status, headers, config) {

                });

        };

    })

    .directive('footable', function(){
        // Code below is from Daniel Stucki. For more information see his
        // excellent StackOverflow answer:
        // http://stackoverflow.com/a/21057869/657661

        return function(scope, element) {
            var footableObject;

            if (scope.$last && ! $('.footable').hasClass('footable-loaded')) {
                $('.footable').footable();
            };

            footableObject = $('.footable').data('footable');

            if (footableObject !== undefined) {
                footableObject.appendRow($(element));
            };
        };

    })
