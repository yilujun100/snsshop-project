(function() {
    'use strict';
    angular.module('app.configurations.config-local',['app.configurations.config']).config(function(globalConfig){
        globalConfig.webChatDebug = false;
        globalConfig.url = 'http://devqy.snsshop.net';
        globalConfig.apiUrl = 'http://devqyftapi.snsshop.net';
        globalConfig.uploadUrl  = 'http://devqy.snsshop.net/upload/attachment';
        globalConfig.corpID = 'wwa6a8c86c5e948bc0';
    })
}).call(this);
