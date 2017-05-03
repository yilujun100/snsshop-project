(function() {
'use strict';
angular.module('knowledge.resource', ['ngResource'])
	.factory('articleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/article');
	}])
	.factory('recordApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/article/recode');
	}])
	.factory('articleDetailApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/article');
	}])
	.factory('flowApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/flow');
	}])
	.factory('myApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/flow/my');
	}])
	.factory('searchTagApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/tage');
	}])
	.factory('commentApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/comment');
	}])
	.factory('tagArticleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('knowledge/tage-article');
	}])
}).call(this);