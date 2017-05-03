/*
 * 知识库控制器
 * auth: yilj@snsshop.cn
 * date: 2016-9-19
 */

(function(){
	'use strict';
	angular.module('km.controller', [])
		.controller('kmCtrl', ['$scope', '$location', '$timeout', 'articleListApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog',
			function($scope, $location, $timeout, articleListApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;	
			});
			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, typeId) {
                articleListApi.doRequest(status, page, perPage, typeId)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, '');

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, typeId);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search
            $scope.searchFn = function (keyword) {
	            $location.path('index/km/search/'+ keyword);
	        };

	        // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	        // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });	                    
		                $timeout(function() {
		                	ngDialog.close();
		                }, 2000);
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });	                    
		                $timeout(function() {
		                	ngDialog.close();
		                }, 2000);

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });	                    
			                $timeout(function() {
			                	ngDialog.close();
			                }, 2000);
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });	                    
		                $timeout(function() {
		                	ngDialog.close();
		                }, 2000);
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });	                    
		                $timeout(function() {
		                	ngDialog.close();
		                }, 2000);

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });	                    
			                $timeout(function() {
			                	ngDialog.close();
			                }, 2000);
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

	        // 评论
	        $scope.toComment = function (articleid) {
	        	$location.path('index/km/detail/'+ articleid + '/comment');
	        };
		}])
		.controller('searchKmCtrl', ['$scope', '$rootScope', '$location', '$stateParams', 'searchKmApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog', 
			function($scope, $rootScope, $location, $stateParams, searchKmApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});
			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, keyword) {
                searchKmApi.doRequest(status, page, perPage, keyword)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                        $scope.isDataNull = (data.length == 0) ? true : false;
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.articles.keyword = $stateParams.keyword;
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.articles.keyword);

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.articles.keyword);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };
		}])
		.controller('searchTagCtrl', ['$scope', '$rootScope', '$location', '$stateParams', 'searchTagApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog', 
        	function($scope, $rootScope, $location, $stateParams, searchTagApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
        
	        $scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});
			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, tagId) {
                searchTagApi.doRequest(status, page, perPage, tagId)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                        $scope.isDataNull = (data.length == 0) ? true : false;
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.articles.tagId = $stateParams.tagId;
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.articles.tagId);

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.articles.tagId);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
		        // query tag
		        angular.forEach($scope.tags, function(item) {
		        	if (item.id == $stateParams.tagId) {
		        		$scope.currentTagName = item.name;
		        		$scope.currentTagId = item.id;
		        	}
		        });
            });

            // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	    }])
		.controller('articleDetailCtrl', ['$scope', '$rootScope', '$location', '$timeout', '$anchorScroll', '$stateParams', '$state', 'ngDialog', 'articleDetailApi', 'articleCommentApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'kmCommentApi', 'flowKmApi', 
			function($scope, $rootScope, $location, $timeout, $anchorScroll, $stateParams, $state, ngDialog, articleDetailApi, articleCommentApi, articleRecommendApi, myCardApi, hotTagApi, kmCommentApi, flowKmApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});
			articleDetailApi.doRequest($stateParams.articleid).success(function(data){
				$scope.articleDetail = data;
			});

			// comment list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1,
                "articleid": $stateParams.articleid
            };
            $scope.comments = {};
            $scope.doRequest = function(status, page, perPage, articleid) {
                articleCommentApi.doRequest(status, page, perPage, articleid)
                    .success(function(data, stat, headers) {
                        $scope.comments.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.comments.status = ''; //默认显示全部
            $scope.comments.articleid = $stateParams.articleid;
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

            // comment
            $scope.commentSubmitFn = function (textFieldVal) {
	            if (textFieldVal == undefined || textFieldVal == '') {
	                $scope.msg = '请添加评论内容';
	                ngDialog.open({ 
                    	template: './views/popup/tips.html', 
                    	className: 'ngdialog-theme-default',
                    	showClose: false,
                    	scope: $scope 
                    });                    
                    $timeout(function() {
	                	ngDialog.close();
	                	$('#commentField').focus();
	                }, 2000);
	            } else {
	                var params = {
	                    article_id: $stateParams.articleid,
	                    comment: textFieldVal
	                }
	                kmCommentApi.doRequest(params).success(function(){
	                	$scope.msg = '评论成功';
		                ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                    $timeout(function() {
		                	ngDialog.close();
	                    	$scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);
	                    	$scope.commentField = '';
	                    	$state.reload();
		                }, 2000);
	                });

	            }
	        };

	        // 文章详情操作(点赞、收藏、评论)
	        $scope.gotoComment = function () {
				$location.hash('comment');
				$anchorScroll();				
			};
            $scope.articleActionFn = function (article, status) {
                if (article.flow == null) {
                    if (status == 0) { // 点赞
                        article.flow = {is_admire: 0};
                        var params = {
                            article_id: article.id,
                            is_admire: 1
                        };
                        flowKmApi.doRequest(params).success(function(){
                        	$scope.msg = '点赞成功';
                            ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
		                    	article.admire_count++;
                                article.flow.is_admire = 1;
			                }, 2000);
		                    /*dialog.closePromise.then(function(){
		                    	article.admire_count++;
                                article.flow.is_admire = 1;
		                    });*/
                        });
                    } else if (status == 1) { // 收藏
                        article.flow = {is_collect: 0};
                        var params = {
                            article_id: article.id,
                            is_collect: 1
                        };
                        flowKmApi.doRequest(params).success(function(){
                        	$scope.msg = '收藏成功';
                            ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
		                    	article.collect_count++;
                                article.flow.is_collect = 1;
			                }, 2000);
		                    /*dialog.closePromise.then(function(){
		                    	article.collect_count++;
                                article.flow.is_collect = 1;
		                    });*/
                        });                      
                    }

                } else {
                    if (status == 0) {
                        if (article.flow.is_admire == 1) {
                            $scope.msg = '已点赞';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                }, 2000);
                        } else {
                            article.flow.is_admire = 1;
                            flowKmApi.doUpdateRequest(article.flow).success(function(){
		                    	$scope.msg = '点赞成功';
			                    ngDialog.open({ 
			                    	template: './views/popup/tips.html', 
			                    	className: 'ngdialog-theme-default',
			                    	showClose: false,
			                    	scope: $scope 
			                    });
			                    $timeout(function() {
				                	ngDialog.close();
			                    	article.admire_count++;
				                }, 2000);
		                    });
                        }
                    } else if (status == 1) {
                        if (article.flow.is_collect == 1) {
                            $scope.msg = '已收藏';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                }, 2000);		                    
                        } else {
                            article.flow.is_collect = 1;
                            flowKmApi.doUpdateRequest(article.flow).success(function(){
		                    	$scope.msg = '收藏成功';
			                    ngDialog.open({ 
			                    	template: './views/popup/tips.html', 
			                    	className: 'ngdialog-theme-default',
			                    	showClose: false,
			                    	scope: $scope 
			                    });
			                    $timeout(function() {
				                	ngDialog.close();
			                    	article.collect_count++;
				                }, 2000);	
		                    });
                        }                        
                    }
                } 
            }

            // 文章详情(上一篇&下一篇)
            /*var articleId = $stateParams.articleid;
            var paramsPrev = {
            	near: 'desc'
            };
            var paramsNext = {
            	near: 'asc'
            }
            $scope.articleFirst = false;
            $scope.articleLast = false;
            articlePrevNextApi.doRequest(articleId, paramsPrev).success(function(data) {
            	if (!data) {
            		$scope.articleFirst = true;
            	} else {
            		$scope.prevData = data;
            	}
            });
            articlePrevNextApi.doRequest(articleId, paramsNext).success(function(data) {
            	if (!data) {
            		$scope.articleLast = true;
            	} else {
            		$scope.nextData = data;
            	}
            });*/

		}])
		.controller('articleCommentCtrl', ['$scope', '$rootScope', '$stateParams', '$location', '$timeout', '$anchorScroll', 'ngDialog', 'articleDetailApi', 'articleCommentApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'kmCommentApi', 'flowKmApi',
			function($scope, $rootScope, $stateParams, $location, $timeout, $anchorScroll, ngDialog, articleDetailApi, articleCommentApi, articleRecommendApi, myCardApi, hotTagApi, kmCommentApi, flowKmApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			$scope.gotoComment = function () {
				$location.hash('comment');
				$anchorScroll();				
			};
			articleDetailApi.doRequest($stateParams.articleid).success(function(data){
				$scope.articleDetail = data;
			});

			// 评论列表
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1,
                "articleid": $stateParams.articleid
            };
            $scope.comments = {};
            $scope.doRequest = function(status, page, perPage, articleid) {
                articleCommentApi.doRequest(status, page, perPage, articleid)
                    .success(function(data, stat, headers) {
                        $scope.comments.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.comments.status = ''; //默认显示全部
            $scope.comments.articleid = $stateParams.articleid;
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);
            };

			$timeout(function(){
				$scope.gotoComment();
			},500);

			// 文章评论
			$scope.commentSubmitFn = function (textFieldVal) {
	            if (textFieldVal == undefined || textFieldVal == '') {
	                $scope.msg = '请添加评论内容';
	                var dialog = ngDialog.open({ 
                    	template: './views/popup/tips.html', 
                    	className: 'ngdialog-theme-default',
                    	showClose: false,
                    	scope: $scope 
                    });                    
	                $timeout(function() {
	                	ngDialog.close();
	                	$('#commentField').focus();
	                }, 2000);
                    /*dialog.closePromise.then(function(){
                    	$('#commentField').focus();
                    });*/
	            } else {
	                var params = {
	                    article_id: $stateParams.articleid,
	                    comment: textFieldVal
	                }
	                kmCommentApi.doRequest(params).success(function(){
	                	$scope.msg = '评论成功';
		                var dialog = ngDialog.open({ 
	                    	template: './views/popup/tips.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
		                $timeout(function() {
		                	ngDialog.close();
		                	$scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);
	                    	$scope.commentField = '';
		                }, 2000);
	                    /*dialog.closePromise.then(function(){
	                    	$scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.comments.articleid);
	                    	$scope.commentField = '';
	                    });*/
	                });

	            }
	        };

	        // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

            // 文章详情操作(点赞、收藏、评论)
            $scope.articleActionFn = function (article, status) {
                if (article.flow == null) {
                    if (status == 0) { // 点赞
                        article.flow = {is_admire: 0};
                        var params = {
                            article_id: article.id,
                            is_admire: 1
                        };
                        flowKmApi.doRequest(params).success(function(){
                        	$scope.msg = '点赞成功';
                            var dialog = ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                	article.admire_count++;
                                article.flow.is_admire = 1;
			                }, 2000);
		                    /*dialog.closePromise.then(function(){
		                    	article.admire_count++;
                                article.flow.is_admire = 1;
		                    });*/
                        });
                    } else if (status == 1) { // 收藏
                        article.flow = {is_collect: 0};
                        var params = {
                            article_id: article.id,
                            is_collect: 1
                        };
                        flowKmApi.doRequest(params).success(function(){
                        	$scope.msg = '收藏成功';
                            var dialog = ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                	article.collect_count++;
                                article.flow.is_collect = 1;
			                }, 2000);
		                    /*dialog.closePromise.then(function(){
		                    	article.collect_count++;
                                article.flow.is_collect = 1;
		                    });*/
                        });                      
                    }

                } else {
                    if (status == 0) {
                        if (article.flow.is_admire == 1) {
                            $scope.msg = '已点赞';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                }, 2000);
                        } else {
                            article.flow.is_admire = 1;
                            flowKmApi.doUpdateRequest(article.flow).success(function(){
		                    	$scope.msg = '点赞成功';
			                    ngDialog.open({ 
			                    	template: './views/popup/tips.html', 
			                    	className: 'ngdialog-theme-default',
			                    	showClose: false,
			                    	scope: $scope 
			                    });			                    
			                    $timeout(function() {
				                	ngDialog.close();
				                	article.admire_count++;
				                }, 2000);			                    
		                    });
                        }
                    } else if (status == 1) {
                        if (article.flow.is_collect == 1) {
                            $scope.msg = '已收藏';
		                    ngDialog.open({ 
		                    	template: './views/popup/tips.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    $timeout(function() {
			                	ngDialog.close();
			                }, 2000);
                        } else {
                            article.flow.is_collect = 1;
                            flowKmApi.doUpdateRequest(article.flow).success(function(){
		                    	$scope.msg = '收藏成功';
			                    ngDialog.open({ 
			                    	template: './views/popup/tips.html', 
			                    	className: 'ngdialog-theme-default',
			                    	showClose: false,
			                    	scope: $scope 
			                    });			                    
			                    $timeout(function() {
				                	ngDialog.close();
			                    	article.collect_count++;
				                }, 2000);
		                    });
                        }                        
                    }
                } 
            }

		}])
		.controller('articleRecommendCtrl', ['$scope', '$rootScope', '$location', 'ngDialog', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 
			function($scope, $rootScope, $location, ngDialog, articleRecommendApi, myCardApi, hotTagApi, flowKmApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// article recommend list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage) {
                articleRecommendApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	        // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

	        // 评论
	        $scope.toComment = function (articleid) {
	        	$location.path('index/km/detail/'+ articleid + '/comment');
	        };
		}])
		.controller('myAdmireCtrl', ['$scope', '$rootScope', '$state', '$location', 'myAdmireApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 
			function($scope, $rootScope, $state, $location, myAdmireApi, articleRecommendApi, myCardApi, hotTagApi){

			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// myAdmire article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myAdmireArticles = {};
            $scope.doRequest = function(status, page, perPage) {
                myAdmireApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myAdmireArticles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.myAdmireArticles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.myAdmireArticles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };
		}])
		.controller('myCommentKmCtrl', ['$scope', '$rootScope', '$location', 'myCommentKmApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 
			function($scope, $rootScope, $location, myCommentKmApi, articleRecommendApi, myCardApi, hotTagApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// myAdmire article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myCommentArticles = {};
            $scope.doRequest = function(status, page, perPage) {
                myCommentKmApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myCommentArticles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.myCommentArticles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.myCommentArticles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };
		}])
		.controller('myCollectKmCtrl', ['$scope', '$rootScope', '$location', 'myCollectKmApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 
			function($scope, $rootScope, $location, myCollectKmApi, articleRecommendApi, myCardApi, hotTagApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// myAdmire article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myCollectArticles = {};
            $scope.doRequest = function(status, page, perPage) {
                myCollectKmApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myCollectArticles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.myCollectArticles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.myCollectArticles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };
		}])
		.controller('trainCtrl', ['$scope', '$rootScope', '$location', 'articleListApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog', 
			function($scope, $rootScope, $location, articleListApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, typeId) {
                articleListApi.doRequest(status, page, perPage, typeId)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, '30');

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, typeId);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search
            $scope.searchFn = function (keyword) {
	            $location.path('index/km/search/'+ keyword);
	        };

	        // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	        // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

	        // 评论
	        $scope.toComment = function (articleid) {
	        	$location.path('index/km/detail/'+ articleid + '/comment');
	        };
		}])
		.controller('shareCtrl', ['$scope', '$rootScope', '$location', 'articleListApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog', 
			function($scope, $rootScope, $location, articleListApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, typeId) {
                articleListApi.doRequest(status, page, perPage, typeId)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, '31');

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, typeId);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search
            $scope.searchFn = function (keyword) {
	            $location.path('index/km/search/'+ keyword);
	        };

	        // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	        // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

	        // 评论
	        $scope.toComment = function (articleid) {
	        	$location.path('index/km/detail/'+ articleid + '/comment');
	        };
		}])
		.controller('standardCtrl', ['$scope', '$rootScope', '$location', 'articleListApi', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'flowKmApi', 'ngDialog', 
			function($scope, $rootScope, $location, articleListApi, articleRecommendApi, myCardApi, hotTagApi, flowKmApi, ngDialog){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// article list
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.articles = {};
            $scope.doRequest = function(status, page, perPage, typeId) {
                articleListApi.doRequest(status, page, perPage, typeId)
                    .success(function(data, stat, headers) {
                        $scope.articles.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.articles.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, '32');

            $scope.changeState = function(status) {                
                $scope.articles.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, typeId);
            };

            // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });

            // search
            $scope.searchFn = function (keyword) {
	            $location.path('index/km/search/'+ keyword);
	        };

	        // search tag
	        $scope.searchTagFn = function (tagId) {
	            $location.path('index/km/searchTagResult/'+ tagId);
	        };

	        // 文章相关操作(点赞、收藏、评论)
	        // 点赞
	        $scope.thumbsFn = function (article) {
	            if (article.flow == null) {
	                article.flow = {is_admire: 0};
	                var params = {
	                    article_id: article.id,
	                    is_admire: 1
	                };
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '点赞成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.admire_count++;
	                });

	            } else {
	                if (article.flow.is_admire == 1) {

	                    $scope.msg = '已点赞';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_admire = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '点赞成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.admire_count++;
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
	                flowKmApi.doRequest(params).success(function(data){
	                	article.flow = data;
	                	$scope.msg = '收藏成功';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });
	                	article.collect_count++;
	                });
	            } else {
	                if (article.flow.is_collect == 1) {

	                    $scope.msg = '已收藏';
	                    ngDialog.open({ 
	                    	template: './views/popup/alert.html', 
	                    	className: 'ngdialog-theme-default',
	                    	showClose: false,
	                    	scope: $scope 
	                    });

	                } else {
	                    article.flow.is_collect = 1;
	                    flowKmApi.doUpdateRequest(article.flow).success(function(){
	                    	$scope.msg = '收藏成功';
		                    ngDialog.open({ 
		                    	template: './views/popup/alert.html', 
		                    	className: 'ngdialog-theme-default',
		                    	showClose: false,
		                    	scope: $scope 
		                    });
		                    article.collect_count++;
	                    });
	                }                
	            }
	        };

	        // 评论
	        $scope.toComment = function (articleid) {
	        	$location.path('index/km/detail/'+ articleid + '/comment');
	        };
		}])
		.controller('kmQueryCtrl', ['$scope', '$rootScope', '$location', '$stateParams', 'articleRecommendApi', 'myCardApi', 'hotTagApi', 'searchKmApi', 
			function($scope, $rootScope, $location, $stateParams, articleRecommendApi, myCardApi, hotTagApi, searchKmApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// search
			$scope.showResult = false;
            $scope.searchFn = function (keyword) {
	            // $location.path('index/km/query/'+ keyword);

	        	// article list
				$scope.page = {
	                "pageSize": 6,
	                "pageNo": 1
	            };
	            $scope.articles = {};
	            $scope.doRequest = function(status, page, perPage, keyword) {
	                searchKmApi.doRequest(status, page, perPage, keyword)
	                    .success(function(data, stat, headers) {
		            		$scope.showResult = true;
	                        $scope.articles.contents = data;
	                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
	                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
	                        $scope.isDataNull = (data.length == 0) ? true : false;
	                    });
	            };
	            $scope.articles.status = ''; //默认显示全部
	            $scope.articles.keyword = $stateParams.keyword;
	            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.articles.keyword);

	            $scope.changeState = function(status) {                
	                $scope.articles.status = status;
	                $scope.page.pageNo = 1;
	                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.articles.keyword);
	            };
	        };


	        // article recommend
            articleRecommendApi.doRequest('', '1', '8').success(function(data){
            	$scope.articleRecommList = data;
            });

            // my card
            myCardApi.doRequest().success(function(data){
            	$scope.myCard = data;
            });

            // hot tag
            hotTagApi.doRequest().success(function(data){
        		$scope.tags = data;
            });
		}])
		.controller('leftbarCtrl', ['$scope', '$rootScope', 'personalCenterApi', function($scope, $rootScope, personalCenterApi){
			$scope.isOpen = true;
			$scope.$watch('isOpen', function(val){
				$rootScope.isOpen = val;
				$rootScope.$broadcast('leftChange', val);
			});
			
			
             if (localStorage['user']) {
                $scope.headimg = JSON.parse(localStorage['user']).avatar;
                $scope.userName = JSON.parse(localStorage['user']).name;
            } else {
                personalCenterApi.doRequest().success(function(data) {
                    var resResult = data;
                    $scope.headimg = resResult.info.avatar;
                    $scope.userName = resResult.info.name;
                });
            }

			$scope.toggleFn = function () {
				$scope.isOpen = !$scope.isOpen;
				if ($scope.isOpen === true) {
					$('.leftbar').animate({width: '190px'}, 300);
				} else {
					$('.leftbar').animate({width: '80px'}, 300);
				}
			};
		}])
		.controller('kmPublishCtrl', ['$scope', '$location', '$timeout', 'ngDialog', 'columnKmApi', 'tagApi', 'articlePublishApi', 'FileUploader', function($scope, $location, $timeout, ngDialog, columnKmApi, tagApi, articlePublishApi, FileUploader){
			// column
			columnKmApi.doRequest().success(function(data){
				$scope.columnList = data;
				$scope.belongColumn = $scope.columnList[0];
				$scope.$watch('belongColumn', function(n) {
					$scope.columnId = n.id;
				});
			});	

			// tags
			tagApi.doRequest().success(function(data){
				$scope.tagList = data;
			});

			// file upload
			var uploader = $scope.uploader = new FileUploader({
				url: 'http://qyftapi.vikduo.com/knowledge/article/upload', // 正式环境
				// url: 'http://devqyftapi.snsshop.net/knowledge/article/upload', // 测试环境
				removeAfterUpload: true 
			});

			uploader.onSuccessItem = function(fileItem, response, status, headers) {
	            // console.info('onSuccessItem', fileItem, response, status, headers);
	            $scope.msg = '上传成功';
                ngDialog.open({ 
                	template: './views/popup/tips.html', 
                	className: 'ngdialog-theme-default',
                	showClose: false,
                	scope: $scope 
                });
                $timeout(function() {
                	ngDialog.close();
                }, 2000);
                // console.log(response);
                $scope.annexes = response.annexes;
                $scope.uuid = response.uuid;
	        };
	        uploader.onErrorItem = function(fileItem, response, status, headers) {
	            // console.info('onErrorItem', fileItem, response, status, headers);
	        };
	        uploader.onCancelItem = function(fileItem, response, status, headers) {
	            // console.info('onCancelItem', fileItem, response, status, headers);
	        };
	        uploader.onCompleteItem = function(fileItem, response, status, headers) {
	            // console.info('onCompleteItem', fileItem, response, status, headers);
	        };

			var controller = $scope.controller = {
				isImage: function(item) {
					var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
					return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
				}
			};

			// form
			$scope.showErrorTitle = false;
			$scope.showErrorTag = false;
			$scope.showErrorDesc = false;
			$scope.showErrorCon = false;
			$scope.checkTitle = function(){
				if ($scope.kmPublishForm.kmArticleTitle.$invalid) {
					$scope.showErrorTitle = true;
				} else {
					$scope.showErrorTitle = false;
				}
			};

			$scope.checkDesc = function(){
				if ($scope.kmPublishForm.kmArticleDesc.$invalid) {
					$scope.showErrorDesc = true;
				} else {
					$scope.showErrorDesc = false;
				}
			};

			$scope.save = function(){
				var columnId = $('#selectedColumnId').val();
				var checkedObj = document.getElementsByName('kmArticleTag');
				var checkedVal = [];
				angular.forEach(checkedObj, function(item){
					if (item.checked) {
						/*console.log(item);
						console.log($(item).attr('data-corp-id'));*/
						checkedVal.push({
								id: item.id, 
								name: item.value,
								corp_id: $(item).attr('data-corp-id'),
								create_time: $(item).attr('data-create-time'),
								en_name: $(item).attr('data-en-name'),
								sort: $(item).attr('data-sort'),
								update_time: $(item).attr('data-update-time') 
							});
					}
				});

				if ($scope.kmPublishForm.kmArticleTitle.$invalid) {
					$scope.showErrorTitle = true;
				} else {
					$scope.showErrorTitle = false;
					if (checkedVal.length == 0) {
						$scope.showErrorTag = true;
					} else {
						$scope.showErrorTag = false;
						if ($scope.kmPublishForm.kmArticleDesc.$invalid) {
							$scope.showErrorDesc = true;
						} else {
							$scope.showErrorDesc = false;
							if ($('#kmArticleCon').html() == null || $('#kmArticleCon').html().length == 0) {
								$scope.showErrorCon = true;
							} else {
								$scope.showErrorCon = false;
								// submit
								var annexes = $scope.annexes || ''
								var content = $('#kmArticleCon').html();
								var publishObj = {
									title: $scope.kmArticleTitle,
									content: content,
									type_id: columnId,
									description: $scope.kmArticleDesc,
									tages: checkedVal,
									annexes: annexes,
									uuid: $scope.uuid
								};
								articlePublishApi.doRequest(publishObj).success(function(data){
									$scope.msg = '发文成功';
				                    var dialog = ngDialog.open({ 
				                    	template: './views/popup/alert.html', 
				                    	className: 'ngdialog-theme-default',
				                    	showClose: false,
				                    	scope: $scope 
				                    });
				                    dialog.closePromise.then(function(){
					                	$location.path('/index/km/detail/'+ data.id);
				                    });
								});
							}
						}
					}
				}
			};
		}])
		.controller('kmPublishEditCtrl', ['$scope', '$location', '$stateParams', '$timeout', 'ngDialog', 'columnKmApi', 'tagApi', 'articlePublishApi', 'FileUploader', 'articleInfoEditApi', 
			function($scope, $location, $stateParams, $timeout, ngDialog, columnKmApi, tagApi, articlePublishApi, FileUploader, articleInfoEditApi){
			// column
			columnKmApi.doRequest().success(function(data){
				$scope.columnList = data;
				$scope.belongColumn = $scope.columnList[0];
				$scope.$watch('belongColumn', function(n) {
					$scope.columnId = n.id;
				});
			});	

			// tags
			tagApi.doRequest().success(function(data){
				$scope.tagList = data;
				angular.forEach($scope.tagList, function(item) {
					item.checked = false;
				});
			});

			// file upload
			var uploader = $scope.uploader = new FileUploader({
				url: 'http://qyftapi.vikduo.com/knowledge/article/upload', // 正式环境
				// url: 'http://devqyftapi.snsshop.net/knowledge/article/upload', // 测试环境
				removeAfterUpload: true 
			});

			uploader.onSuccessItem = function(fileItem, response, status, headers) {
	            // console.info('onSuccessItem', fileItem, response, status, headers);
	            $scope.msg = '上传成功';
                ngDialog.open({ 
                	template: './views/popup/tips.html', 
                	className: 'ngdialog-theme-default',
                	showClose: false,
                	scope: $scope 
                });
                $timeout(function() {
                	ngDialog.close();
                }, 2000);
                // console.log(response);
                $scope.annexes = response.annexes;
                $scope.uuid = response.uuid;
	        };
	        uploader.onErrorItem = function(fileItem, response, status, headers) {
	            // console.info('onErrorItem', fileItem, response, status, headers);
	        };
	        uploader.onCancelItem = function(fileItem, response, status, headers) {
	            // console.info('onCancelItem', fileItem, response, status, headers);
	        };
	        uploader.onCompleteItem = function(fileItem, response, status, headers) {
	            // console.info('onCompleteItem', fileItem, response, status, headers);
	        };

			var controller = $scope.controller = {
				isImage: function(item) {
					var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
					return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
				}
			};

			// article info
			articleInfoEditApi.doRequest($stateParams.articleId).success(function(data) {
				$scope.kmArticleTitle = data.title;
				$scope.columnId = data.type_id;
				angular.forEach($scope.columnList, function(item) {
					if (item.id == $scope.columnId) {
						$scope.belongColumn = item;
					}
				});
				$scope.kmArticleDesc = data.description;
				$('#kmArticleCon').html(data.content);
				var tagsChecked = data.tages;
				angular.forEach($scope.tagList, function(item) {
					angular.forEach(tagsChecked, function(itemInner) {
						if (item.id == itemInner.id) {
							item.checked = true;
						}
					})
				})
				/*angular.forEach(tagsChecked, function(item) {
					$('input[type="checkbox"]#'+ item.id).prop('checked', true);
				});*/
			});

			// form
			$scope.showErrorTitle = false;
			$scope.showErrorTag = false;
			$scope.showErrorDesc = false;
			$scope.showErrorCon = false;
			$scope.checkTitle = function(){
				if ($scope.kmPublishForm.kmArticleTitle.$invalid) {
					$scope.showErrorTitle = true;
				} else {
					$scope.showErrorTitle = false;
				}
			};

			$scope.checkDesc = function(){
				if ($scope.kmPublishForm.kmArticleDesc.$invalid) {
					$scope.showErrorDesc = true;
				} else {
					$scope.showErrorDesc = false;
				}
			};

			$scope.save = function(){
				var columnId = $('#selectedColumnId').val();
				var checkedObj = document.getElementsByName('kmArticleTag');
				var checkedVal = [];
				angular.forEach(checkedObj, function(item){
					if (item.checked) {
						/*console.log(item);
						console.log($(item).attr('data-corp-id'));*/
						checkedVal.push({
								id: item.id, 
								name: item.value,
								corp_id: $(item).attr('data-corp-id'),
								create_time: $(item).attr('data-create-time'),
								en_name: $(item).attr('data-en-name'),
								sort: $(item).attr('data-sort'),
								update_time: $(item).attr('data-update-time') 
							});
					}
				});

				if ($scope.kmPublishForm.kmArticleTitle.$invalid) {
					$scope.showErrorTitle = true;
				} else {
					$scope.showErrorTitle = false;
					if (checkedVal.length == 0) {
						$scope.showErrorTag = true;
					} else {
						$scope.showErrorTag = false;
						if ($scope.kmPublishForm.kmArticleDesc.$invalid) {
							$scope.showErrorDesc = true;
						} else {
							$scope.showErrorDesc = false;
							if ($('#kmArticleCon').html() == null || $('#kmArticleCon').html().length == 0) {
								$scope.showErrorCon = true;
							} else {
								$scope.showErrorCon = false;
								// submit
								var annexes = $scope.annexes || ''
								var content = $('#kmArticleCon').html();
								var publishObj = {
									title: $scope.kmArticleTitle,
									content: content,
									type_id: columnId,
									description: $scope.kmArticleDesc,
									tages: checkedVal,
									annexes: annexes,
									uuid: $scope.uuid
								};
								articlePublishApi.doUpdateRequest($stateParams.articleId, publishObj).success(function(data){
									$scope.msg = '发布成功';
				                    var dialog = ngDialog.open({ 
				                    	template: './views/popup/alert.html', 
				                    	className: 'ngdialog-theme-default',
				                    	showClose: false,
				                    	scope: $scope 
				                    });
				                    dialog.closePromise.then(function(){
					                	$location.path('/index/km/detail/'+ data.id);
				                    });
								});
							}
						}
					}
				}
			};

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
	    .directive('onFinished', function ($rootScope) {
		    return {
		        restrict: 'A',
		        link: function(scope, element, attr) {
		            if (scope.$last === true) {
		                $rootScope.$broadcast('onFinished');
		            }
		        }
		    };
		})
		.directive('changeMainViewWidth', function(){
			return {
				restrict: 'A',
				scope: {
					isOpen: '='
				},
				link: function (scope, element, attr) {
					// scope.isOpen = scope.isOpen || false;

					scope.$watch('isOpen', function(val){
						if (val) {
							element.css('margin-left', 190);
							element.find('.container').css('width', $(window).width() - 190);
						} else {
							element.css('margin-left', 80);
							element.find('.container').css('width', $(window).width() - 80);						
						}
					});
				}
			}
		})
		.directive('backToTop', function(){
			return {
				restrict: 'A',
				link: function (scope, element, attr) {
					$(element).hide();
					$(window).on('scroll', function(){
						if ($(this).scrollTop() > 600) {
							$(element).fadeIn(200);
						} else {
							$(element).fadeOut(200);
						}
					});
					element.bind('click', function(){
						$('html, body').animate({scrollTop: 0}, 30);
					})
				}
			}
		})
		.directive('hoverTips', function() {
			return {
				restrict: 'A',
				link: function(scope, element, attr) {
					element.bind('mouseover', function() {
						console.log(element.children());
						element.children().eq(1).show();
					});
					element.bind('mouseout', function() {
						element.children().eq(1).hide();
					});
				}
			}
		})
	    /*.directive('setMainMinHeight', function($timeout) {
	    	return {
	    		restrict: 'A',
	    		link: function(scope, element, attr) {
	    			scope.$on('onFinished', function() {
	    				$timeout(function() {
			    			var sidebar = $('.sidebar'),
			    				sidebarHeight = sidebar.height();
		    				// console.log(sidebarHeight);
		    				$('.container').css('min-height', sidebarHeight + 20);

	    				}, 0);
	    			});
    				// element.css('min-height', winWidth - 80 - 320 - 30);
	    		}
	    	}
	    })*/
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

	    }])
	    /*.service('leftbarAction', [function(){
	    	return {
	    		isFold: true,
	    		isOpen: false,
	    		leftBarOpen: function () {
	    			this.isFold = false;
	    			this.isOpen = true;
					$('.content-page').css('margin-left', 200);
					$('.container').css('width', $(window).width() - 200);	    			
	    		},
	    		leftBarFold: function () {
	    			this.isFold = true;
	    			this.isOpen = false;
					$('.content-page').css('margin-left', 80);
					$('.container').css('width', $(window).width() - 80);

	    		}
	    	}
	    }])*/
}).call(this);