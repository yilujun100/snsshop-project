/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('workorder.resource',['ngResource'])
        .factory('workorderApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('ticket',{},{
                'ticketType':{method:'GET',url:('ticket/record/flow-list')},
                'formField':{method:'post',url:('ticket/record/form-field')},
                'recordList':{method:'POST',url:('ticket/record/record-list')},
                'manageList':{method:'POST',url:('ticket/work-order/list')},
                'approve':{method:'POST',url:('ticket/work-order/approval')},
                'recordDetail':{method:'POST',url:('ticket/record/record-detail')},
                'save':{method:'POST',url:('ticket/record/record-create')},
                'update':{method:'POST',url:('ticket/record/record-update')},
                'cancel':{method:'POST',url:('ticket/record/record-cancel')},
                
                
                // 'accessTime':{method:'POST',url:('room/book/access-time'),isArray:true},
                
            });
        }])      
}).call(this);
