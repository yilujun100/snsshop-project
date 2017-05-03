(function() {
	'use strict';
	angular.module('conference.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'conference';
	})
}).call(this);