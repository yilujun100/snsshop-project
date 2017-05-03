/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('app.services.resource',['ngResource'])
        .factory('commonUserApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('common/user',{},{
            	'qyauth':{method:'get',url:('common/qy-wx-user/login')},
                'auth':{method:'POST',url:('common/user/auth')},
                'login':{method:'GET',url:('common/user/login')}
            });
        }])
}).call(this);
