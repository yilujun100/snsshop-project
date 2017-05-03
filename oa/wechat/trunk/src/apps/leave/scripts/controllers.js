(function() {
    'use strict';
    angular.module('leave.controllers',[])
        .controller('UserLeaveListController',['$scope','$timeout','$location','$routeParams','globalPagination','recordApi',
            function($scope,$timeout,$location,$routeParams,globalPagination,recordApi){
                $timeout(function(){
                    $scope.$parent.showHeader = false;
                },0)
                $scope.condition = {
                    "user_id":$routeParams.id
                };
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = recordApi;
                $scope.pagination.query_method = 'userApplyList';
                $scope.select = function(page) {
                    $scope.pagination.select(page,$scope.condition_copy,{"leaveRecord":{}}).$promise.then(function(data){
                        $scope.records = _.union($scope.records,data);
                    })
                };
                $scope.search = function(){
                    $scope.condition_copy = angular.copy($scope.condition);
                    $scope.records = [];
                    $scope.select(1);
                }

                $scope.bottomReached = function(){
                    if(!$scope.pagination.isLast())
                        $scope.select($scope.pagination.page+1)
                }

                $scope.search();
        }])
}).call(this);