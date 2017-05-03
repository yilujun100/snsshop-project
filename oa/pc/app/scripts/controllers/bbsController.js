/*
 * 论坛控制器
 * auth: yilj@snsshop.cn
 * date: 2016-11-8
 */
(function() {
    'use strict';
    angular.module('bbs.controller', [])
        .controller('bbsCtrl', bbsCtrl)
        .controller('circleCtrl', circleCtrl)
        .controller('circleRecommCtrl', circleRecommCtrl)
        .controller('detailCtrl', detailCtrl)
        .controller('allCirclesCtrl', allCirclesCtrl)
        .controller('postCtrl', postCtrl)
        .controller('postEditCtrl', postEditCtrl)
        .controller('myCircleCtrl', myCircleCtrl)
        .controller('myPostCtrl', myPostCtrl)
        .controller('myReplyCtrl', myReplyCtrl)
        .controller('myCollectCtrl', myCollectCtrl)
        .controller('postRecommCtrl', postRecommCtrl)
        .controller('manageCtrl', manageCtrl)
        .controller('searchCtrl', searchCtrl)
        .directive('setMainMinHeight', function($timeout) {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    var win = $(window),
                        winHeight = win.height(),
                        ele = $(element),
                        containerWrap = ele.find('.container'),
                        sidebarWrap = ele.find('.sidebar'),
                        mainWrap = ele.find('.main');
                    containerWrap.css('min-height', winHeight - 120);

                    $timeout(function() {
                        if (winHeight < sidebarWrap.height()) {
                            containerWrap.css('min-height', sidebarWrap.height() + 20);
                        }
                    }, 1000);

                    /*scope.$on('onFinished', function() {
                    	if (containerWrap.height() < sidebarWrap.height()) {
                    		containerWrap.css('height', sidebarWrap.height() + 20);
                    	}						
                    })*/
                    $timeout(function() {
                        if (containerWrap.height() < sidebarWrap.height()) {
                            containerWrap.css('min-height', sidebarWrap.height() + 20);
                        }
                    }, 1000);
                }
            }
        })
        .directive('sortHightlight', function() {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.bind('click', function() {
                        element.addClass('circle-on').siblings().removeClass('circle-on');
                    });
                }
            }
        })
        .directive('contenteditable', function(apiUrl) {
            return {
                restrict: 'A',
                require: '?ngModel',
                link: function(scope, element, attrs, ngModel) {
                    // 初始化 编辑器内容
                    if (!ngModel) {
                        return;
                    } // do nothing if no ng-model
                    // Specify how UI should be updated
                    ngModel.$render = function() {
                        element.html(ngModel.$viewValue || '');
                    };
                    // Listen for change events to enable binding
                    element.on('blur keyup change', function() {
                        scope.$apply(readViewText);
                    });
                    // No need to initialize, AngularJS will initialize the text based on ng-model attribute
                    // Write data to the model
                    function readViewText() {
                        var html = element.html();
                        // When we clear the content editable the browser leaves a <br> behind
                        // If strip-br attribute is provided then we strip this out
                        if (attrs.stripBr && html === '<br>') {
                            html = '';
                        }
                        ngModel.$setViewValue(html);
                    }

                    // 创建编辑器
                    var editor = new wangEditor(element);
                    // 自定义菜单
                    editor.config.menus = [
                        'bold',
                        'underline',
                        'italic',
                        'strikethrough',
                        'forecolor',
                        'bgcolor',
                        '|',
                        'fontfamily',
                        'fontsize',
                        'head',
                        'unorderlist',
                        'orderlist',
                        'alignleft',
                        'aligncenter',
                        'alignright',
                        '|',
                        'img'
                    ];
                    // 上传图片					
                    var token = window.localStorage.getItem('token');
                    // editor.config.uploadImgUrl = 'http://devqyftapi.snsshop.net/bbs/article/upload?PHPSESSID=' + token; // 测试环境
                    editor.config.uploadImgUrl = apiUrl+'/bbs/article/upload?PHPSESSID='+ token; // 正式环境
                    editor.config.uploadImgFileName = 'myFile';
                    editor.config.hideLinkImg = true;
                    editor.create();
                }
            };
        })
        .directive('hoverEvent', function() {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.bind('mouseover', function() {
                        element.html('点击退圈');
                    });
                    element.bind('mouseout', function() {
                        element.html('已加入');
                    });
                }
            }
        })
        .directive('qqFace', function() {
        	return {
        		restrict: 'A',
        		link: function(scope, element, attr) {
					$(element).qqface({
						imgPath: './images/biaoqing/',
						handle: $(element),
					});
        		}
        	}
        })
        .directive('textField', function($parse) {
        	return {
        		restrict: 'A',
        		require: '?ngModel',
        		link: function(scope, element, attrs, ngModel) {
    					
    				element.on('change blur focus', function() {
    					$parse(attrs['ngModel']).assign(scope, element[0].value);  
    				});
        		}
        	}
        })        
		.directive('fixedComment', function($location, $anchorScroll){
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
			            $location.hash('comment');
			            $anchorScroll();
			            $('#commentField').focus();
					})
				}
			}
		})
        .filter('replaceFace', function() {
    		return function(faces) {
    			var faceMap = [
					'weixiao,微笑',
					'piezui,撇嘴',
					'se,色',
					'fadai,发呆',
					'deyi,得意',
					'liulei,流泪',
					'haixiu,害羞',
					'bizui,闭嘴',
					'shui,睡',
					'daku,大哭',
					'ganga,尴尬',
					'fanu,发怒',
					'tiaopi,调皮',
					'ciya,呲牙',
					'jingya,惊讶',
					'nanguo,难过',
					'ku,酷',
					'lenghan,冷汗',
					'zhuakuang,抓狂',
					'tu,吐',
					'touxiao,偷笑',
					'keai,可爱',
					'baiyan,白眼',
					'aoman,-傲慢',
					'jie,饥饿',
					'kun,困',
					'jingkong,惊恐',
					'liuhan,流汗',
					'hanxiao,憨笑',
					'dabing,大兵',
					'fendou,奋斗',
					'zhouma,咒骂',
					'yiwen,疑问',
					'xu,嘘',
					'yun,晕',
					'zhemo,折磨',
					'shuai,衰',
					'kulou,骷髅',
					'qiaoda,敲打',
					'zaijian,再见',
					'cahan,擦汗',
					'koubi,抠鼻',
					'guzhang,鼓掌',
					'qiudale,糗大了',
					'huaixiao,坏笑',
					'zuohengheng,左哼哼',
					'youhengheng,右哼哼',
					'haqian,哈欠',
					'bishi,鄙视',
					'weiqu,委屈',
					'kuaikule,快哭了',
					'yinxian,阴险',
					'qinqin,亲亲',
					'xia,吓',
					'kelian,可怜',
					'caidao,菜刀',
					'xigua,西瓜',
					'pijiu,啤酒',
					'lanqiu,篮球',
					'pingpang,乒乓',
					'kafei,咖啡',
					'fan,饭',
					'zhutou,猪头',
					'meigui,玫瑰',
					'diaoxie,凋谢',
					'shiai,示爱',
					'aixin,爱心',
					'xinsui,心碎',
					'dangao,蛋糕',
					'shandian,闪电',
					'zhadan,炸弹',
					'dao,刀',
					'zuqiu,足球',
					'piaochong,瓢虫',
					'bianbian,便便',
					'yueliang,月亮',
					'taiyang,太阳',
					'liwu,礼物',
					'yongbao,拥抱',
					'qiang,强',
					'ruo,弱',
					'woshou,握手',
					'shengli,胜利',
					'baoquan,抱拳',
					'gouyin,勾引',
					'quantou,拳头',
					'chajin,差劲',
					'aini,爱你',
					'no,NO',
					'ok,OK'
    			];

    			var textField = faces;
    			$.map(faceMap, function(item, index) {
					var itemFaceName = item.split(',');
					if (textField.indexOf(itemFaceName[1]) != -1) {
						textField = textField.replace(itemFaceName[1], itemFaceName[0]);
					}
				});

				textField = textField.replace(/\</g, '&lt;');
                textField = textField.replace(/\>/g, '&gt;');
                textField = textField.replace(/\n/g, '<br/>');
				textField = textField.replace(/\[:([\s\S]+?)\]/g, '<img src="./images/biaoqing/$1.gif" />');

				return textField;
    		}
        });

    // 论坛首页
    function bbsCtrl($scope, $rootScope, $window, $location, $timeout, ngDialog, circleColumnApi, circleNotJoinedApi, articleApi, userInfoApi, articleRecommApi) {

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // recommend circle
        circleNotJoinedApi.doRequest().success(function(data) {
            $scope.recommendCircleList = data;
        });

        // bbs index article
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            articleApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 圈子首页
    function circleCtrl($scope, $rootScope, $stateParams, $location, $timeout, ngDialog, circleIndexApi, circleJoinedApi, circleColumnApi, userApi, userInfoApi, articleRecommApi) {

        // joined circle
        circleJoinedApi.doRequest().success(function(data) {
            $scope.joinedCircle = data;
            $scope.isJoinedCircle = false;
            angular.forEach($scope.joinedCircle, function(item) {
                if (item.id == $stateParams.id) {
                    $scope.joinedCircleInfo = item;
                    $scope.isJoinedCircle = true;
                }
            });
            if (!$scope.isJoinedCircle) {
                circleColumnApi.doRequest().success(function(data) {
                    $scope.allCircles = data;
                    angular.forEach($scope.allCircles, function(item) {
                        if (item.id == $stateParams.id) {
                            $scope.notJoinedCircleInfo = item;
                        }
                    });
                });
            }
        });

        // circle article
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.circleId = $stateParams.id;
        $scope.doRequest = function(page, perPage, circleId) {
            circleIndexApi.doRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doPopularRequest = function(page, perPage, circleId) {
            circleIndexApi.doPopularRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doCommentRequest = function(page, perPage, circleId) {
            circleIndexApi.doCommentRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);

        // article sort
        $scope.default = function() {
            $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);
        };

        $scope.popularity = function() {
            $scope.doPopularRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);
        };

        $scope.commentNum = function() {
            $scope.doCommentRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);
        };

        // join circle
        $scope.join = function(circleInfo) {
            var params = {
                coterie_id: circleInfo.id
            };
            userApi.doRequest(params).success(function() {
                $scope.msg = '圈子加入成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $scope.isJoinedCircle = true;
            });
        };

        // exit circle
        $scope.exit = function(circleInfo) {
            userApi.doDeleteRequest(circleInfo.myCoterie).success(function() {
                $scope.msg = '圈子退出成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $scope.isJoinedCircle = false;
            });
        };

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 推荐圈子
    function circleRecommCtrl($scope, $rootScope, $stateParams, $location, $timeout, ngDialog, circleInfoApi, circleRecommArticleApi, circleJoinedApi, circleColumnApi, userApi, userInfoApi, articleRecommApi) {
        // joined circle
        /*circleJoinedApi.doRequest().success(function(data) {
        	$scope.joinedCircle = data;
        	$scope.isJoinedCircle = false;
        	angular.forEach($scope.joinedCircle, function(item) {
        		if (item.id == $stateParams.id) {
        			$scope.joinedCircleInfo = item;
        			$scope.isJoinedCircle = true;
        		}
        	});
        	if (!$scope.isJoinedCircle) {
        		circleColumnApi.doRequest().success(function(data) {
        			$scope.allCircles = data;
        			angular.forEach($scope.allCircles, function(item) {
        				if (item.id == $stateParams.id) {
        					$scope.notJoinedCircleInfo = item;
        				}
        			});
        		});
        	}
        });*/
        // circle info
        circleInfoApi.doRequest($stateParams.id).success(function(data) {
            $scope.circleInfo = data;
            circleJoinedApi.doRequest().success(function(data1) {
                $scope.joinedCircle = data1;
                $scope.isJoinedCircle = false;
                angular.forEach($scope.joinedCircle, function(item) {
                    if (item.id == $stateParams.id) {
                        $scope.isJoinedCircle = true;
                    }
                });
            })
        });

        // join circle
        $scope.join = function(circleInfo) {
            var params = {
                coterie_id: circleInfo.id
            };
            userApi.doRequest(params).success(function(data) {
                $scope.msg = '圈子加入成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                circleJoinedApi.doRequest().success(function(data) {
                    angular.forEach(data, function(item) {
                        if (item.id == $stateParams.id) {
                            $scope.joinedCircleInfo = item;
                        }
                    })
                });
                $scope.isJoinedCircle = true;

                $scope.responseResult = data;
                console.log($scope.responseResult);
            });
        };

        // exit circle
        $scope.count = 0;
        $scope.exit = function(circleInfo) {
            var params;
            $scope.count++;
            if ($scope.count > 1) {
                params = $scope.responseResult;
            } else {
                params = circleInfo.myCoterie != null ? circleInfo.myCoterie : $scope.responseResult;
            }

            userApi.doDeleteRequest(params).success(function() {
                $scope.msg = '圈子退出成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                circleColumnApi.doRequest().success(function(data) {
                    angular.forEach(data, function(item) {
                        if (item.id == $stateParams.id) {
                            $scope.notJoinedCircleInfo = item;
                        }
                    });
                });
                $scope.isJoinedCircle = false;
            });
        };

        // recommend circle article
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.circleId = $stateParams.id;

        $scope.sortType = '';
        $scope.sortDoRequest = function(page, perPage, circleId) {
            switch ($scope.sortType) {
                case '':
                    $scope.doRequest(page, perPage, circleId);
                    break;
                case 'defalut':
                    $scope.doRequest(page, perPage, circleId);
                    break;
                case 'popularity':
                    $scope.doPopularRequest(page, perPage, circleId);
                    break;
                case 'commentNum':
                    $scope.doCommentRequest(page, perPage, circleId);
                    break;
                default:
                	$scope.doRequest(page, perPage, circleId);
                	break;
            }
        };

        $scope.doRequest = function(page, perPage, circleId) {
            circleRecommArticleApi.doRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doPopularRequest = function(page, perPage, circleId) {
            circleRecommArticleApi.doPopularRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doCommentRequest = function(page, perPage, circleId) {
            circleRecommArticleApi.doCommentRequest(page, perPage, circleId)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                })
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);

        // article sort
        $scope.default = function() {
            $scope.sortType = 'defalut';
            $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);

        };

        $scope.popularity = function() {
            $scope.sortType = 'popularity';
            $scope.doPopularRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);
        };

        $scope.commentNum = function() {
            $scope.sortType = 'commentNum';
            $scope.doCommentRequest($scope.page.pageNo, $scope.page.pageSize, $scope.circleId);
        };

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 文章详情
    function detailCtrl($scope, $rootScope, $state, $stateParams, $location, $timeout, $anchorScroll, ngDialog, postDetailApi, flowApi, commentFlowApi, commentApi, commentAddApi, userInfoApi, circleColumnApi, articleRecommApi, commentReplyApi) {
        // article info
        postDetailApi.doRequest($stateParams.articleId).success(function(data) {
            $scope.articleData = data;
        });

        // article actions
        $scope.articleLikedFn = function(articleId) {
            var params = {
                id: articleId,
                type: 2
            };
            flowApi.doUpdateRequest(articleId, params).success(function(data) {
                if (data.status == 1) { // status => 1(点赞成功,奖励1积分) 2(已点赞)
                    $scope.msg = '点赞成功,奖励1积分';
                    ngDialog.open({
                        template: './views/popup/tips.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    $timeout(function() {
                        ngDialog.close();
                    }, 2000);
                    $scope.articleData.admire_count++;
                    $scope.articleData.is_admire = true;
                } else if (data.status == 2) {
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
                }
            });
        };

        $scope.articleCollectFn = function(articleId) {
            var params = {
                id: articleId,
                type: 1
            };
            flowApi.doUpdateRequest(articleId, params).success(function(data) {
                if (data.status == 1) { // status => 1(收藏成功) 2(已收藏)
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
                    $scope.userInfo.collect_count++;
                    $scope.articleData.collect_count++;
                    $scope.articleData.is_collect = true;
                } else if (data.status == 2) {
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
                }
            });
        };

        $scope.gotoCommentFn = function() {
            $location.hash('comment');
            $anchorScroll();
        };

        // comment list
        /*var target = [];

        function fetch(data, comment){
        	if(data && data.length > 0){
        		angular.forEach(data, function(item) {
        			fetch(item, comment);						
        		});
        	} else {
        		if (data.comments && data.comments.length > 0) {
        			angular.forEach(data.comments, function(item) {
        				item.replyUserName = data.user_name;
        			});
        			fetch(data.comments, comment);						
        		}
        		var temp = angular.copy(data);
        		delete temp.comments;
        		// temp.toUser = comment.user_name;
        		comment.replyList.push(temp);	
        	}
        }*/
        function fetch(data, parent, comment) {
            if (data && data.length > 0) {
                angular.forEach(data, function(item) {
                    item.parent_user_name = parent.user_name;
                    item.replyFold = true;
                    fetch(item, item, comment);
                });
            } else {
                if (data.comments && data.comments.length > 0) {
                    fetch(data.comments, data, comment);
                }
                var temp = angular.copy(data);
                delete temp.comments;
                comment.commentList.push(temp);
            }
        }

        /*angular.forEach(data, function(obj) {
        	if (obj.comments && obj.comments.length > 0) {
        		obj.commentList = [];
        		fetch(obj.comments, obj, obj);
        	}
        });*/
        /*function replay(users) {

        	for (var i in users) {
        		if (users[i].comments.length > 0) {
        			var list = [];
        			users[i].comments = replyList(users[i]);
        		}
        	}

        	function replyList(data) {
        		var reply_user_name = data.user_name;
        		if (data.comments.length > 0) {
        			var comments = data.comments;
        			for (var i in comments) {
        				comments[i].reply_user_name = reply_user_name;
        				comments[i].replyFold = true;
        				list.push(comments[i]);
        				if (comments[i].comments.length > 0) {
        					replyList(comments[i]);
        				}
        				delete comments[i].comments;
        			}
        		}
        		return list;
        	}
        	return users;
        }*/

        /*angular.forEach(data, function(obj) {
        	if (obj.comments && obj.comments.length > 0) {
        		obj.commentList = [];
        		fetch(obj.comments, obj);						
        	}					
        });	*/
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.articleId = $stateParams.articleId;
        $scope.doRequest = function(page, perPage, articleId) {
            commentReplyApi.doRequest(page, perPage, articleId)
                .success(function(data, stat, headers) {
                    $scope.commentList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');

                    angular.forEach($scope.commentList, function(item) {
                        item.commentFold = true;
                        item.isShow = true;
                        /*if (item.comments.length > 0) {                    		
                        	angular.forEach(item.comments, function(itemInner) {
                        		itemInner.replyFold = true;
                        	});
                        }*/
                        /*if (item.comments && item.comments.length > 0) {
                        	item.replyList = [];
                        	fetch(item.comments, item);
                        	angular.forEach(item.replyList, function(itemInner) {
                        		itemInner.replyFold = true;
                        	})
                        }*/
                        if (item.comments && item.comments.length > 0) {
                            item.commentList = [];
                            fetch(item.comments, item, item);
                        }

                    });
                    console.log($scope.commentList);
                })
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.articleId);

        // comment like
        $scope.commentLikedFn = function(commentId) {
            var params = {
                id: commentId,
                type: 3
            };
            commentFlowApi.doRequest(commentId, params).success(function(data) {
                if (data.status == 1) { // status => 1(点赞成功,奖励1积分) 2(已点赞)
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
                    angular.forEach($scope.commentList, function(item) {
                        if (item.id == params.id) {
                            item.admire_count++;
                            item.flow = { type: 3 };
                        }
                    });
                } else if (data.status == 2) {
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
                }
            });
        };

        // add comment
        $scope.commentSubmitFn = function(inputVal) {
        	console.log(inputVal);
            if (inputVal == undefined || inputVal == '') {
                $scope.msg = '请添加评论内容';
                var dialog = ngDialog.open({
                    template: './views/popup/alert.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                dialog.closePromise.then(function() {
                    $('#commentField').focus();
                });
            } else {
                var params = {
                    article_id: $stateParams.articleId,
                    comment: inputVal
                }
                commentAddApi.doUpdateRequest(params).success(function() {
                    $scope.msg = '评论成功,奖励1积分';
                    ngDialog.open({
                        template: './views/popup/tips.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    /*$scope.commentContent = '';
                    $scope.articleData.comment_count++;*/
                });
                $timeout(function() {
                    ngDialog.close();
                    $state.reload();
                }, 2000);
            }
        };

        // 互评 
        $scope.doCommentOpen = function(comment) {
            angular.forEach($scope.commentList, function(item) {
                if (item.id == comment.id) {
                    item.commentFold = false;
                    item.isShow = false;
                }
            });
        };

        $scope.doCommentFold = function(comment) {
            angular.forEach($scope.commentList, function(item) {
                if (item.id == comment.id) {
                    item.commentFold = true;
                    item.isShow = true;
                }
            });
        };

        $scope.doReplyOpen = function(reply) {
            angular.forEach($scope.commentList, function(item) {
                angular.forEach(item.commentList, function(itemInner) {
                    if (itemInner.id == reply.id) {
                        itemInner.replyFold = false;
                    }
                });
            });
        };

        $scope.doReplyFold = function(reply) {
            angular.forEach($scope.commentList, function(item) {
                angular.forEach(item.commentList, function(itemInner) {
                    if (itemInner.id == reply.id) {
                        itemInner.replyFold = true;
                    }
                });
            });
        };

        $scope.doComment = function(commentContent, comment) {
            if (commentContent == undefined || commentContent == '') {
                $scope.msg = '请添加评论内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                    $('#commentCon' + comment.id).focus();
                }, 2000);
            } else {
                var params = {
                    article_id: $scope.articleId,
                    comment: commentContent,
                    reply_comment_id: comment.id
                };
                commentAddApi.doUpdateRequest(params).success(function(data) {
                    var responseResult = data;
                    responseResult.replyFold = true;
                    responseResult.parent_user_name = comment.user_name;
                    if (comment.commentList && comment.commentList.length > 0) {
                        comment.commentList.push(responseResult);
                    } else {
                        comment.commentList = [];
                        comment.commentList.push(responseResult);
                    }
                    $('#commentCon' + data.reply_comment_id).val('');
                });
            }
        };

        $scope.doReplyConfirm = function(replyContent, reply, comment) {
            if (replyContent == undefined || replyContent == '') {
                $scope.msg = '请添加回复内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                    $('#replyContent' + reply.id).focus();
                }, 2000);
            } else {
                var params = {
                    article_id: $scope.articleId,
                    comment: replyContent,
                    reply_comment_id: reply.id
                };
                commentAddApi.doUpdateRequest(params).success(function(data) {
                    // reply.comments.push(data);
                    /*angular.forEach($scope.commentList, function(item) {
        				if (item.id == reply.reply_comment_id) {
        					item.comments.push(data);
        				}
		        	});*/
                    var responseResult = data;
                    responseResult.replyFold = true;
                    responseResult.parent_user_name = reply.user_name;
                    comment.commentList.push(responseResult);
                    $('#replyContent' + data.reply_comment_id).val('');
                    reply.replyFold = true;
                });
            }
        };


        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 所有圈子
    function allCirclesCtrl($scope, $rootScope, $state, $stateParams, $location, $timeout, ngDialog, circleJoinedApi, circleNotJoinedApi, userApi, circleColumnApi, userInfoApi, articleRecommApi) {
        // joined circle
        circleJoinedApi.doRequest().success(function(data) {
            $scope.joinedCircleList = data;
            angular.forEach($scope.joinedCircleList, function(item) {
                item.status = true;
            });
        });

        // not joined circle
        circleNotJoinedApi.doRequest().success(function(data) {
            $scope.notJoinedCircleList = data;
            angular.forEach($scope.notJoinedCircleList, function(item) {
                item.status = false;
            });
        });

        // join circle
        $scope.join = function(category) {
            var params = {
                coterie_id: category.id
            };
            userApi.doRequest(params).success(function() {
                $scope.msg = '圈子加入成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                    $state.reload();
                }, 2000);
            });
        };

        // exit circle
        $scope.exit = function(category) {
            userApi.doDeleteRequest(category.myCoterie).success(function() {
                $scope.msg = '圈子退出成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                /*dialog.closePromise.then(function() {
                    $state.reload();
                });*/
                
                $timeout(function() {
                    ngDialog.close();
                    $state.reload();
                }, 2000);
            });
        };

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 发帖
    function postCtrl($scope, $rootScope, $location, ngDialog, circleColumnApi, userInfoApi, newPostApi) {
        // belong circle
        circleColumnApi.doRequest().success(function(data) {
            $scope.belongCircleList = data;
            $scope.belongCircle = $scope.belongCircleList[0];
            $scope.$watch('belongCircle', function(n) {
                $scope.circleId = n.id;
            });
        });

        // poster
        userInfoApi.doRequest().success(function(data) {
            $scope.poster = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // form validate
        $scope.isSelectedCircle = true;
        $scope.showErrorTitle = false;
        $scope.showErrorTitleLimit = false;
        $scope.showErrorContent = false;
        $scope.showErrorContentLimit = false;
        // $scope.postTitle;
        $scope.postContent = '';
        $scope.alreadyInputCon = 0;
        $scope.canInputCon = 10000;

        $scope.$watch('postContent', function(newVal, oldVal) {
        	$scope.wordCount($scope.postContent);
        });

        $scope.wordCount = function(val) {
        	var wordContent = val.replace(/<.*?>/ig, '');
        	wordContent = wordContent.replace(/&nbsp;/g, 'S');
        	var realLenght = 0;
        	for (var i = 0, len = wordContent.length; i < len; i++) {
        		realLenght += 1;
        	}
        	if (realLenght > 10000) {
        		$scope.showErrorContentLimit = true;
        	} else {
        		$scope.showErrorContentLimit = false;
        		$scope.alreadyInputCon = realLenght;
        		$scope.canInputCon = 10000 - $scope.alreadyInputCon;
        	}
        };
        /*$scope.charCodeAtFn = function(val) {        	
        	var realLength = 0;
        	for (var i = 0, len = val.length; i < len; i++) {
        		var charCode = val.charCodeAt(i);
        		if (charCode >= 0 && charCode <= 128) {
        			realLength += 1;
        		} else {
        			realLength += 3;
        		}
        	}
        	return realLength;
        };*/

        $scope.checkTitle = function() {
            if (!$scope.postForm.postTitle.$dirty) {
                $scope.showErrorTitle = true;
            } else {
                $scope.showErrorTitle = false;
                if ($scope.postForm.postTitle.$error.maxlength) {
                	$scope.showErrorTitleLimit = true;
                } else {
                	$scope.showErrorTitleLimit = false;
                }
            }
        };
        $scope.checkContent = function() {
            if ($('#content').html() == null || $('#content').html().length == 0) {
                $scope.showErrorContent = true;
            } else {
                $scope.showErrorContent = false;
            }
        };
        $scope.save = function() {
            if (!$scope.postForm.postTitle.$dirty) {
                $scope.showErrorTitle = true;
            } else {
                $scope.showErrorTitle = false;
                if ($scope.postForm.postTitle.$error.maxlength) {
                	$scope.showErrorTitleLimit = true;
                } else {
                	$scope.showErrorTitleLimit = false;
	                if ($('#content').html() == null || $('#content').html().length == 0) {
	                    $scope.showErrorContent = true;
	                } else {
	                    $scope.showErrorContent = false;
	                    if (!$scope.showErrorContentLimit) {
		                    // submit
		                    var postCircleId = $('#selectedCircleId').val();
		                    var checkedRadio = $scope.isAnonymous == true ? 1 : 0;
		                    var postContent = $('#content').html();
		                    var postObj = {
		                        coterie_id: postCircleId,
		                        title: $scope.postTitle,
		                        content: postContent,
		                        is_anonymity: checkedRadio
		                    };
		                    newPostApi.doRequest(postObj).success(function(data) {
		                        $scope.msg = '发帖成功,奖励5积分';
		                        $scope.confirmText = '查看发表的帖子';
		                        var dialog = ngDialog.open({
		                            template: './views/popup/alert.html',
		                            className: 'ngdialog-theme-default',
		                            showClose: false,
		                            scope: $scope
		                        });
		                        dialog.closePromise.then(function() {
		                            $location.path('/index/bbs/detail/' + data.id);
		                        });
		                    });
	                    }
	                }
                }
            }
        };
    }

    // 帖子编辑
    function postEditCtrl($scope, $location, $stateParams, ngDialog, postDetailApi, circleColumnApi, userInfoApi, newPostApi) {
        // post info
        postDetailApi.doRequest($stateParams.postId).success(function(data) {
            var isAnonymity;
            $scope.circleId = data.coteries.coterie_id;
            /*angular.forEach($scope.belongCircleList, function(item) {
                if (item.id == $scope.circleId) {
                    $scope.belongCircle = item;
                }
            });*/
            $scope.poster = data.user;
            isAnonymity = data.is_anonymity == 1 ? true : false;
            if (isAnonymity) {
                $('#authorAnonymous').prop('checked', true);
            } else {
                $('#authorDefault').prop('checked', true);
            }
            $scope.postTitle = data.title;
            $('#content').html(data.content);
        
	        // belong circle
	        circleColumnApi.doRequest().success(function(data) {
	            $scope.belongCircleList = data;
	            angular.forEach($scope.belongCircleList, function(item) {
	            	if (item.id == $scope.circleId) {
	            		$scope.belongCircle = item;
	            	}
	            });
	            // $scope.belongCircle = $scope.belongCircleList[0];
	            $scope.$watch('belongCircle', function(n) {
	                $scope.circleId = n.id;
	            });
	        });
        });

        // form validate
        $scope.isSelectedCircle = true;
        $scope.showErrorTitle = false;
        $scope.showErrorContent = false;

        $scope.checkTitle = function() {
            if ($scope.postForm.postTitle.$invalid) {
                $scope.showErrorTitle = true;
            } else {
                $scope.showErrorTitle = false;
            }
        };
        $scope.checkContent = function() {
            if ($('#content').html() == null || $('#content').html().length == 0) {
                $scope.showErrorContent = true;
            } else {
                $scope.showErrorContent = false;
            }
        };
        $scope.save = function() {
            if ($scope.postForm.postTitle.$invalid) {
                $scope.showErrorTitle = true;
            } else {
                $scope.showErrorTitle = false;
                if ($('#content').html() == null || $('#content').html().length == 0) {
                    $scope.showErrorContent = true;
                } else {
                    $scope.showErrorContent = false;

                    // submit
                    var postCircleId = $('#selectedCircleId').val();
                    var checkedRadio = $('input:radio:checked').attr('id') == 'authorAnonymous' ? 1 : 0;
                    var postContent = $('#content').html();
                    var postObj = {
                        coterie_id: postCircleId,
                        title: $scope.postTitle,
                        content: postContent,
                        is_anonymity: checkedRadio
                    };
                    newPostApi.doUpdateRequest($stateParams.postId, postObj).success(function(data) {
                        $scope.msg = '编辑修改成功';
                        $scope.confirmText = '查看修改的帖子';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $location.path('/index/bbs/detail/' + data.id);
                        });
                    });
                }
            }
        };
    }

    // 我的圈子
    function myCircleCtrl($scope, $rootScope, $state, $location, $timeout, ngDialog, circleJoinedApi, userApi, circleColumnApi, userInfoApi, articleRecommApi) {
        // joined circle
        circleJoinedApi.doRequest().success(function(data) {
            $scope.joinedCircleList = data;
            angular.forEach($scope.joinedCircleList, function(item) {
                item.status = true;
            });
        });

        // exit circle
        $scope.exit = function(category) {
            userApi.doDeleteRequest(category.myCoterie).success(function() {
                $scope.msg = '圈子退出成功';
                var dialog = ngDialog.open({
                    template: './views/popup/alert.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                dialog.closePromise.then(function() {
                    $state.reload();
                });
            });
        };

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 我的帖子
    function myPostCtrl($scope, $rootScope, $location, $timeout, ngDialog, myPostApi, circleColumnApi, userInfoApi, articleRecommApi) {
        // article list
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            myPostApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 我的回帖
    function myReplyCtrl($scope, $rootScope, $location, $timeout, ngDialog, myReplyApi, circleColumnApi, userInfoApi, articleRecommApi) {
        // article list
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            myReplyApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 帖子收藏
    function myCollectCtrl($scope, $rootScope, $state, $location, $timeout, ngDialog, myCollectApi, circleColumnApi, userInfoApi, articleRecommApi, uncollectApi) {
        // article list
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            myCollectApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };

        // cancel collect
        $scope.cancelCollect = function(articleId) {
            uncollectApi.doRequest(articleId).success(function() {
                $scope.msg = '取消收藏成功';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                    $state.reload();
                }, 2000);
            });
        };
    }

    // 推荐话题
    function postRecommCtrl($scope, $rootScope, $location, $timeout, ngDialog, articleRecommApi, circleColumnApi, userInfoApi) {
        // article list
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            articleRecommApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 圈子管理
    function manageCtrl($scope, $rootScope, $location, $state, $timeout, ngDialog, manageArticleApi, manageActionApi, circleColumnApi, userInfoApi, articleRecommApi) {
        // article list
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage) {
            manageArticleApi.doRequest(page, perPage)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

        // article actions
        $scope.toTop = function(articleId) {
            manageActionApi.doTopRequest(articleId).success(function() {
                $scope.msg = '置顶成功';
                var dialog = ngDialog.open({
                    template: './views/popup/alert.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                dialog.closePromise.then(function() {
                    $state.reload();
                });
            });
        };
        $scope.toBest = function(articleId) {
            manageActionApi.doBestRequest(articleId).success(function() {
                $scope.msg = '加精成功';
                var dialog = ngDialog.open({
                    template: './views/popup/alert.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                dialog.closePromise.then(function() {
                    $state.reload();
                });
            });
        };
        $scope.toggle = function(article) {
            manageActionApi.doToggleRequest(article.id).success(function() {
                if (article.status == 0) {
                    $scope.msg = '显示成功';
                } else if (article.status == 1) {
                    $scope.msg = '隐藏成功';
                }
                var dialog = ngDialog.open({
                    template: './views/popup/alert.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                dialog.closePromise.then(function() {
                    $state.reload();
                });
            });
        };

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    // 搜索结果
    function searchCtrl($scope, $rootScope, $stateParams, $location, $timeout, ngDialog, searchApi, circleColumnApi, userInfoApi, articleRecommApi) {
        var keyword = $stateParams.keyword;
        $scope.page = {
            pageSize: 8,
            pageNo: 1
        };
        $scope.doRequest = function(page, perPage, keyword) {
            searchApi.doRequest(page, perPage, keyword)
                .success(function(data, stat, headers) {
                    $scope.articleList = data;
                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
                    $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    $scope.isDataNull = (data.length == 0) ? true : false;
                });
        };
        $scope.doRequest($scope.page.pageNo, $scope.page.pageSize, keyword);

        // circle category
        circleColumnApi.doRequest().success(function(data) {
            $scope.circleList = data;
        });

        // user info
        userInfoApi.doRequest().success(function(data) {
            $scope.userInfo = data;
            $rootScope.isManager = data.coterie != undefined ? 1 : 0;
        });

        // article recommend
        articleRecommApi.doRequest(1, 10).success(function(data, stat, headers) {
            $scope.articleRecommendList = data;
        });

        // search
        $scope.searchFn = function(keyword) {
            if (!keyword) {
                $scope.msg = '请输入搜索的内容';
                ngDialog.open({
                    template: './views/popup/tips.html',
                    className: 'ngdialog-theme-default',
                    showClose: false,
                    scope: $scope
                });
                $timeout(function() {
                    ngDialog.close();
                }, 2000);
                $('#searchFiled').focus();
            } else {
                $location.path('/index/bbs/search/' + keyword);
            }
        };
    }

    bbsCtrl.$inject = ['$scope', '$rootScope', '$window', '$location', '$timeout', 'ngDialog', 'circleColumnApi', 'circleNotJoinedApi', 'articleApi', 'userInfoApi', 'articleRecommApi'];
    circleCtrl.$inject = ['$scope', '$rootScope', '$stateParams', '$location', '$timeout', 'ngDialog', 'circleIndexApi', 'circleJoinedApi', 'circleColumnApi', 'userApi', 'userInfoApi', 'articleRecommApi'];
    circleRecommCtrl.$inject = ['$scope', '$rootScope', '$stateParams', '$location', '$timeout', 'ngDialog', 'circleInfoApi', 'circleRecommArticleApi', 'circleJoinedApi', 'circleColumnApi', 'userApi', 'userInfoApi', 'articleRecommApi'];
    detailCtrl.$inject = ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$timeout', '$anchorScroll', 'ngDialog', 'postDetailApi', 'flowApi', 'commentFlowApi', 'commentApi', 'commentAddApi', 'userInfoApi', 'circleColumnApi', 'articleRecommApi', 'commentReplyApi'];
    postCtrl.$inject = ['$scope', '$rootScope', '$location', 'ngDialog', 'circleColumnApi', 'userInfoApi', 'newPostApi'];
    postEditCtrl.$inject = ['$scope', '$location', '$stateParams', 'ngDialog', 'postDetailApi', 'circleColumnApi', 'userInfoApi', 'newPostApi'];
    allCirclesCtrl.$inject = ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$timeout', 'ngDialog', 'circleJoinedApi', 'circleNotJoinedApi', 'userApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi'];
    myCircleCtrl.$inject = ['$scope', '$rootScope', '$state', '$location', '$timeout', 'ngDialog', 'circleJoinedApi', 'userApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi'];
    myPostCtrl.$inject = ['$scope', '$rootScope', '$location', '$timeout', 'ngDialog', 'myPostApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi'];
    myReplyCtrl.$inject = ['$scope', '$rootScope', '$location', '$timeout', 'ngDialog', 'myReplyApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi'];
    myCollectCtrl.$inject = ['$scope', '$rootScope', '$state', '$location', '$timeout', 'ngDialog', 'myCollectApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi', 'uncollectApi'];
    postRecommCtrl.$inject = ['$scope', '$rootScope', '$location', '$timeout', 'ngDialog', 'articleRecommApi', 'circleColumnApi', 'userInfoApi'];
    manageCtrl.$inject = ['$scope', '$rootScope', '$location', '$state', '$timeout', 'ngDialog', 'manageArticleApi', 'manageActionApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi']
    searchCtrl.$inject = ['$scope', '$rootScope', '$stateParams', '$location', '$timeout', 'ngDialog', 'searchApi', 'circleColumnApi', 'userInfoApi', 'articleRecommApi'];

}).call(this);
