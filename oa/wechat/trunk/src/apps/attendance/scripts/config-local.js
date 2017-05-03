(function() {
    'use strict';
    angular.module('attendance.configurations',['app.configurations.config']).config(function(globalConfig){
        globalConfig.moduleCode = 'attendance';
    })
}).call(this);