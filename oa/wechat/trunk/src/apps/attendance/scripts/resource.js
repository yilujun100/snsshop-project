/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('attendance.resource',['ngResource'])
        .factory('attendanceLogApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('attendance/log');
        }])
        .factory('attendanceSignApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('attendance/sign',{},{
                'currentTime':{method:'GET',url:('attendance/sign/current-time')},
                'save': { method:'POST',url:"attendance/sign",ignoreLoadingBar: true }
            });
        }])
        .factory('attendanceRecordApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('attendance/record');
        }])
}).call(this);
