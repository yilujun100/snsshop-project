(function() {
	'use strict';
	angular.module('knowledge.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'knowledge';
	})
}).call(this);