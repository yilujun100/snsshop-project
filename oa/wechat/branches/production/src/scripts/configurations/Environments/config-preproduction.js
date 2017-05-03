(function() {
    'use strict';
    angular.module('app.configurations.config-local',['app.configurations.config']).config(function(globalConfig){
        globalConfig.webChatDebug = false;
        globalConfig.apiUrl = 'http://qyftapi.vikduo.com';
    })
}).call(this);
