(function() {
    'use strict';
    angular.module('attendance.controllers',[])
        .controller('mainController', [ '$scope','$location','$route','SharedState',function($scope,$location,$route,SharedState){
        }])
        .controller('listController',['$scope','$timeout','$location','globalFunction','conditionTypes','attendanceLogApi',function($scope,$timeout,$location,globalFunction,conditionTypes,attendanceLogApi){
                $scope.condition = {
                    time_min:{type:conditionTypes.min,value:''},
                    time_max:{type:conditionTypes.max,value:''}
                }
                $scope.$watch('currentDate',function(new_value,old_value){
                    if(new_value){
                        $scope.condition.time_min.value = Date.parse(new_value)/1000;
                        $scope.condition.time_max.value = Date.parse(new_value)/1000+86400;
                        $scope.logs = attendanceLogApi.query(globalFunction.generateUrlParams($scope.condition))
                    }else{
                        $scope.logs = [];
                    }
                })
                var now = new Date();
                $scope.currentDate = new Date(now.getFullYear(),now.getMonth(),now.getDate());
                $scope.logs = attendanceLogApi.query();
        }])
        .controller('recordController',['$scope','$timeout','$location','$filter','$routeParams','globalFunction','conditionTypes','attendanceRecordApi',
            function($scope,$timeout,$location,$filter,$routeParams,globalFunction,conditionTypes,attendanceRecordApi){
                $scope.now = new Date($routeParams.date);
                $scope.condition = {
                    date:{type:conditionTypes.rightLike,value:$filter('date')($scope.now,'yyyy-MM')}
                }
                $scope.day_arr = ['日','一','二','三','四','五','六'];
                $scope.status_arr= ['','正常','迟到','早退','缺勤','---','迟到早退']
                $scope.isNextDay = function(record){
                    return record.date < $filter('date')(record.sign_out_actual_time*1000,'yyyy-MM-dd');
                }
                $scope.records = attendanceRecordApi.query(globalFunction.generateUrlParams($scope.condition));
        }])
        .controller('signController',['$interval','$scope','$rootScope','$routeParams','$filter','SharedState','weChat','signType','attendanceLogApi','attendanceSignApi','modalExtension',
            function($interval,$scope,$rootScope,$routeParams,$filter,SharedState,weChat,signType,attendanceLogApi,attendanceSignApi,modalExtension){
            var current_time;
            $scope.type = signType;
            $scope.time = (new Date()).getTime();
            $scope.time_difference = 0;
            current_time = (new Date()).getTime();
            attendanceSignApi.currentTime().$promise.then(function(data){
                $scope.time_difference = current_time - data.time*1000; //加这200毫秒是考虑到网络延迟带来的误差
                $scope.time = (new Date()).getTime()-$scope.time_difference;
            })

            $interval(function(){
                $scope.time = (new Date()).getTime()-$scope.time_difference;
            },1000);
            if($scope.type == 1)//sign in
                $scope.time_type = 'in';
            else
                $scope.time_type = 'out';
            $scope.sign = function(){
                var loading = modalExtension.loading("正在获取位置中，请稍候");
                weChat.getLocation({
                    type: 'gcj02'
                }).then(function(data){
                    attendanceSignApi.save({type:$scope.type,latitude:data.latitude,longitude:data.longitude}).$promise.then(function(data){
                        loading.close();
                        var type = $scope.type == 1?"签到":"签退";
                        $scope.time_type = data.time_type;
                        modalExtension.alert(type+"成功,本次"+type+"时间为"+$filter('date')(data.time*1000,'HH:mm:ss'));
                    },function(data){
                        loading.close();
                    });
                },function(){
                    loading.close();
                    modalExtension.alert('获取当前位置失败');
                })
            }
        }])
}).call(this);