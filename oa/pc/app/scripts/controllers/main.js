(function() {
    'use strict';
    angular.module('szOaApp.controller.main', [])
        .constant('paginationConfig', {
            itemsPerPage: 15
        })
        .controller('mainCtrl', mainCtrl) // 主页
        .controller('loginCtrl', loginCtrl) // 登录



    function mainCtrl($scope) {
        $scope.awesomeThings = [
            'HTML5 Boilerplate',
            'AngularJS',
            'Karma'
        ];
    }

    mainCtrl.$inject = ['$scope'];

    function loginCtrl($scope) {
        $scope.loginText = 'login test';
    }

    loginCtrl.$inject = ['$scope'];
})();
