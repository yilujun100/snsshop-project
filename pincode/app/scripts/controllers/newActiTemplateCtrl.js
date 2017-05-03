(function() {
	'use strict';
	angular.module('pincodeApp.controller.newActiTemplate', [])
		.controller('newActiTemplateCtrl', newActiTemplateCtrl);

	function newActiTemplateCtrl($scope, dateRangePicker, dateTimeRangePicker, singleDateTimePicker) {

		// dateTimeRange
		$scope.dateTimeRangeopts = dateTimeRangePicker.opts;
		$scope.dateTimeRange = {
			startDate: null,
			endDate: null
		};

		// dateTime
		$scope.singleDateTimeOpts = singleDateTimePicker.opts;
		$scope.singleDateTime = {
			startDate: null,
			endDate: null
		};

		$scope.tinymceOptions = {
			height: 200,
			theme: 'modern',
    		menubar: false,
    		toolbar: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | forecolor backcolor',
    		language: 'zh_CN'
		}
	}

	newActiTemplateCtrl.$inject = ['$scope', 'dateRangePicker', 'dateTimeRangePicker', 'singleDateTimePicker'];
})();