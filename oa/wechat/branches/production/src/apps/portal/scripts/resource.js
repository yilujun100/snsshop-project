(function() {
'use strict';
angular.module('portal.resource', ['ngResource'])
	.factory('columnApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/column');
	}])
	.factory('userApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/user');
	}])
	.factory('articleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/article', {'column_id': 2});
	}])
	.factory('articleDetailApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/article');
	}])
	.factory('noticeApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/article', {'column_id': 1});
	}])
	.factory('systemApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/article', {'column_id': 3});
	}])
	.factory('infoApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('common/user/info');
	}])
	.factory('rotateApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/rotate');
	}])
	.factory('tagApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('portal/article');
	}]);
}).call(this);