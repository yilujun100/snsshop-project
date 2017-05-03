(function() {
	'use strict';
	angular.module('bbs.resource', ['ngResource'])
		.factory('circleColumnApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function() {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/coterie?PHPSESSID=' +　localStorage.token　+ '&expand=manager&sort=sort DESC'
					});
				}
			}
		}])
		.factory('articleApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article?PHPSESSID=' +　localStorage.token　+ '&expand=coteries,user&expand-fields=&fields=&page=' + page + '&per-page=' + perPage + '&sort=is_top,sort%20DESC'
					});
				}
			}
		}])
		.factory('postDetailApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/'+ articleId +'?PHPSESSID=' +　localStorage.token　+ '&expand=coteries&expand-fields='
					});
				}
			}
		}])
		.factory('flowApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doUpdateRequest: function(articleId, params) {
					return $http({
						method: 'put',
						url: apiUrl + '/bbs/article/flow/'+ articleId +'?PHPSESSID=' +　localStorage.token,
						data: params
					});
				}
			}
		}])
		.factory('commentFlowApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(articleId, params) {
					return $http({
						method: 'post',
						url: apiUrl + '/bbs/comment/flow?id='+ articleId +'&PHPSESSID=' +　localStorage.token,
						data: params
					});
				}
			}
		}])
		.factory('commentApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage, articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/comment?PHPSESSID=' +　localStorage.token　+ '&article_id='+ articleId +'&expand=flow&page=' + page + '&per-page=' + perPage + '&sort=id ASC'
					});
				}
			}
		}])
		.factory('commentAddApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doUpdateRequest: function (params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/bbs/comment?PHPSESSID=' + localStorage.token,
						data: params
					});
				}
			}
		}])
		.factory('commentReplyApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function (page, perPage, articleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/comment/and-reply?PHPSESSID=' + localStorage.token + '&article_id=' + articleId + '&page=' + page + '&per-page=' + perPage + '&sort=id ASC'
					});
				}
			}
		}])
		.factory('userInfoApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function() {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/user/detail?PHPSESSID=' +　localStorage.token
					});
				}
			}
		}])
		.factory('articleRecommApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article?PHPSESSID=' +　localStorage.token　+ '&expand=coteries,user&is_best=1&page=' + page + '&per-page=' + perPage + '&sort=sort%20DESC'
					});
				}
			}
		}])
		.factory('circleIndexApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/coteries?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage
					});
				},
				doPopularRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/coteries?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage + '&sort=view_count DESC'
					});
				},
				doCommentRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/coteries?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage + '&sort=comment_count DESC'
					});
				}
			}
		}])
		.factory('circleRecommArticleApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/promote?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage
					});
				},
				doPopularRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/promote?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage + '&sort=view_count DESC'
					});
				},
				doCommentRequest: function(page, perPage, circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/promote?PHPSESSID=' +　localStorage.token　+ '&coterie_id='+ circleId +'&expand=coteries,user&expand-fields=coteries.user&fields=&page=' + page + '&per-page=' + perPage + '&sort=comment_count DESC'
					});
				}
			}
		}])
		.factory('circleJoinedApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function() {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/coterie/my?PHPSESSID=' +　localStorage.token + '&expand=myCoterie,manager&expand-fields=&fields='
					});
				}
			}
		}])
		.factory('circleNotJoinedApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function() {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/coterie/no?PHPSESSID=' +　localStorage.token
					});
				}
			}
		}])
		.factory('circleInfoApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(circleId) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/coterie/'+ circleId +'?PHPSESSID=' +　localStorage.token + '&expand=myCoterie,manager'
					});
				}
			}
		}])
		.factory('userApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/bbs/user?PHPSESSID=' +　localStorage.token,
						data: params
					});
				},
				doDeleteRequest: function(params) {
					return $http({
						method: 'DELETE',
						url: apiUrl + '/bbs/user/'+ params.id +'?PHPSESSID=' +　localStorage.token + '&coterie_id='+ params.coterie_id +'&create_time='+ params.create_time +'&update_time='+ params.update_time +'&user_id=' + params.user_id
					});
				}
			}
		}])
		.factory('newPostApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(params) {
					return $http({
						method: 'POST',
						url: apiUrl + '/bbs/article?PHPSESSID=' +　localStorage.token,
						data: params
					});
				},
				doUpdateRequest: function(postId, params) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/bbs/article/'+ postId +'?PHPSESSID=' +　localStorage.token,
						data: params
					});
				}
			}
		}])
		.factory('myPostApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/my?PHPSESSID=' +　localStorage.token + '&expand=coteries,user&expand-fields=&fields=&page=' + page + '&per-page=' + perPage
					});
				}
			}
		}])
		.factory('myReplyApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/comment/comment-article?PHPSESSID=' +　localStorage.token + '&expand=article&expand-fields=article.user,article.coteries&fields=&page=' + page + '&per-page=' + perPage
					});
				}
			}
		}])
		.factory('myCollectApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/flow?PHPSESSID=' +　localStorage.token + '&expand=article&expand-fields=article.user,article.coteries&fields=&page=' + page + '&per-page=' + perPage + '&type=1'
					});
				}
			}
		}])
		.factory('manageArticleApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article/bm?PHPSESSID=' +　localStorage.token + '&expand=coteries,user&expand-fields=&fields=&page=' + page + '&per-page=' + perPage + '&sort=sort DESC'
					});
				}
			}
		}])
		.factory('manageActionApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doTopRequest: function(articleId) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/bbs/article/toggle-top/'+ articleId +'?PHPSESSID=' +　localStorage.token
					});
				},
				doBestRequest: function(articleId) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/bbs/article/toggle-best/'+ articleId +'?PHPSESSID=' +　localStorage.token
					});
				},
				doToggleRequest: function(articleId) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/bbs/article/toggle-status/'+ articleId +'?PHPSESSID=' +　localStorage.token
					});
				}
			}
		}])
		.factory('searchApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(page, perPage, keyword) {
					return $http({
						method: 'GET',
						url: apiUrl + '/bbs/article?PHPSESSID=' +　localStorage.token + '&expand=coteries,user&expand-fields=&fields=&page=' + page + '&per-page=' + perPage + '&sort=sort DESC&title=LIKE_' + keyword
					});
				}
			}
		}])
		.factory('uncollectApi', ['$http', 'apiUrl', function($http, apiUrl) {
			return {
				doRequest: function(articleId) {
					return $http({
						method: 'PUT',
						url: apiUrl + '/bbs/flow/un-collect/'+ articleId +'?PHPSESSID=' +　localStorage.token
					});
				}
			}
		}])
}).call(this);