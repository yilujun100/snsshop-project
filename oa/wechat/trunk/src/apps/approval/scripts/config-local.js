(function() {
	'use strict';
	angular.module('approval.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'approval';
	})
}).call(this);