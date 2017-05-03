(function() {
'use strict';
angular.module('bbs.resource', ['ngResource'])
	.factory('userApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/user');
	}])
	.factory('articleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article');
	}])
	.factory('categaryApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie');
	}])
	.factory('personalCenterApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/user/detail');
	}])
	.factory('commentApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment');
	}])
	.factory('commentAddApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment');
	}])
	.factory('commentReplyApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/and-reply');
	}])
	.factory('flowApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/flow');
	}])
	.factory('commentFlowApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/flow');
	}])
	.factory('collectApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/flow');
	}])
	.factory('replyApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/comment-article');
	}])
	.factory('myPostApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/my');
	}])
	.factory('myCircleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie/my');
	}])
	.factory('circleArticleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/coteries');
	}])
	.factory('notJoinedCircleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie/no');
	}])
	.factory('postDelApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('user/operate/publish');
	}])
	.factory('scoreTotalApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('common/score/stat');
	}])
	.factory('scoreListApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('common/score');
	}])
}).call(this);