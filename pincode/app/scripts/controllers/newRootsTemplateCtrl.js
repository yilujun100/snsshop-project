(function() {
	'use strict';
	angular.module('pincodeApp.controller.newRootsTemplate', [])
		.controller('newRootsTemplateCtrl', newRootsTemplateCtrl);

	function newRootsTemplateCtrl($scope, singleDateTimePicker) {
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

	newRootsTemplateCtrl.$inject = ['$scope', 'singleDateTimePicker'];
})();