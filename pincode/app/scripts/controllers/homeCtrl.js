(function() {
	'use strict';
	angular.module('pincodeApp.controller.home', [])
		.controller('homeCtrl', homeCtrl);

	function homeCtrl($scope) {
      $scope.bind=function(){
				$("#telPhone").show();
			}
	}

	homeCtrl.$inject = ['$scope'];
})();