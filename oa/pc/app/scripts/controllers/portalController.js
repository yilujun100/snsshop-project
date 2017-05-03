/*
 * 门户控制器
 * auth: yilj@snsshop.cn
 * date: 2016-9-13
 */

(function(){
	'use strict';
	angular.module('portal.controller', ['ui.bootstrap'])
		.controller('portalIndexCtrl', ['$scope', '$rootScope', 'columnApi', 'newPartnerApi', 'adApi', 'bannerApi', 
			function($scope, $rootScope, columnApi, newPartnerApi, adApi, bannerApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});
			
			// 栏目
			columnApi.doRequest().success(function(data){
				var resResult = data;
				$scope.columnList = resResult;
				/*var arr = [];
				angular.forEach(resResult, function(item){
					arr.push(item.newestPortalArticle);
				});

				$scope.noticeList = arr[1]; // 最新公告
	            $scope.newsList = arr[0]; // 企业新闻
	            $scope.systemList = arr[2]; // 行政制度*/
			});

			// 新伙伴
			newPartnerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.newPartners = resResult;
			});

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});

			// 首页轮播
			bannerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.sliderList = resResult;
				// console.log($scope.sliderList[0]);
				$scope.myInterval = 5000;
				$scope.noWrapSlides = false;
				$scope.active = 0;
				var slides = $scope.slides = [];
				var addSlide = function() {
					slides.push({
						image: $scope.sliderList[0].big_src,
						id: 0,
						link: $scope.sliderList[0].link
					});
					slides.push({
						image: $scope.sliderList[1].big_src,
						id: 1,
						link: $scope.sliderList[1].link
					});
					slides.push({
						image: $scope.sliderList[2].big_src,
						id: 2,
						link: $scope.sliderList[2].link
					});
					slides.push({
						image: $scope.sliderList[3].big_src,
						id: 3,
						link: $scope.sliderList[3].link
					});
				};

				addSlide();
			});
			$scope.jumpTo = function (linkUrl) {
				console.log(linkUrl);
			};
			$scope.$on('ngRepeatFinished', function(){
				$('.banner, .carousel-inner').css('height', $('.banner').width()/5.14);
			});

		}])
		.controller('columnListCtrl', ['$scope', '$stateParams', 'columnListApi', 'columnApi', 'newPartnerApi', 'adApi', function($scope, $stateParams, columnListApi, columnApi, newPartnerApi, adApi){
			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
			$scope.columnList = {};
            $scope.doRequest = function(status, page, perPage, columnId) {
                columnListApi.doRequest(status, page, perPage, columnId)
                    .success(function(data, stat, headers) {
                        $scope.columnList.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.columnList.status = ''; //默认显示全部
            $scope.columnList.columnId = $stateParams.columnId;
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize, $scope.columnList.columnId);

            $scope.changeState = function(status) {                
                $scope.companyNews.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize, $scope.columnList.columnId);
            };

            columnApi.doRequest().success(function(data){
				var resResult = data;
				angular.forEach(resResult, function(item){
					if (item.id == $stateParams.columnId) {
						$scope.breadCrumbTitle = item.name;
					}
				});

			});            

			// 新伙伴
			newPartnerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.newPartners = resResult;
			});

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});
		}])
		.controller('columnArticleCtrl', ['$scope', '$rootScope', '$stateParams', 'columnApi', 'columnArticleApi', function($scope, $rootScope, $stateParams, columnApi, columnArticleApi){

			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			columnArticleApi.doRequest($stateParams.id).success(function(data){
				var resResult = data;
				$scope.columnArticle = resResult;
				$scope.columnId = resResult.column_id;
			});

			columnApi.doRequest().success(function(data){
				var resResult = data;
				angular.forEach(resResult, function(item){
					if (item.id == $scope.columnId) {
						$scope.breadCrumbTitle = item.name;
					}
				});

			});
		}])
		.controller('companyNewsCtrl', ['$scope', '$rootScope', 'companyNewsApi', 'newPartnerApi', 'adApi', function($scope, $rootScope, companyNewsApi, newPartnerApi, adApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
			$scope.companyNews = {};
            $scope.doRequest = function(status, page, perPage) {
                companyNewsApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.companyNews.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.companyNews.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.companyNews.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };            

			// 新伙伴
			newPartnerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.newPartners = resResult;
			});

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});
		}])
		.controller('companyNewsDetailCtrl', ['$scope', '$rootScope', '$stateParams', 'companyNewsDetailApi', function($scope, $rootScope, $stateParams, companyNewsDetailApi){

			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			companyNewsDetailApi.doRequest($stateParams.id).success(function(data){
				var resResult = data;
				$scope.companyNewsDetail = resResult;
			});
		}])
		.controller('noticeListCtrl', ['$scope', '$rootScope', 'noticeListApi', 'newPartnerApi', 'adApi', function($scope, $rootScope, noticeListApi, newPartnerApi, adApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
			$scope.noticeList = {};
            $scope.doRequest = function(status, page, perPage) {
                noticeListApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.noticeList.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.noticeList.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.noticeList.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

			// 新伙伴
			newPartnerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.newPartners = resResult;
			});

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});
		}])
		.controller('noticeDetailCtrl', ['$scope', '$rootScope', '$stateParams', 'noticeDetailApi', function($scope, $rootScope, $stateParams, noticeDetailApi){

			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			noticeDetailApi.doRequest($stateParams.id).success(function(data){
				var resResult = data;
				$scope.noticeDetail = resResult;
			});
		}])
		.controller('systemListCtrl', ['$scope', '$rootScope', 'systemListApi', 'newPartnerApi', 'adApi', function($scope, $rootScope, systemListApi, newPartnerApi, adApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
			$scope.systemList = {};
            $scope.doRequest = function(status, page, perPage) {
                systemListApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.systemList.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.systemList.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.systemList.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

			// 新伙伴
			newPartnerApi.doRequest().success(function(data){
				var resResult = data;
				$scope.newPartners = resResult;
			});

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});
		}])
		.controller('systemDetailCtrl', ['$scope', '$rootScope', '$stateParams', 'systemDetailApi', function($scope, $rootScope, $stateParams, systemDetailApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			systemDetailApi.doRequest($stateParams.id).success(function(data){
				var resResult = data;
				$scope.systemDetail = resResult;
			});
		}])
		.controller('newPartnerListCtrl', ['$scope', '$rootScope', 'newPartnerListApi', 'adApi', function($scope, $rootScope, newPartnerListApi, adApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			$scope.page = {
                "pageSize": 6,
                "pageNo": 1
            };
			$scope.newPartnerList = {};
            $scope.doRequest = function(status, page, perPage) {
                newPartnerListApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.newPartnerList.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.newPartnerList.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function(status) {                
                $scope.newPartnerList.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };

			// 广告
			adApi.doRequest().success(function(data){
				var resResult = data;
				$scope.adList = resResult;
			});
		}])
		.controller('newPartnerDetailCtrl', ['$scope', '$rootScope', '$stateParams', 'newPartnerDetailApi', function($scope, $rootScope, $stateParams, newPartnerDetailApi){

			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			newPartnerDetailApi.doRequest($stateParams.userid).success(function(data){
				var resResult = data;
				$scope.newPartnerDetail = resResult;
			});
		}])
		.controller('personalCenterCtrl', ['$scope', '$rootScope', '$stateParams', 'personalCenterApi', 'infoApi', 'statisApi', 'scoreTotalApi', function($scope, $rootScope, $stateParams, personalCenterApi, infoApi, statisApi, scoreTotalApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			personalCenterApi.doRequest($stateParams.userid).success(function(data){
				var resResult = data;
				$scope.userInfo = resResult;
			});

			// personal info
			infoApi.doRequest().success(function(data){
				$scope.personalInfo = data;
			});

			statisApi.doRequest().success(function(data){
				$scope.statisData = data;
				$scope.statisOvertime = data.overtime || 0;
				$scope.statisLeavetime = data.leavetime || 0;
			});

			// score total
			scoreTotalApi.doRequest().success(function(data){
				$scope.scoreTotal = data;
			});
		}])	
		.controller('infoBasicCtrl', ['$scope', '$rootScope', '$stateParams', 'personalCenterApi', 'infoApi', 'statisApi', 'scoreTotalApi', function($scope, $rootScope, $stateParams, personalCenterApi, infoApi, statisApi, scoreTotalApi){
			
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			personalCenterApi.doRequest($stateParams.userid).success(function(data){
				var resResult = data;
				$scope.userInfo = resResult;
			});

			// personal info
			infoApi.doRequest().success(function(data){
				$scope.personalInfo = data;
			});

			statisApi.doRequest().success(function(data){
				$scope.statisData = data;
				$scope.statisOvertime = data.overtime || 0;
				$scope.statisLeavetime = data.leavetime || 0;
			});

			// score total
			scoreTotalApi.doRequest().success(function(data){
				$scope.scoreTotal = data;
			});
		}])
		.controller('overtimeOffCtrl', ['$scope', '$rootScope', 'overtimeApi', 'leaveApi', 'statisApi', function($scope, $rootScope, overtimeApi, leaveApi, statisApi){
			$scope.isOpen = $rootScope.isOpen;

			$scope.$on('leftChange', function(event, val){
				$scope.isOpen = val;
			});

			// overtime list
			overtimeApi.doRequest().success(function(data){
				$scope.overtimeList = data;
				if ($scope.overtimeList.length > 0) {
					$scope.dataOvertimeNull = false;
				} else {
					$scope.dataOvertimeNull = true;
				}
			});

			// leave list
			leaveApi.doRequest().success(function(data){
				$scope.leaveList = data;
				if ($scope.leaveList.length > 0) {
					$scope.dataLeaveNull = false;
				} else {
					$scope.dataLeaveNull = true;
				}
			});

			// statistics
			statisApi.doRequest().success(function(data){
				$scope.statisData = data;
				$scope.statisOvertime = data.overtime || 0;
				$scope.statisLeavetime = data.leavetime || 0;
			});
		}])	
		.controller('msgCtrl', ['$scope', '$rootScope', '$location', '$state', '$timeout', 'ngDialog', 'msgApi', 'msgDeleteApi', 'msgMarkReadApi', 'msgMarkReadAllApi', 'msgUnreadApi', 'msgStatisApi', 'msgData', function($scope, $rootScope, $location, $state, $timeout, ngDialog, msgApi, msgDeleteApi, msgMarkReadApi, msgMarkReadAllApi, msgUnreadApi, msgStatisApi, msgData){
			
			// statistics
			msgStatisApi.doRequest().success(function(data) {
				$scope.statis = data;
			});

			// msg list			
			$scope.showMsgAll = true;
			$scope.showMsgUnread = false;
			$scope.page = {
				pageSize: 15,
				pageNo: 1,
				fromApp: ''
			};
			$scope.doRequest = function(page, perPage, filter) {
				msgApi.doRequest(page, perPage, filter)
					.success(function(data, stat, headers) {
						$scope.msgList = data;
	                    $scope.page.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.page.totalPage = headers('X-Pagination-Page-Count');	
	                    $('#opera-del, #opera-mark-read').addClass('btn-action-disabled').removeClass('btn-action-primary');
	                    $('.data-table thead input').prop('checked', false);				
					})
			};
			$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.page.fromApp);

			// data actions
			$scope.unread = function() {
				// msg unread list
				$scope.pageUnread = {
					pageSize: 15,
					pageNo: 1,
					fromAppUnread: ''
				};
				$scope.doUnreadRequest = function(page, perPage, filter) {
					msgUnreadApi.doRequest(page, perPage, filter)
						.success(function(data, stat, headers) {
							$scope.msgUnreadList = data;
		                    $scope.pageUnread.totalCount = headers('X-Pagination-Total-Count');
		                    $scope.pageUnread.totalPage = headers('X-Pagination-Page-Count');	
		                    $('#opera-del, #opera-mark-read').addClass('btn-action-disabled').removeClass('btn-action-primary');
		                    $('.data-table thead input').prop('checked', false);				
						})
				};
				$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, $scope.pageUnread.fromAppUnread);
				$scope.showMsgAll = false;
				$scope.showMsgUnread = true;
			};

			$scope.readAll = function() {
				$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, $scope.page.fromApp);
				$scope.showMsgAll = true;
				$scope.showMsgUnread = false;
			};

			$scope.statusChange = function(event, itemInfo) {
				var itemCheckbox = $(event.target);
				if (itemCheckbox.is(':checked')) {
					if (itemInfo.is_read == 1) { // 已读
						$('#opera-del').removeClass('btn-action-disabled').addClass('btn-action-primary');
					} else if (itemInfo.is_read == 0) { // 未读
						$('#opera-del, #opera-mark-read').removeClass('btn-action-disabled').addClass('btn-action-primary');
					}				
				} else {
					if (itemInfo.is_read == 1) { // 已读
						$('#opera-del').removeClass('btn-action-primary').addClass('btn-action-disabled');
					}  else if (itemInfo.is_read == 0) { // 未读
						$('#opera-del, #opera-mark-read').removeClass('btn-action-primary').addClass('btn-action-disabled');
					}	
					var aCheckedbox = $('.data-table tbody input[type="checkbox"]:checked');
					for (var i = 0, len = aCheckedbox.length; i < len; i++) {
						// console.log($(aCheckedbox[i]).attr('data-isread'));
						if ($(aCheckedbox[i]).attr('data-isread') == 1) {
							$('#opera-del').removeClass('btn-action-disabled').addClass('btn-action-primary');
						}
						if ($(aCheckedbox[i]).attr('data-isread') == 0) {
							$('#opera-del, #opera-mark-read').removeClass('btn-action-disabled').addClass('btn-action-primary');
						}
					}
					$('.data-table thead input').prop('checked', false);
				}
			};

			$scope.selectAll = function(event) {
				var selectAllCheckbox = $(event.target);
				var aCheckedbox = $('.data-table tbody input[type="checkbox"]:checked');
				if (selectAllCheckbox.is(':checked')) {
					var keepGoing = true;
					$('.data-table tbody input').prop('checked', true);
					angular.forEach($scope.msgList, function(item) {
						// debugger;
						if (keepGoing) {
							if (item.is_read == 0) {
								$('#opera-del, #opera-mark-read').removeClass('btn-action-disabled').addClass('btn-action-primary');
								keepGoing = false;
							} else {
								$('#opera-del').removeClass('btn-action-disabled').addClass('btn-action-primary');
							}							
						}
					});
				} else {
					$('.data-table tbody input').prop('checked', false);
					$('#opera-del, #opera-mark-read').removeClass('btn-action-primary').addClass('btn-action-disabled');
				}
			};



			$scope.dataDel = function(event) {
				var _this = $(event.target);
				if (!_this.hasClass('btn-action-disabled')) {
					// popup confirm
					$scope.msg = '删除后消息将无法恢复,您确定要删除吗?';
					ngDialog.openConfirm({
	                	template: './views/popup/confirm.html', 
	                	className: 'ngdialog-theme-default',
	                	showClose: false,
	                	scope: $scope 
					}).then(function(success){
						var aCheckedbox = $('.data-table tbody input[type="checkbox"]:checked');
						var arrIds = [];
						var strIds;
						for (var i = 0, len = aCheckedbox.length; i < len; i++) {
							arrIds.push(parseInt($(aCheckedbox[i]).attr('data-msgid')));
						}
						strIds = arrIds.join(',');
						var params = {
							ids: strIds
						};

						msgDeleteApi.doRequest(params).success(function() {
							$scope.msg = '删除成功';
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
					});
				}
			};

			$scope.dataMarkRead = function(event) {
				var _this = $(event.target);
				if (!_this.hasClass('btn-action-disabled')) {
					var aCheckedbox = $('.data-table tbody input[type="checkbox"]:checked');
					var arrIds = [];
					var strIds;
					for (var i = 0, len = aCheckedbox.length; i < len; i++) {
						arrIds.push(parseInt($(aCheckedbox[i]).attr('data-msgid')));
					}
					strIds = arrIds.join(',');
					var params = {
						ids: strIds
					};

					/*angular.forEach($scope.msgList, function(item) {
						console.log($scope.itemChecked);
					});*/
					msgMarkReadApi.doRequest(params).success(function() {
						$state.reload();
					});
				}
			};

			$scope.dataMarkReadAll = function() {
				// popup confirm
				$scope.msg = '确认标记所有消息为已读吗?';
				ngDialog.openConfirm({
                	template: './views/popup/confirm.html', 
                	className: 'ngdialog-theme-default',
                	showClose: false,
                	scope: $scope 
				}).then(function(success){
					var params = {
						ids: 'all'
					};

					msgMarkReadAllApi.doRequest(params).success(function() {
						$state.reload();
					});
				});
			};

			$scope.msgCategory = function(appName) {
				switch (appName) {
					case 'all':
						$scope.page.fromApp = '';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, '');
						break;
					case 'portal':
						$scope.page.fromApp = 'portal';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'portal');
						break;
					case 'knowledge':
						$scope.page.fromApp = 'knowledge';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'knowledge');
						break;	
					case 'bbs':
						$scope.page.fromApp = 'bbs';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'bbs');
						break;	
					case 'room':
						$scope.page.fromApp = 'room';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'room');
						break;	
					case 'leave':
						$scope.page.fromApp = 'leave';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'leave');
						break;		
					case 'approval':
						$scope.page.fromApp = 'approval';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'approval');
						break;		
					case 'attendance':
						$scope.page.fromApp = 'attendance';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'attendance');
						break;		
					case 'ticket':
						$scope.page.fromApp = 'ticket';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'ticket');
						break;
					case 'other':
						$scope.page.fromApp = 'other';
						$scope.doRequest($scope.page.pageNo, $scope.page.pageSize, 'NULL__');
						break;				
				}
			};

			$scope.msgUnreadCategory = function(appName) {
				switch (appName) {
					case 'all':
						$scope.pageUnread.fromAppUnread = '';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, '');
						break;
					case 'portal':
						$scope.pageUnread.fromAppUnread = 'portal';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'portal');
						break;
					case 'knowledge':
						$scope.pageUnread.fromAppUnread = 'knowledge';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'knowledge');
						break;	
					case 'bbs':
						$scope.pageUnread.fromAppUnread = 'bbs';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'bbs');
						break;	
					case 'room':
						$scope.pageUnread.fromAppUnread = 'room';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'room');
						break;	
					case 'leave':
						$scope.pageUnread.fromAppUnread = 'leave';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'leave');
						break;		
					case 'approval':
						$scope.pageUnread.fromAppUnread = 'approval';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'approval');
						break;		
					case 'attendance':
						$scope.pageUnread.fromAppUnread = 'attendance';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'attendance');
						break;		
					case 'ticket':
						$scope.pageUnread.fromAppUnread = 'ticket';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'ticket');
						break;	
					case 'other':
						$scope.pageUnread.fromAppUnread = 'other';
						$scope.doUnreadRequest($scope.pageUnread.pageNo, $scope.pageUnread.pageSize, 'NULL__');
						break;				
				}
			};

			$scope.jumpToDetail = function(msg) {
				// $location.path('index/personalCenter/message/detail/'+ msg.id);
				if (msg.is_read == 0) {
					msgData.getMsgInfo().then(function(res) {
						$scope.msgNumber = res.data;
						$scope.msgNumber.unread--;
					})
					// $rootScope.msgNumber.unread--;
				}
				$state.go('index.personalCenter.msg.detail', {msgId: msg.id}, {reload: true}); 
			};
		}])
		.controller('msgDetailCtrl', ['$rootScope','$scope', '$state', '$stateParams', 'msgDetailApi', function($rootScope, $scope, $state, $stateParams, msgDetailApi){
			// msg info
			msgDetailApi.doRequest($stateParams.msgId).success(function(data) {
				$scope.msgInfo = data;
				/*if ($scope.msgInfo.is_read == 1) {
					$state.reload();
				}*/
			});		
		}])
		.controller('personMyCollectCtrl', ['$scope', '$timeout', 'ngDialog', 'myCollectKmApi', 'myCollectApi', 'opCancelCollectApi', function($scope, $timeout, ngDialog, myCollectKmApi, myCollectApi, opCancelCollectApi){
			// km article list
			$scope.pageKm = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myCollectArticles = {};
            $scope.doKmRequest = function(status, page, perPage) {
                myCollectKmApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myCollectArticles.contents = data;
                        $scope.pageKm.totalCount = headers('X-Pagination-Total-Count');
                        $scope.pageKm.totalPage = headers('X-Pagination-Page-Count');
                        if ($scope.myCollectArticles.contents.length > 0) {
                        	$scope.dataArticleNull = false;
                        } else {
                        	$scope.dataArticleNull = true;
                        }
                    });
            };
            $scope.myCollectArticles.status = ''; //默认显示全部
            $scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);

            $scope.changeState = function(status) {                
                $scope.myCollectArticles.status = status;
                $scope.pageKm.pageNo = 1;
                $scope.doKmRequest(status, $scope.pageKm.pageNo, $scope.pageKm.pageSize);
            };

            // bbs post list
            $scope.pageBbs = {
				pageSize: 6,
				pageNo: 1
			};
			$scope.doBbsRequest = function(page, perPage) {
				myCollectApi.doRequest(page, perPage)
					.success(function(data, stat, headers) {
						$scope.postCollectList = data;
	                    $scope.pageBbs.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.pageBbs.totalPage = headers('X-Pagination-Page-Count');	
	                    if ($scope.postCollectList.length > 0) {
	                    	$scope.dataPostNull = false;
	                    } else {
	                    	$scope.dataPostNull = true;
	                    }				
					});
			};
			$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);

			// operation cancel collect
			$scope.opCancelCollect = function(article, fromApp) {
				var params = {
					channel: fromApp,
					id: article.id,
					do: 'del'
				};
				opCancelCollectApi.doRequest(params).success(function() {
					$scope.msg = '取消收藏成功';
	                ngDialog.open({ 
	                	template: './views/popup/tips.html', 
	                	className: 'ngdialog-theme-default',
	                	showClose: false,
	                	scope: $scope 
	                });
	                $timeout(function() {
	                	ngDialog.close();
	                }, 2000);
	                if (fromApp == 'knowledge') {
	                	$scope.pageKm.totalCount--;
		                angular.forEach($scope.myCollectArticles.contents, function(item, index) {
		                	if (item.id == article.id) {
		                		$scope.myCollectArticles.contents.splice(index, 1);
		                		$scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);
		                	}
		                });
	                } else if (fromApp == 'bbs') {
	                	$scope.pageBbs.totalCount--;
	                	angular.forEach($scope.postCollectList, function(item, index) {
	                		if (item.id == article.id) {
	                			$scope.postCollectList.splice(index, 1);
								$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);
	                		}
	                	});
	                }
				});
			};
		}])
		.controller('personMyCommentCtrl', ['$scope', '$timeout', 'ngDialog', 'personalKmCommentApi', 'personalBbsCommentApi', 'opDelCommentApi', function($scope, $timeout, ngDialog, personalKmCommentApi, personalBbsCommentApi, opDelCommentApi){
			// km article list
			$scope.pageKm = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myCommentKmArticles = {};
            $scope.doKmRequest = function(status, page, perPage) {
                personalKmCommentApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myCommentKmArticles.contents = data;
                        $scope.pageKm.totalCount = headers('X-Pagination-Total-Count');
                        $scope.pageKm.totalPage = headers('X-Pagination-Page-Count');
						if ($scope.myCommentKmArticles.contents.length > 0) {
							$scope.dataKmArticleNull = false;
						} else {
							$scope.dataKmArticleNull = true;
						}
                    });
            };
            $scope.myCommentKmArticles.status = ''; //默认显示全部
            $scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);

            $scope.changeState = function(status) {                
                $scope.myCommentKmArticles.status = status;
                $scope.pageKm.pageNo = 1;
                $scope.doKmRequest(status, $scope.pageKm.pageNo, $scope.pageKm.pageSize);
            };	

            // bbs post reply
            $scope.pageBbs = {
				pageSize: 6,
				pageNo: 1
			};
			$scope.doBbsRequest = function(page, perPage) {
				personalBbsCommentApi.doRequest(page, perPage)
					.success(function(data, stat, headers) {
						$scope.postReplyList = data;
	                    $scope.pageBbs.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.pageBbs.totalPage = headers('X-Pagination-Page-Count');	
						if ($scope.postReplyList.length > 0) {
							$scope.dataReplyListNull = false;
						} else {
							$scope.dataReplyListNull = true;
						}				
					});
			};
			$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);

			// operation del comment
			$scope.opDelComment = function(article, fromApp) {
				var params = {
					channel: fromApp,
					id: article.id,
					do: 'del'
				};
				opDelCommentApi.doRequest(params).success(function() {
					$scope.msg = '删除评论成功';
	                ngDialog.open({ 
	                	template: './views/popup/tips.html', 
	                	className: 'ngdialog-theme-default',
	                	showClose: false,
	                	scope: $scope 
	                });
	                $timeout(function() {
	                	ngDialog.close();
	                }, 2000);
	                if (fromApp == 'knowledge') {
	                	$scope.pageKm.totalCount--;
		                angular.forEach($scope.myCommentKmArticles.contents, function(item, index) {
		                	if (item.id == article.id) {
		                		$scope.myCommentKmArticles.contents.splice(index, 1);
		                		$scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);
		                	}
		                });
	                } else if (fromApp == 'bbs') {
	                	$scope.pageBbs.totalCount--;
	                	angular.forEach($scope.postReplyList, function(item, index) {
	                		if (item.id == article.id) {
		                		$scope.postReplyList.splice(index, 1);
		                		$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);
	                		}
	                	});
	                }
				});
			};
		}])
		.controller('personMyPraiseCtrl', ['$scope', 'myAdmireApi', 'myAdmirePostApi', function($scope, myAdmireApi, myAdmirePostApi){
			// km article list
			$scope.pageKm = {
                "pageSize": 6,
                "pageNo": 1
            };
            $scope.myAdmireKmArticles = {};
            $scope.doKmRequest = function(status, page, perPage) {
                myAdmireApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.myAdmireKmArticles.contents = data;
                        $scope.pageKm.totalCount = headers('X-Pagination-Total-Count');
                        $scope.pageKm.totalPage = headers('X-Pagination-Page-Count');
                        if ($scope.myAdmireKmArticles.contents.length > 0) {
                        	$scope.dataArticleNull = false;
                        } else {
                        	$scope.dataArticleNull = true;
                        }
                    });
            };
            $scope.myAdmireKmArticles.status = '';
            $scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);

            $scope.changeKmState = function(status) {  
            	$scope.myAdmireKmArticles.status = status;          
                $scope.pageKm.pageNo = 1;
                $scope.doKmRequest('', $scope.pageKm.pageNo, $scope.pageKm.pageSize);
            };

            // bbs post list
            $scope.pageBbs = {
				pageSize: 6,
				pageNo: 1
			};
			$scope.doBbsRequest = function(page, perPage) {
				myAdmirePostApi.doRequest(page, perPage)
					.success(function(data, stat, headers) {
						$scope.postList = data;
	                    $scope.pageBbs.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.pageBbs.totalPage = headers('X-Pagination-Page-Count');
	                    if ($scope.postList.length > 0) {
	                    	$scope.dataPostNull = false;
	                    } else {
	                    	$scope.dataPostNull = true;
	                    }					
					});
			};
			$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);
		}])
		.controller('personMyPublishCtrl', ['$scope', '$timeout', 'ngDialog', 'myPublishArticleApi', 'myPostApi', 'opDelPublishApi', function($scope, $timeout, ngDialog, myPublishArticleApi, myPostApi, opDelPublishApi){
			// km article list
			$scope.pageKm = {
				pageSize: 6,
				pageNo: 1
			};
			$scope.doKmRequest = function(page, perPage) {
				myPublishArticleApi.doRequest(page, perPage)
					.success(function(data, stat, headers) {
						$scope.articleKmList = data;
	                    $scope.pageKm.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.pageKm.totalPage = headers('X-Pagination-Page-Count');	
	                    if ($scope.articleKmList.length > 0) {
	                    	$scope.dataArticleNull = false;
	                    } else {
	                    	$scope.dataArticleNull = true;
	                    }				
					});
			};
			$scope.doKmRequest($scope.pageKm.pageNo, $scope.pageKm.pageSize);

			// bbs post list
			$scope.pageBbs = {
				pageSize: 6,
				pageNo: 1
			};
			$scope.doBbsRequest = function(page, perPage) {
				myPostApi.doRequest(page, perPage)
					.success(function(data, stat, headers) {
						$scope.articleBbsList = data;
	                    $scope.pageBbs.totalCount = headers('X-Pagination-Total-Count');
	                    $scope.pageBbs.totalPage = headers('X-Pagination-Page-Count');
	                    if ($scope.articleBbsList.length > 0) {
	                    	$scope.dataPostNull = false;
	                    } else {
	                    	$scope.dataPostNull = true;
	                    }					
					});
			};
			$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);

			// operation del publish
			$scope.opDelPublish = function(article, fromApp) {
				var params = {
					channel: fromApp,
					id: article.id,
					do: 'del'
				};
				opDelPublishApi.doRequest(params).success(function() {
					$scope.msg = '删除成功';
	                ngDialog.open({ 
	                	template: './views/popup/tips.html', 
	                	className: 'ngdialog-theme-default',
	                	showClose: false,
	                	scope: $scope 
	                });
	                $timeout(function() {
	                	ngDialog.close();
	                }, 2000);
	                if (fromApp == 'knowledge') {
	                	$scope.pageKm.totalCount--;
		                angular.forEach($scope.articleKmList, function(item, index) {
		                	if (item.id == article.id) {
		                		$scope.articleKmList.splice(index, 1);
		                		$scope.doKmRequest($scope.pageKm.pageNo, $scope.pageKm.pageSize);
		                	}
		                });
	                } else if (fromApp == 'bbs') {
	                	$scope.pageBbs.totalCount--;
	                	angular.forEach($scope.articleBbsList, function(item, index) {
	                		if (item.id == article.id) {
		                		$scope.articleBbsList.splice(index, 1);
		                		$scope.doBbsRequest($scope.pageBbs.pageNo, $scope.pageBbs.pageSize);
	                		}
	                	});
	                }
				});
			};	
		}])
		.controller('scoreDetailCtrl', ['$scope', 'scoreTotalApi', 'scoreListApi', function($scope, scoreTotalApi, scoreListApi){
			// score total
			scoreTotalApi.doRequest().success(function(data){
				$scope.scoreTotal = data;
			});

			// score list
			$scope.page = {
                "pageSize": 15,
                "pageNo": 1
            };
            $scope.doRequest = function(page, perPage) {
                scoreListApi.doRequest(page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.scoreList = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

            $scope.changeState = function() {     
                $scope.page.pageNo = 1;
                $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);
            };
            
	        // score rule
	        $scope.showPopup = false;
	        $scope.showRule = function() {
	            $scope.showPopup = true;
	        };
		}])
		.controller('leftbarAvatarCtrl', ['$scope', 'personalCenterApi', function($scope, personalCenterApi){
			personalCenterApi.doRequest().success(function(data){
				var resResult = data;
				$scope.userInfo = resResult;
			});
		}])		
		.filter('computAge', function(){

	        return function (val) {
	            var iAge;
	            var now = new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate(),
	                joinDate = new Date(val * 1000).getFullYear() + '-' + (new Date(val * 1000).getMonth() + 1) + '-' + new Date(val * 1000).getDate();

	            now = now.split('-');
	            now = parseInt(now[0]) * 12 + parseInt(now[1]);
	            joinDate = joinDate.split('-');
	            joinDate = parseInt(joinDate[0]) * 12 + parseInt(joinDate[1]);
	            var diff = Math.abs(now - joinDate);
	            if (diff >= 12) {
	                iAge = Math.floor(diff/12) + '年' + diff % 12 + '个月';
	            } else {
	                iAge = diff % 12 + '个月';
	            }
	            return iAge;
	        }
	    })
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
	    .directive('clickTab', function(){
	    	return {
	    		restrict: 'A',
	    		link: function(scope, element, attr){
	    			var ele = $(element).find('li');
	    			var con = $('.tab-con > div');

	    			ele.each(function(i){
	    				var _this = $(this);
	    				_this.on('click', function(){
	    					$(this).addClass('tab-active').siblings().removeClass('tab-active');
	    					con.eq(i).show().siblings().hide();
	    				});
	    			})
	    		}
	    	}
	    }) 
	    .filter('appNameToEn', function(){
	    	return function (str) {
	    		switch (str) {
	    			case 'portal':
	    				return '门户';
    				case 'knowledge':
    					return '知识库';
					case 'bbs':
						return 'BBS';
					case 'room':
						return '会议室';
					case 'leave':
						return '请假';
					case 'approval':
						return '流程';
					case 'attendance':
						return '考勤';
					default:
						return '其他';
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

	    }])
		/*.directive('carousel', ['$timeout', function(){
			return {
				restrict: 'EA',
				link: function (scope, element, attr) {
					var slider = element.find('.slider-wrap').children(),
						bullet = element.find('.bullet').children(),
						length = slider.length,
						current = 0,
						temp = '',
						timer = null,
						loopSpeed = 4000,
						fadeSpeed = 2000,
						loop = function () {
							slider.eq(current).fadeOut(fadeSpeed);
							bullet.eq(current).removeClass('bullet-on');
							if (current == (length - 1)) {
								current = -1;
							}
							current += 1;
							slider.eq(current).fadeIn(fadeSpeed);
							bullet.eq(current).addClass('bullet-on');
						};
					slider.eq(0).show();
					bullet.eq(0).addClass('bullet-on');
					timer = setInterval(loop, loopSpeed);
					bullet.on('click', function(){
						var iIndex = $(this).index();
						slider.eq(iIndex).fadeIn().siblings().fadeOut();
						bullet.eq(iIndex).addClass('bullet-on').siblings().removeClass('bullet-on');
						clearInterval(timer);
						current = iIndex;
						timer = setInterval(loop, loopSpeed);
					});
				}
			}
		}])*/
		.directive('onFinishRenderFilters', function($rootScope) {
			return {
				restrict: 'A',
				link: function(scope, element, attr) {
					if (scope.$last === true) {
						$rootScope.$broadcast('ngRepeatFinished');
					}
				}
			};
		})
		.directive('resize', function($window) {
			return {
				restrict: 'A',
				link: function(scope, element, attr) {
					var _win = angular.element($window);
					var toggleBtn = element.prev().find('div');
					_win.on('resize', function() {
						if (toggleBtn.hasClass('lb-icon-open')) {
							element.find('.container').css('width', $(window).width() - 80);
						} else if (toggleBtn.hasClass('lb-icon-fold')) {
							element.find('.container').css('width', $(window).width() - 200);
						}
					});
				}
			}
		})
		.directive('rulesHover', function() {
			return {
				restrict: 'A',
				scope: {
					hover: '='
				},
				link: function(scope, element, attr) {
					element.bind('mouseover', function() {
						scope.$apply(function() {
							scope.hover = true;
						});
					});

					element.bind('mouseout', function() {
						scope.$apply(function() {
							scope.hover = false;
						});
					});
				}
			}
		});
}).call(this);