(function() {
    'use strict';
    angular.module('app.configurations.config-local',['app.configurations.config']).config(function(globalConfig){
        globalConfig.apiUrl = 'http://devqyftapi.snsshop.net';
        globalConfig.debug = true;
    })
}).call(this);