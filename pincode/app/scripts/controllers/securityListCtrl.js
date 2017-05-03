(function ($) {
  'use strict';
  angular.module('pincodeApp.controller.securityList', [])
    .controller('securityListCtrl', securityListCtrl);

  function securityListCtrl($scope) {
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

  securityListCtrl.$inject = ['$scope'];
})(jQuery);