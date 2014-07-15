angular.module('tac_data', [])

    .controller('TacDataCtrl', function ($scope, $http) {

        $scope.getTacData = function () {

         $scope.tacData=[];

          $http({method: 'GET', url: '/vshell2/api'}).
            success(function(data, status, headers, config) {
              $scope.tacData = data;
            }).
            error(function(data, status, headers, config) {
              console.info('Fail');
            });

        };


});
