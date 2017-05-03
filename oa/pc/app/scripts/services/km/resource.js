(function() {
	'use strict';
	angular.module('km.resource', ['ngResource'])
		.factory('articleListApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage, typeId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/article?PHPSESSID=' + localStorage.token + '&expand=type,user,flow&expand-fields=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&sort=sort%20DESC&status='+ status +'&type_id=' + typeId
					});
				}				
			};
		}])
		.factory('articleRecommendApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/article?PHPSESSID=' + localStorage.token + '&expand=type,user,flow&expand-fields=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&sort=sort%20DESC&status=&is_promote=1' 
					});
				}				
			};
		}])
		.factory('myCardApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function () {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/flow/my?PHPSESSID=' + localStorage.token
					});
				}				
			};
		}])
		.factory('hotTagApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function () {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/tage?PHPSESSID=' + localStorage.token
					});
				}				
			};
		}])
		.factory('searchKmApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage, keyword) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/article?PHPSESSID=' + localStorage.token + '&expand=type,user,flow&expand-fields=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&sort=sort%20DESC&title=LIKE_'+ keyword
					});
				}				
			};
		}])
		.factory('searchTagApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage, tagId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/tage-article?PHPSESSID=' + localStorage.token + '&id='+ tagId +'&expand=article&expand-fields=article.user,article.flow&fields=&page='+ page +'&per-page='+ perPage
					});
				}				
			};
		}])
		.factory('articleDetailApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/article/'+ articleId +'?PHPSESSID=' + localStorage.token + '&expand=user,flow&expand-fields=&fields='
					});
				}				
			};
		}])
		.factory('articlePrevNextApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (articleId, params) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/knowledge/article/sibling/'+ articleId +'?PHPSESSID=' + localStorage.token,
						data: params
					});
				}				
			};
		}])
		.factory('articleCommentApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage, articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/comment?article_id='+ articleId +'&PHPSESSID=' + localStorage.token + '&expand-fields=&fields=&keyword=&page='+ page +'&per-page='+ perPage +'&status='
					});
				}				
			};
		}])
		.factory('flowKmApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/knowledge/flow?PHPSESSID=' + localStorage.token,
						data: params
					});
				},
				doUpdateRequest: function (params) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/knowledge/flow/'+ params.id +'?PHPSESSID=' + localStorage.token,
						data: params
					});
				}
			};
		}])
		.factory('kmCommentApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/knowledge/comment?PHPSESSID=' + localStorage.token,
						data: params
					})
				}
			}
		}])
		.factory('myAdmireApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/flow?PHPSESSID='+ localStorage.token +'&expand=article&expand-fields=article.user&fields=&is_admire=1&page='+ page +'&per-page='+ perPage
					});
				}				
			};
		}])
		.factory('myCommentKmApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/flow?PHPSESSID='+ localStorage.token +'&expand=article&expand-fields=article.user&fields=&is_comment=1&page='+ page +'&per-page='+ perPage
					});
				}				
			};
		}])
		.factory('myCollectKmApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (status, page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/flow?PHPSESSID='+ localStorage.token +'&expand=article&expand-fields=article.user&fields=&is_collect=1&page='+ page +'&per-page='+ perPage
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
		.factory('columnKmApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function () {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/type?PHPSESSID=' + localStorage.token + '&sort= sort DESC' 
					});
				}
			};	
		}])
		.factory('tagApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function () {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/tage?PHPSESSID=' + localStorage.token + '&sort= sort DESC' 
					});
				}
			};	
		}])
		.factory('articlePublishApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/knowledge/article?PHPSESSID=' + localStorage.token,
						data: params
					});
				},
				doUpdateRequest: function (articleId, params) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/knowledge/article/'+ articleId +'?PHPSESSID=' + localStorage.token,
						data: params
					});
				}
			};	
		}])
		.factory('articleInfoEditApi', ['$http', 'apiUrl', function($http, apiUrl){
			return {
				doRequest: function (articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/knowledge/article/'+ articleId +'?PHPSESSID=' + localStorage.token + '&expand=tages'
					});
				}
			};	
		}])
}).call(this);