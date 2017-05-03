(function() {
    'use strict';
    angular.module('directory.controllers',[])
        .controller('mainController', [ '$scope','$location','$route','userManager','user',function($scope,$location,$route,userManager,user){
            $scope.user = user;
            $scope.keyword = '';
            $scope.search = function(){
                $scope.$broadcast('search',$scope.keyword)
            }
        }])
        .controller('listController',['$scope','$timeout','$location','globalPagination','userApi',function($scope,$timeout,$location,globalPagination,userApi){
            $timeout(function(){
                $scope.$parent.showHeader = true;
                $scope.$parent.keyword = '';
            },0)
            $scope.condition = {
                "keyword":""
            };
            if($location.path() == '/useful-list')
                $scope.condition.only_useful = 1;
            //初始化列表數據
            $scope.pagination = globalPagination.create();
            $scope.pagination.resource = userApi;
            $scope.pagination.sort = 'initial_letter ASC,name ASC,id ASC';
            $scope.select = function(page) {
                $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                    $scope.users = _.union($scope.users,data);
                })
            };
            $scope.search = function(){
                $scope.condition_copy = angular.copy($scope.condition);
                $scope.users = [];
                $scope.select(1);
            }

            $scope.bottomReached = function(){
                if(!$scope.pagination.isLast())
                    $scope.select($scope.pagination.page+1)
            }

            $scope.search();

            $scope.$on('search',function(event,keyword){
                $scope.condition.keyword = keyword;
                $scope.search();
            })
        }])
        .controller('detailController',['$timeout','$scope','$routeParams','userApi',function($timeout,$scope,$routeParams,userApi){
            $timeout(function(){
                $scope.$parent.showHeader = false;
            },0)
            $scope.currentUser = userApi.get({"id":$routeParams.id});
        }])
}).call(this);