(function() {
    'use strict';
    angular.module('app.configurations.config',[]).constant('globalConfig', {
        webChatDebug:true,
        apiUrl:'',
        uploadUrl:'',
        debug: false,
        url:''
    })
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.configurations.config-local',['app.configurations.config']).config(function(globalConfig){
        globalConfig.webChatDebug = false;
        globalConfig.apiUrl = 'http://devqyftapi.snsshop.net';
    })
}).call(this);
