angular.module('hoststatus', [])

  .controller('HoststatusCtrl', function ($scope, $http) {

    $scope.getHoststatus = function () {

      console.log('starting...');

     $scope.hoststatus=[];

      $http({method: 'GET', url: '/vshell2/api/hoststatus'}).
        success(function(data, status, headers, config) {
          console.info('success');
          $scope.hoststatus = data;
        }).
        error(function(data, status, headers, config) {
          console.info('fail');
        });

        //return hoststatus;

    };


});
