(function() {
	'use strict';
	angular.module('portal.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'portal';
	})
}).call(this);