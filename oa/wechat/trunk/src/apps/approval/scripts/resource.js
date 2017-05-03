/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('approval.resource',['ngResource'])
        .factory('flowApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('approval/flow');
        }])
        .factory('recordApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('approval/record',{},{
                'applyList':{method:'GET',url:('approval/record/apply-list'),isArray:true},
                'approvalList':{method:'GET',url:('approval/record/approval-list'),isArray:true},
                'approve':{method:'POST',url:('approval/record/approve')},
                'cancel':{method:'POST',url:('approval/record/cancel')},
                'report':{method:'GET',url:('approval/report'),isArray:true},
            });
        }])
}).call(this);
