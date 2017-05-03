/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('leave.resource',['ngResource'])
        .factory('flowApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('leave/flow');
        }])
        .factory('recordApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('leave/record',{},{
                'applyList':{method:'GET',url:('leave/record/apply-list'),isArray:true},
                'userApplyList':{method:'GET',url:('leave/record/user-apply-list'),isArray:true},
                'approvalList':{method:'GET',url:('leave/record/approval-list'),isArray:true},
                'approve':{method:'POST',url:('leave/record/approve')},
                'cancel':{method:'POST',url:('leave/record/cancel')}
            });
        }])
}).call(this);
