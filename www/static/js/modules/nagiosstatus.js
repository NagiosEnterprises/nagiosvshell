angular.module('nagiosstatus', [])

.controller('StatusCtrl', function ($scope, $http) {

    $scope.getHoststatus = function () {

        $scope.hoststatus=[];

        $http({method: 'GET', url: '/vshell2/api/hoststatus'}).

            success(function(data, status, headers, config) {
                console.info('success');
                $scope.hoststatus = data;
            }).

            error(function(data, status, headers, config) {
                console.info('fail');
        });

    };


    $scope.getServicestatus = function () {

        $scope.servicestatus=[];

        $http({method: 'GET', url: '/vshell2/api/servicestatus'}).

            success(function(data, status, headers, config) {
                console.info('success');
                $scope.servicestatus = data;
                $scope.services = $scope.servicestatus;
                $scope.pageNo = 1;
            }).

            error(function(data, status, headers, config) {
                console.info('fail');
        });

    };

    $scope.getTacData = function() {

        $scope.tacData=[];

        $http({method: 'GET', url: '/vshell2/api/'}).

            success(function(data, status, headers, config) {
                $scope.tacData= data;
            }).

            error(function(data, status, headers, config) {
                console.info('fail');
        });
    };


    $scope.translateServicestatus = function(statusCode,lastCheck){

        if(statusCode=='0' && lastCheck == '0'){
            return 'PENDING';
        }else if(statusCode=='0'){
            return 'OK';
        } else if(statusCode=='1'){
            return 'WARNING';
        } else if(statusCode=='2'){
            return 'CRITICAL';
        }else {
            return 'UNKNOWN';
        }

    };


    $scope.calculateDuration = function(timestamp){


        var now = new Date();
        var beginning = new Date(timestamp * 1000);

        var duration = Math.round ( ( now.getTime() / 1000 )  - ( beginning.getTime() / 1000) );

        var seconds_per_minute = 60;
        var seconds_per_hour = seconds_per_minute * seconds_per_minute;
        var seconds_per_day = 24 * seconds_per_hour;

        var remaining_duration = duration;
        var days = Math.round( remaining_duration / seconds_per_day);

        remaining_duration -= days * seconds_per_day;
        var hours = Math.round( remaining_duration / seconds_per_hour );
        remaining_duration -= hours * seconds_per_hour;
        var minutes = Math.round( remaining_duration / seconds_per_minute );
        remaining_duration -= minutes * seconds_per_minute;
        var seconds = remaining_duration;

        var retval = '';


        if (days > 0) {
            retval += days+'d-';
        }

        if (hours > 0 || days > 0) {
            retval += (hours < 0 ? 0 : hours)+'h-';
        }

        if (minutes > 0 || days > 0 || hours > 0) {
            retval += (minutes < 0 ? 0 : minutes) + 'm-';
        }

        if (seconds > 0 || minutes > 0 || days > 0 || hours > 0) {
            retval += (seconds < 0 ? 0 : seconds) + 's';
        }

        return retval;
        
    };



    $scope.servicestatusCSS = function(state) {
        switch(state){
            case 'OK':
                return 'success';
            case 'WARNING':
                return 'warning';
            case 'CRITICAL':
                return 'danger';
            case 'PENDING':
                return;            
        }
    };


    //pagination
    $scope.pageSize = 10;
    $scope.pages = [];
    $scope.pageStart = 1;

    $scope.$watch('services.length', function(filteredSize){
      $scope.pages.length = 0;
      var noOfPages = Math.ceil(filteredSize / $scope.pageSize);
      for (var i=0; i<noOfPages; i++) {
        $scope.pages.push(i);
      }
    });

    $scope.setActivePage = function (pageNo) {
      if (pageNo >=0 && pageNo < $scope.pages.length) {
        $scope.pageNo = pageNo;

        $scope.pageStart = pageNo<= 1 ? 1 : pageNo * $scope.pageSize;
        $scope.pageEnd = $scope.pageStart > $scope.servicestatus.length ? $scope.servicestatus.length : $scope.pageStart+$scope.pageSize - 1;
      }
    };

})
  .filter('pagination', function(){

     return function(inputArray, selectedPage, pageSize) {
        if(selectedPage == undefined){
            selectedPage = 1;
        }
       var start = selectedPage*pageSize;
       return inputArray.slice(start, start + pageSize);
     };
  });

