(function() {
    'use strict';
    angular.module('app.configurations.config-local',['app.configurations.config']).config(function(globalConfig){
        globalConfig.webChatDebug = false;
        globalConfig.url = 'http://qy.vikduo.com';
        globalConfig.apiUrl = 'http://qyftapi.vikduo.com';
        globalConfig.uploadUrl  = 'http://qy.vikduo.com/upload/attachment';
        globalConfig.corpID = 'WW33ce87018c3e1506';
    })
}).call(this);
