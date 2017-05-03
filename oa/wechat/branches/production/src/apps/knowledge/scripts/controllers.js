(function() {
'use strict';
angular.module('knowledge.controllers', [])
	.controller('mainController', [ '$scope','$location','$route', '$window', 'userManager','user',function($scope,$location,$route, $window, userManager,user){
        $scope.showHeader = false;
        $scope.user = user;
        $scope.currentPath = '';
        $scope.$on('$routeChangeSuccess',function(e){
            $scope.currentPath = $location.path();
        })
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
        $window.document.title = '知识库';
    }])
    .controller('knowledgeIndexController', ['$scope', '$timeout', '$route', '$location', '$window', 'globalPagination', 'globalFunction', 'modalExtension', 'articleApi', 'flowApi', 'searchTagApi', 'recordApi', 
        function($scope, $timeout, $route, $location, $window, globalPagination, globalFunction, modalExtension, articleApi, flowApi, searchTagApi, recordApi){
        
        // 点赞
        $scope.thumbsFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_admire: 0};
                var params = {
                    article_id: article.id,
                    is_admire: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('点赞成功').then(function() {
                        article.admire_count++;
                    })
                });

            } else {
                if (article.flow.is_admire == 1) {

                    modalExtension.alert('已点赞');

                } else {
                    article.flow.is_admire = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('点赞成功').then(function() {
                            article.admire_count++;
                        })
                    });
                }
            } 
        };

        // 收藏
        $scope.collectFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_collect: 0};
                var params = {
                    article_id: article.id,
                    is_collect: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('收藏成功');
                });
            } else {
                if (article.flow.is_collect == 1) {

                    modalExtension.alert('已收藏');

                } else {
                    article.flow.is_collect = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('收藏成功');
                    });
                }                
            }
        };

        // 搜索
        $scope.isShow = false;
        // $scope.defaultKeyword = '设计与产品';
        $scope.init = function () {
            $scope.isShow = false;
            $('#searchFiled').val($scope.defaultKeyword);
        };
        $scope.setSearchVal = function (val) {
            $('#searchFiled').val(val);
        };
        searchTagApi.query().$promise.then(function(data){
            var resResult = data;
            $scope.tags = resResult;
        });

        recordApi.get().$promise.then(function(data){
            $scope.hasRecord = data.recode;
            $scope.historyRecords = data.recode;
        });
        $scope.searchFn = function (keyword) {
            $location.path('/search-result/'+ keyword);
        };
        $scope.searchTagFn = function (tagId) {
            $location.path('/search-tag-result/'+ tagId);
        };


        // article list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":"",
            "type_id": ""
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = articleApi;
        $scope.pagination.sort = 'sort DESC';
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy,{type:{},user:{}, flow: {}}).$promise.then(function(data){
                $scope.articleList = _.union($scope.articleList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.articleList = [];
            $scope.select(1);
        };

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        };

        $scope.setFilter = function(typeId){
            delete $scope.condition.is_promote;
            $scope.condition.type_id = typeId;
            $scope.search();
        };
        $scope.setRecommendFilter = function (isPromote) {
            delete $scope.condition.type_id;
            $scope.condition.is_promote = isPromote;
            $scope.search();
        };

        $scope.bottomReached =  globalFunction.debounce(function(){
            if(!$scope.pagination.isLast()) {
                $scope.select($scope.pagination.page+1)
            } else {
                modalExtension.tips('没有更多内容');
            }
        },20);
        $scope.search();
        $window.document.title = '知识库';
    }])
    .controller('myCollectionController', ['$scope', '$timeout', '$route', '$window', 'globalPagination', 'globalFunction', 'modalExtension', 'flowApi', function($scope, $timeout, $route, $window, globalPagination, globalFunction, modalExtension, flowApi){
        
        // collect list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "is_collect": 1
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = flowApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy, {article:{user: ''}}).$promise.then(function(data){
                $scope.collectList = _.union($scope.collectList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.collectList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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

        // 收藏记录滑动删除
        $scope.deleteFn = function (item) {
            modalExtension.confirm('确认删除所选项').then(function() {
                var params = {
                    article_id: item.article_id,
                    id: item.id,
                    is_collect: 0
                }
                flowApi.update(params).$promise.then(function(){
                    modalExtension.alert('删除成功').then(function() {
                        window.location.reload();
                    });
                });
            });
        };

        $scope.fnShowDel = function (item, isShow) {
            var collectList = $scope.collectList;
            angular.forEach(collectList, function(data, index, array){
                if (item.article_id == data.article_id) {
                    item.isDelete = isShow;
                }
            });
        };
        $window.document.title = '我的收藏';

    }])
    .controller('articleDetailController', ['$scope', '$routeParams', 'globalFunction', 'globalConfig', 'modalExtension', 'articleDetailApi', 'flowApi', function($scope, $routeParams, globalFunction, globalConfig, modalExtension, articleDetailApi, flowApi){
        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id},{user: {}, flow: {}})).$promise.then(function(data){
            // console.log(data);
            var resResult = data;
            $scope.articleDetail = resResult;

            // 文章详情操作(点赞、收藏、评论)
            $scope.articleActionFn = function (article, status) {
                if (article.flow == null) {
                    if (status == 0) { // 点赞
                        article.flow = {is_admire: 0};
                        var params = {
                            article_id: article.id,
                            is_admire: 1
                        };
                        flowApi.save(params).$promise.then(function() {
                            modalExtension.alert('点赞成功').then(function() {
                                article.admire_count++;
                                article.flow.is_admire = 1;
                            })
                        });
                    } else if (status == 1) { // 收藏
                        article.flow = {is_collect: 0};
                        var params = {
                            article_id: article.id,
                            is_collect: 1
                        };
                        flowApi.save(params).$promise.then(function() {
                            modalExtension.alert('收藏成功').then(function() {
                                article.collect_count++;
                                article.flow.is_collect = 1;
                            })
                        });                        
                    }

                } else {
                    if (status == 0) {
                        if (article.flow.is_admire == 1) {
                            modalExtension.alert('已点赞');
                        } else {
                            article.flow.is_admire = 1;
                            flowApi.update(article.flow).$promise.then(function() {
                                modalExtension.alert('点赞成功').then(function() {
                                    article.admire_count++;
                                })
                            });
                        }
                    } else if (status == 1) {
                        if (article.flow.is_collect == 1) {
                            modalExtension.alert('已收藏');
                        } else {
                            article.flow.is_collect = 1;
                            flowApi.update(article.flow).$promise.then(function() {
                                modalExtension.alert('收藏成功').then(function() {
                                    article.collect_count++;
                                })
                            });
                        }                        
                    }
                } 
            }
        });   
    }])
    .controller('commentController', ['$scope', '$timeout', '$route', '$routeParams', '$location', 'globalFunction', 'globalPagination', 'modalExtension', 'commentApi', 'articleDetailApi',
        function($scope, $timeout, $route, $routeParams, $location, globalFunction, globalPagination, modalExtension, commentApi, articleDetailApi){
        
        // comment list        
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "keyword":"",
            "status":"",
            "article_id": $routeParams.id
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = commentApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){                            
                $scope.commentList = _.union($scope.commentList,data);               
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.commentList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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

        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id},{user: {}, flow: {}})).$promise.then(function(data){
            // console.log(data);
            $scope.articleDetail = data;
        });
        $scope.commentSubmitFn = function (inputVal) {
            // if ($scope.articleDetail.is_comment == 0) {
            //     modalExtension.alert('没有相关权限');
            // } else {
                if (inputVal == undefined || inputVal == '') {
                    modalExtension.alert('请添加评论内容').then(function() {
                        $('#commentField').focus();
                    })
                } else {
                    var params = {
                        article_id: $routeParams.id,
                        comment: inputVal
                    }
                    commentApi.save(params).$promise.then(function(data){
                        modalExtension.alert('评论成功').then(function(){
                            $scope.search();
                            $scope.commentField = '';
                        });
                    });
                }                
            // }
        };
    }])
    .controller('personalCenterController', ['$scope', '$window', 'myApi', function($scope, $window, myApi){
        myApi.get().$promise.then(function(data){
            var resResult = data;
            $scope.myInfo = resResult;
        });
        $window.document.title = '个人';
    }])
    .controller('myCommentController', ['$scope', '$timeout', '$route', 'globalPagination', 'globalFunction', 'flowApi', function($scope, $timeout, $route, globalPagination, globalFunction, flowApi){
        
        // collect list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "is_comment": 1
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = flowApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy, {article:{user: ''}}).$promise.then(function(data){
                $scope.commentList = _.union($scope.commentList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.commentList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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

    }])
    .controller('myAdmireController', ['$scope', '$timeout', '$route', 'globalPagination', 'globalFunction', 'flowApi', function($scope, $timeout, $route, globalPagination, globalFunction, flowApi){
        
        // collect list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0)
        $scope.condition = {
            "is_admire": 1
        };
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = flowApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy, {article:{user: ''}}).$promise.then(function(data){
                $scope.admireList = _.union($scope.admireList,data);
            })
        };
        $scope.search = function(){
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.admireList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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

    }])
    .controller('searchController', ['$scope', '$routeParams', '$timeout', '$route', 'globalPagination', 'globalFunction', 'modalExtension', 'articleApi', 'flowApi', 'conditionTypes', 
        function($scope, $routeParams, $timeout, $route, globalPagination, globalFunction, modalExtension, articleApi, flowApi, conditionTypes){
        
        // console.log($routeParams.keyword);
        // 点赞
        $scope.thumbsFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_admire: 0};
                var params = {
                    article_id: article.id,
                    is_admire: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('点赞成功').then(function() {
                        article.admire_count++;
                    })
                });

            } else {
                if (article.flow.is_admire == 1) {

                    modalExtension.alert('已点赞');

                } else {
                    article.flow.is_admire = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('点赞成功').then(function() {
                            article.admire_count++;
                        })
                    });
                }
            } 
        };

        // 收藏
        $scope.collectFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_collect: 0};
                var params = {
                    article_id: article.id,
                    is_collect: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('收藏成功');
                });
            } else {
                if (article.flow.is_collect == 1) {

                    modalExtension.alert('已收藏');

                } else {
                    article.flow.is_collect = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('收藏成功');
                    });
                }                
            }
        };


        // article list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0);
        $scope.article = {
            name: $routeParams.keyword,
            column_id: ""
        }
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = articleApi;
        $scope.pagination.sort = 'sort DESC';
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy,{type:{},user:{}, flow: {}}).$promise.then(function(data){
                $scope.isDataNull = (data.length == 0) ? true : false;
                $scope.articleList = _.union($scope.articleList,data);
            });
        };
        $scope.search = function(){
            $scope.condition = {
                title: {type: conditionTypes.like, value: $scope.article.name},
                type_id: $scope.article.column_id.id
            };
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.articleList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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
    }])
    .controller('searchTagController', ['$scope', '$routeParams', '$timeout', '$route', 'globalPagination', 'globalFunction', 'modalExtension', 'tagArticleApi', 'flowApi', 'conditionTypes', 
        function($scope, $routeParams, $timeout, $route, globalPagination, globalFunction, modalExtension, tagArticleApi, flowApi, conditionTypes){
        
        // console.log($routeParams.keyword);
        // 点赞
        $scope.thumbsFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_admire: 0};
                var params = {
                    article_id: article.id,
                    is_admire: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('点赞成功').then(function() {
                        article.admire_count++;
                    })
                });

            } else {
                if (article.flow.is_admire == 1) {

                    modalExtension.alert('已点赞');

                } else {
                    article.flow.is_admire = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('点赞成功').then(function() {
                            article.admire_count++;
                        })
                    });
                }
            } 
        };

        // 收藏
        $scope.collectFn = function (article) {
            if (article.flow == null) {
                article.flow = {is_collect: 0};
                var params = {
                    article_id: article.id,
                    is_collect: 1
                };
                flowApi.save(params).$promise.then(function(data) {
                    article.flow = data;
                    modalExtension.alert('收藏成功');
                });
            } else {
                if (article.flow.is_collect == 1) {

                    modalExtension.alert('已收藏');

                } else {
                    article.flow.is_collect = 1;
                    flowApi.update(article.flow).$promise.then(function() {
                        modalExtension.alert('收藏成功');
                    });
                }                
            }
        };


        // article list
        $timeout(function(){
            $scope.$parent.showHeader = true;
        },0);
        $scope.article = {
            id: $routeParams.tagId
        }
        //初始化列表數據
        $scope.pagination = globalPagination.create();
        $scope.pagination.resource = tagArticleApi;
        $scope.select = function(page) {
            $scope.pagination.select(page,$scope.condition_copy,{article:{user: '',flow: ''}}).$promise.then(function(data){
                $scope.isDataNull = (data.length == 0) ? true : false;
                $scope.articleList = _.union($scope.articleList,data);
            });
        };
        $scope.search = function(){
            $scope.condition = {               
                id: $scope.article.id
            };
            $scope.condition_copy = angular.copy($scope.condition);
            $scope.articleList = [];
            $scope.select(1);
        }

        $scope.setStatus = function(status){
            $scope.condition.status = status;            
            $scope.search();
        }

        $scope.setFilter = function(typeId){
            $scope.condition.type_id = typeId;
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
    }])
    .controller('searchDetailController', ['$scope', '$routeParams', 'globalFunction', 'globalConfig', 'modalExtension', 'articleDetailApi', 'flowApi', function($scope, $routeParams, globalFunction, globalConfig, modalExtension, articleDetailApi, flowApi){
        articleDetailApi.get(globalFunction.generateUrlParams({id:$routeParams.id, search: 1},{user: {}, flow: {}})).$promise.then(function(data){
            // console.log(data);
            var resResult = data;
            $scope.articleDetail = resResult;

            // 文章详情操作(点赞、收藏、评论)
            $scope.articleActionFn = function (article, status) {
                if (article.flow == null) {
                    if (status == 0) { // 点赞
                        article.flow = {is_admire: 0};
                        var params = {
                            article_id: article.id,
                            is_admire: 1
                        };
                        flowApi.save(params).$promise.then(function() {
                            modalExtension.alert('点赞成功').then(function() {
                                article.admire_count++;
                                article.flow.is_admire = 1;
                            })
                        });
                    } else if (status == 1) { // 收藏
                        article.flow = {is_collect: 0};
                        var params = {
                            article_id: article.id,
                            is_collect: 1
                        };
                        flowApi.save(params).$promise.then(function() {
                            modalExtension.alert('收藏成功').then(function() {
                                article.collect_count++;
                                article.flow.is_collect = 1;
                            })
                        });                        
                    }

                } else {
                    if (status == 0) {
                        if (article.flow.is_admire == 1) {
                            modalExtension.alert('已点赞');
                        } else {
                            article.flow.is_admire = 1;
                            flowApi.update(article.flow).$promise.then(function() {
                                modalExtension.alert('点赞成功').then(function() {
                                    article.admire_count++;
                                })
                            });
                        }
                    } else if (status == 1) {
                        if (article.flow.is_collect == 1) {
                            modalExtension.alert('已收藏');
                        } else {
                            article.flow.is_collect = 1;
                            flowApi.update(article.flow).$promise.then(function() {
                                modalExtension.alert('收藏成功').then(function() {
                                    article.collect_count++;
                                })
                            });
                        }                        
                    }
                } 
            }
        });   
    }])
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