(function() {
    'use strict';
    angular.module('directory.configurations',['app.configurations.config']).config(function(globalConfig){
        globalConfig.moduleCode = 'directory';
    })
}).call(this);