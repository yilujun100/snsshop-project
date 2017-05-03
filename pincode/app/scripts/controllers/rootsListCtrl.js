(function() {
	'use strict';
	angular.module('pincodeApp.controller.rootsList', [])
		.controller('rootsListCtrl', rootsListCtrl);

	function rootsListCtrl($scope) {
		$scope.manage = function () {
			$("#myModal").show();
		};
		$scope.edit = function () {
			$("#myModal").show();
		};
		$scope.close = function () {
			$("#myModal").hide();
		}
	}

	rootsListCtrl.$inject = ['$scope'];
})();