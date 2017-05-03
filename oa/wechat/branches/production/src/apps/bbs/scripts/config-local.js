(function() {
	'use strict';
	angular.module('bbs.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'bbs';
	})
}).call(this);