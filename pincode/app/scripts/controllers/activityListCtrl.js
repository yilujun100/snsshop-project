(function() {
	'use strict';
	angular.module('pincodeApp.controller.activityList', [])
		.controller('activityListCtrl', activityListCtrl);

	function activityListCtrl($scope) {
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

	activityListCtrl.$inject = ['$scope'];
})();