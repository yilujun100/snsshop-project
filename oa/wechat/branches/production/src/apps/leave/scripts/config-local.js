(function() {
    'use strict';
    angular.module('leave.configurations',['app.configurations.config']).config(function(globalConfig){
        globalConfig.moduleCode = 'leave';
    })
}).call(this);