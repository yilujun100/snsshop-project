/**
 * Created by KellyJia on 2017/3/27.
 */
(function() {
  'use strict';
  angular.module('pincodeApp.controller.prizeRecord', [])
    .controller('prizeRecordCtrl', prizeRecordCtrl);

  function prizeRecordCtrl($scope, dateRangePicker) {
    // dateRange
    $scope.opts = {
      locale: dateRangePicker.locale
    };
    $scope.date = dateRangePicker.default;
    $scope.setDateRange = function(lastDays) {
      dateRangePicker.setDate(lastDays);
    };

  }

  prizeRecordCtrl.$inject = ['$scope','dateRangePicker'];
})();