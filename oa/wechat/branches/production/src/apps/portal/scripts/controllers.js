(function() {
'use strict';
angular.module('portal.controllers', [])
	.controller('mainController', [ '$scope','$location','$route', '$window', 'userManager','user',function($scope,$location,$route, $window, userManager,user){
        $scope.showHeader = false;
        $scope.user = user;
        $scope.currentPath = '';
        $scope.$on('$routeChangeSuccess',function(e){
            $scope.currentPath = $location.path();
        })
        $scope.isCurrentPath = function(paths) {
            return _.contains(paths.split(','),$scope.currentPath);
            /*var xxx = _.find(paths.split(','), function(item){
                if ($location.path().indexOf(item) != -1) {
                    return true;
                } else {
                    return false;
                }
                // $location.path().indexOf(item);
            });
            console.log(xxx);*/
        };
        $scope.setHighlight = function(paths) {
            var pathArr = paths.split(',');
            var currentPath = $location.path();
            var isHighlight;
            pathArr.forEach(function(val, index, arr){
                if (currentPath.indexOf(arr[index]) != -1) {
                    isHighlight =  true;
                }
            });
            return isHighlight;
        };
        $window.document.title = '门户首页';
    }])
    .controller('portalIndexController', ['$scope', '$routeParams', '$window', 'globalFunction', 'globalConfig', 'rotateApi', 'columnApi', 'userApi', 'tagApi', function($scope, $routeParams, $window, globalFunction, globalConfig, rotateApi, columnApi, userApi, tagApi){
    
        rotateApi.query().$promise.then(function(data){
            var resResult = data;
            $scope.sliderList = resResult;
        });
        
        columnApi.query(globalFunction.generateUrlParams({id:''},{newestPortalArticle:{}})).$promise.then(function(data){
            var resResult = data;
            var arrResult = [];
            angular.forEach(resResult, function(item){
                arrResult.push(item.newestPortalArticle);
            });
            $scope.newsList = arrResult[2]; // notice
            $scope.companyEventsList = arrResult[1]; // company events
            $scope.articles = arrResult[0]; // articles
        });

        // new people
        userApi.query().$promise.then(function(data){
            var resResult = data;
            $scope.peoples = resResult;
        });

        // article tag
        /*tagApi.get(globalFunction.generateUrlParams({id:$routeParams.id})).$promise.then(function(data){
            console.log(data);
        });*/

        $scope.$on('setParentHeight',function(newV,oldV){
            // console.log($('.banner img').eq(0).height());
        });
        $window.document.title = '门户首页';
    }])
    .controller('noticeListController', ['$scope', '$timeout', '$route', '$window', 'globalPagination', 'globalFunction', 'modalExtension', 'noticeApi', function($scope, $timeout, $route, $window, globalPagination, globalFunction, modalExtension, noticeApi){
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":""
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = noticeApi;
        $scope.pagination.sort = 'sort DESC';
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                $scope.noticeList = _.union($scope.noticeList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.noticeList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;
            $scope.search();
        }

        $scope.bottomReached =  globalFunction.debounce(function(){
            if(!$scope.pagination.isLast()) {
                $scope.select($scope.pagination.page+1)
            } else {
                modalExtension.tips('没有更多内容');
            }
        },20);
        $scope.search();
        $window.document.title = '公告';
    }])
    .controller('eventsListController', ['$scope', '$timeout', '$route', '$window', 'modalExtension','globalFunction', 'globalPagination', 'articleApi', function($scope, $timeout, $route, $window, modalExtension,globalFunction, globalPagination, articleApi){
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":""
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = articleApi;
        $scope.pagination.sort = 'sort DESC';
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                $scope.articles = _.union($scope.articles,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.articles = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;
            $scope.search();
        }

        $scope.bottomReached =  globalFunction.debounce(function(){
            if(!$scope.pagination.isLast()) {
                $scope.select($scope.pagination.page+1)
            } else {
                modalExtension.tips('没有更多内容');
            }
        },20);
        $scope.search();
        $window.document.title = '新闻';
    }])
    .controller('systemListController', ['$scope', '$timeout', '$route', '$window', 'globalPagination', 'globalFunction', 'modalExtension', 'systemApi', function($scope, $timeout, $route, $window, globalPagination, globalFunction, modalExtension, systemApi){
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":""
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = systemApi;
        $scope.pagination.sort = 'sort DESC';
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                $scope.systemList = _.union($scope.systemList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.systemList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;
            $scope.search();
        }

        $scope.bottomReached =  globalFunction.debounce(function(){
            if(!$scope.pagination.isLast()) {
                $scope.select($scope.pagination.page+1)
            } else {
                modalExtension.tips('没有更多内容');
            }
        },20);
        $scope.search();
        $window.document.title = '制度';
    }])    
    .controller('newPeopleListController', ['$scope', '$timeout', '$route', 'globalPagination', 'globalFunction', 'modalExtension', 'userApi', function($scope, $timeout, $route, globalPagination, globalFunction, modalExtension, userApi){
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":""
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = userApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                $scope.peopleList = _.union($scope.peopleList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.peopleList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;
            $scope.search();
        }

        $scope.bottomReached = function(){
            if(!$scope.pagination.isLast())
                $scope.select($scope.pagination.page+1)
        }
        $scope.bottomReached =  globalFunction.debounce(function(){
            if(!$scope.pagination.isLast()) {
                $scope.select($scope.pagination.page+1)
            } else {
                modalExtension.tips('没有更多内容');
            }
        },20);
        $scope.search();
    }])
    .controller('newPeopleIntroController', ['$scope', '$routeParams', 'globalFunction', 'globalConfig', 'userApi', function($scope, $routeParams, globalFunction, globalConfig, userApi){
        userApi.get(globalFunction.generateUrlParams({id:$routeParams.id})).$promise.then(function(data){
            var resResult = data;
            $scope.newPeopleIntro = resResult;
        });
    }])
    .controller('personalCenterController', ['$scope', '$routeParams', '$window', 'globalFunction', 'globalConfig', 'infoApi', function($scope, $routeParams, $window, globalFunction, globalConfig, infoApi){
        infoApi.get({'userid': 452}).$promise.then(function(data){
            var resResult = data;
            $scope.userInfo = resResult;
        });
        $window.document.title = '个人';
    }])
    .controller('eventDetailController', ['$scope', '$routeParams', '$window', 'globalFunction', 'globalConfig', 'articleDetailApi', function($scope, $routeParams, $window, globalFunction, globalConfig, articleDetailApi){
        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id})).$promise.then(function(data){
            var resResult = data;
            $scope.articleDetail = resResult;
        });
        $window.document.title = '新闻';
    }])
    .controller('noticeDetailController', ['$scope', '$routeParams', '$window', 'globalFunction', 'globalConfig', 'articleDetailApi', function($scope, $routeParams, $window, globalFunction, globalConfig, articleDetailApi){
        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id})).$promise.then(function(data){
            var resResult = data;
            $scope.noticeDetail = resResult;
        });
        $window.document.title = '公告';
    }])
    .controller('systemDetailController', ['$scope', '$routeParams', '$window', 'globalFunction', 'globalConfig', 'articleDetailApi', function($scope, $routeParams, $window, globalFunction, globalConfig, articleDetailApi){
        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id})).$promise.then(function(data){
            var resResult = data;
            $scope.systemDetail = resResult;
        });
        $window.document.title = '制度';
    }])
    .directive('indexSlider', function($rootScope){
        return {
            restrict: 'A',
            link: function(scope, elem, attrs) {
                if (scope.$last === true) {
                    $rootScope.$broadcast(attrs.indexSlider);
                }
            }
        };
    })
    /*.directive('computAge', function(){
        return {
            restrict: 'A',
            controller: ['$scope', function($scope){
                $scope.toDouble = function (num) {
                    return num < 10 ? '0' + num : num;
                }
            }],
            link: function(scope, element, attr){
                var iAge;
                var nowDate = new Date(),
                    nowDateYear =  nowDate.getFullYear(),
                    nowDateMonth = nowDate.getMonth() + 1,
                    nowDateDay = nowDate.getDate();             
                var strDate = nowDateYear + '-' + scope.toDouble(nowDateMonth) + '-' + scope.toDouble(nowDateDay);
                var joinDate = attr.date;
                console.log(attr.date);
                
                strDate = strDate.split('-');
                strDate = parseInt(strDate[0]) * 12 + parseInt(strDate[1]);
                joinDate = joinDate.split('-');
                joinDate = parseInt(joinDate[0]) * 12 + parseInt(joinDate[1]);
                var diff = Math.abs(strDate - joinDate);
                if (diff > 12) {
                    iAge = Math.floor(diff/12) + '年' + diff % 12 + '个月';
                } else {
                    iAge = diff % 12 + '个月';
                }
                console.log(iAge);
            }
        }
    })*/
    .directive('errSrc', function(){
        return {
            restrict: 'A',
            link: function(scope, element, attr){
                element.bind('error', function(){
                    if (attr.src != attr.errSrc) {
                        attr.$set('src', attr.errSrc);
                    }
                });
            }
        };
    })
    .filter('computAge', function(){

        /*return function (val) {
          var now = new Date(new Date().getFullYear() + '/' + (new Date().getMonth() + 1) + '/' + new Date().getDate()),
              time = new Date(new Date(val * 1000).getFullYear() + '/' + (new Date(val * 1000).getMonth() + 1) + '/' + new Date(val * 1000).getDate());
          var less = (now - time) / 86400000, hour = new Date(val * 1000).getHours(), minu = new Date(val * 1000).getMinutes();
          hour < 10 ? hour = '0' + hour : hour = hour;
          minu < 10 ? minu = '0' + minu : minu = minu;
          switch (less) {
            case 0:
              return '今天' + hour + ':' + minu;

            case 1:
              return '昨天' + hour + ':' + minu;

            default:
              return parseInt(less) + '天';
          }
        }*/
        return function (val) {
            var iAge;
            var now = new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate(),
                joinDate = new Date(val * 1000).getFullYear() + '-' + (new Date(val * 1000).getMonth() + 1) + '-' + new Date(val * 1000).getDate();

            now = now.split('-');
            now = parseInt(now[0]) * 12 + parseInt(now[1]);
            joinDate = joinDate.split('-');
            joinDate = parseInt(joinDate[0]) * 12 + parseInt(joinDate[1]);
            var diff = Math.abs(now - joinDate);
            if (diff > 12) {
                iAge = Math.floor(diff/12) + '年' + diff % 12 + '个月';
            } else {
                iAge = diff % 12 + '个月';
            }
            return iAge;
        }
    })
    .filter('trust', ['$sce', function($sce) {
        return function(val, str) {
            switch (str) {
                case 'html':
                    return $sce.trustAsHtml(val);
                case 'js':
                    return $sce.trustAsJs(val);
                case 'css':
                    return $sce.trustAsCss(val);
                case 'url':
                    return $sce.trustAsUrl(val);
                case 'resourceUrl':
                    return $sce.trustAsResourceUrl(val);
                default:
                    return '未可知';
            }
        };

    }]);
}).call(this);