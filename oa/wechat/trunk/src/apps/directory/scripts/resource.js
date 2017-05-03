/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('directory.resource',['ngResource'])
        .factory('userApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('directory/user');
        }])
}).call(this);
