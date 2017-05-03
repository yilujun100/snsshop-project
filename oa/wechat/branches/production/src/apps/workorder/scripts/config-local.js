(function() {
	'use strict';
	angular.module('workorder.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'ticket';
	})
}).call(this);