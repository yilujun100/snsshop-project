(function() {
	'use strict';
	angular.module('pincodeApp.controller.auth', [])
		.controller('authCtrl', authCtrl);

	function authCtrl($scope) {

		// validate
		$scope.userNameFlag = false;
		$scope.pwdFlag = false;
		$scope.showErrorUserName = {
			required: false,
			pattern: false
		};
		$scope.showErrorPwd = {
			required: false,
			minlength: false
		};

		$scope.checkUserName = function() {
			if($scope.loginForm.username.$error) {
				if ($scope.loginForm.username.$error.required) {
					$scope.showErrorUserName.required = true;
				} else if ($scope.loginForm.username.$error.pattern) {
					$scope.showErrorUserName.required = false;
					$scope.showErrorUserName.pattern = true;
				} else {
					$scope.showErrorUserName.pattern = false;
					$scope.userNameFlag = true;
				}
			} else {
				$scope.showErrorUserName.required = false;
				$scope.showErrorUserName.pattern = false;
			}
		};

		$scope.checkPwd = function() {
			if($scope.loginForm.pwd.$error) {
				if ($scope.loginForm.pwd.$error.required) {
					$scope.showErrorPwd.required = true;
				} else if ($scope.loginForm.pwd.$error.minlength) {
					$scope.showErrorPwd.required = false;
					$scope.showErrorPwd.minlength = true;
				} else {
					$scope.showErrorPwd.minlength = false;
					$scope.pwdFlag = true;
				}
			}
		};

		$scope.validate = function() {
			var submitFlag = $scope.userNameFlag && $scope.pwdFlag;
			var params = {};

			$scope.checkUserName();
			$scope.checkPwd();
			if (submitFlag) {
				params.userName = $scope.user.name;
				params.pwd = $scope.user.pwd;
				params.autoLogin = $scope.user.autoLogin == true ? 1 : 0;

				console.log(params);
			}
		};

	}

	authCtrl.$inject = ['$scope'];
})()