/**
 * Created by KellyJia on 2017/3/28.
 */
(function ($) {
  'use strict';
  angular.module('pincodeApp.controller.codeAdministrate', [])
    .controller('codeAdministrateCtrl', codeAdministrateCtrl);

  function codeAdministrateCtrl($scope) {
    $scope.add = function () {
      $("#addModal").show()
    };
    $scope.close = function () {
      $("#addModal").hide()
    }
  }

  codeAdministrateCtrl.$inject = ['$scope'];
})(jQuery);