(function() {
'use strict';
angular.module('portal.resource', ['ngResource'])
	.factory('columnApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/column?PHPSESSID=' + localStorage.token + '&expand=newestPortalArticle&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('newPartnerApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/user?PHPSESSID=' + localStorage.token + '&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('adApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/advert?PHPSESSID=' + localStorage.token + '&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('bannerApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/rotate?PHPSESSID=' + localStorage.token + '&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('columnListApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage, columnId) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article?column_id='+ columnId +'&PHPSESSID=' + localStorage.token + '&expand=&expand-fileds=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status=&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('columnArticleApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (id) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article/'+ id + '?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('companyNewsApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article?column_id=2&PHPSESSID=' + localStorage.token + '&expand=&expand-fileds=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status=&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('noticeListApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article?column_id=1&PHPSESSID=' + localStorage.token + '&expand=&expand-fileds=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status=&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('systemListApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article?column_id=3&PHPSESSID=' + localStorage.token + '&expand=&expand-fileds=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status=&sort=sort%20DESC'
				});
			}
		};	
	}])
	.factory('newPartnerListApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/user?PHPSESSID=' + localStorage.token + '&expand=&expand-fileds=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status='
				});
			}
		};	
	}])
	.factory('newPartnerDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (userid) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/user/'+ userid +'?PHPSESSID=' + localStorage.token
				});
			}
		};	
	}])
	.factory('companyNewsDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (id) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article/'+ id + '?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('noticeDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (id) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article/'+ id + '?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('systemDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (id) {
				return $http({
					method: 'GET',
					url: apiUrl + '/portal/article/'+ id + '?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('personalCenterApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/user/info?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('overtimeApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/attendance/record/overtime?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('leaveApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/attendance/record/leave?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('statisApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/attendance/record/stat?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('infoApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/user/info?PHPSESSID=' + localStorage.token 
				});
			}
		};	
	}])
	.factory('msgApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage, filter) {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/message?PHPSESSID=' + localStorage.token + '&page=' + page + '&per-page=' +  perPage + '&from_app=' + filter
				});
			}
		};	
	}])
	.factory('msgDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (msgId) {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/message/'+ msgId +'?PHPSESSID=' + localStorage.token
				});
			}
		};	
	}])
	.factory('msgUnreadApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage, filter) {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/message?PHPSESSID=' + localStorage.token + '&page=' + page + '&per-page=' +  perPage + '&is_read=0' + '&from_app=' + filter
				});
			}
		};	
	}])
	.factory('msgDeleteApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/common/message/deleted?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('msgMarkReadApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/common/message/read?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('msgMarkReadAllApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/common/message/read?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('msgStatisApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/message/stat?PHPSESSID=' + localStorage.token
				});
			}
		};	
	}])
	.factory('myPublishArticleApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/knowledge/article/my?PHPSESSID=' + localStorage.token + '&expand=user&page=' + page + '&per-page=' + perPage
				});
			}
		};	
	}])
	.factory('myAdmirePostApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/bbs/article/my-admire?PHPSESSID=' + localStorage.token + '&expand=user&page=' + page + '&per-page=' + perPage
				});
			}
		};	
	}])
	.factory('opCancelCollectApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/user/operate/collect?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('opDelCommentApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/user/operate/comment?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('opDelPublishApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (params) {
				return $http({
					method: 'POST',
					url: apiUrl + '/user/operate/publish?PHPSESSID=' + localStorage.token,
					data: params
				});
			}
		};	
	}])
	.factory('personalKmCommentApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (status, page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/knowledge/comment/my?PHPSESSID=' + localStorage.token + '&expand=article&page=' + page + '&per-page=' + perPage
				});
			}
		};	
	}])
	.factory('personalBbsCommentApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/bbs/comment/my?PHPSESSID=' + localStorage.token + '&expand=article&page=' + page + '&per-page=' + perPage
				});
			}
		};	
	}])
	.factory('scoreTotalApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function () {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/score/stat?PHPSESSID=' + localStorage.token
				});
			}
		};	
	}])
	.factory('scoreListApi', ['$http', 'apiUrl', function($http, apiUrl){
		return {
			doRequest: function (page, perPage) {
				return $http({
					method: 'GET',
					url: apiUrl + '/common/score?PHPSESSID=' + localStorage.token + '&page=' + page + '&per-page=' + perPage
				});
			}
		};	
	}])
	.factory('msgData', ['$http', '$q', 'apiUrl', function($http, $q, apiUrl) {
		var msgInfo = {};
		return {
			getMsgInfo: function() {
				return $http.get(apiUrl + '/common/message/stat?PHPSESSID=' + localStorage.token).then(function(res) {
					msgInfo = res;
					return msgInfo;
				})
			}
		};
	}]);
}).call(this);