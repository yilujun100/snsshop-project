/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('conference.resource',['ngResource'])
        // .factory('flowApi',['globalFunction',function(globalFunction){
        //     return globalFunction.createResource('room/book');
        // }])
        .factory('conferenceApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('room/book/room-list',{},{
                'applyList':{method:'GET',url:('room/book/apply-list'),isArray:true},
                'bookList':{method:'POST',url:('room/book/book-time-list')},
                'accessTime':{method:'POST',url:('room/book/access-time'),isArray:true},
                'bookMonthList':{method:'POST',url:('room/book/book-month-list')},
                'bookTimeList':{method:'POST',url:('room/book/book-time-list')},
                'cancel':{method:'POST',url:('room/book/cancel')},
                'bookDetail':{method:'POST',url:('room/book/book-detail')},
                'bookCreate':{method:'POST',url:('room/book/book-create')}, 
                'departmentList':{method:'GET',url:('user/department/list')},
                'users':{method:'post',url:('user/department/users')},
                'bookUpdate':{method:'POST',url:('room/book/book-update')},
                'searchUser':{method:'POST',url:('user/department/find-users')},
                'ownBook':{method:'POST',url:('room/book/my-book-list')},
                'remove':{method:'POST',url:('room/book/book-del')},
                'allusers':{method:'POST',url:('user/department/all-users')},
            });
        }])
        .factory('accessTimeApi',['$http',function($http){
            return {
                doRequest: function(id, selectDate) {
                    var data = {
                            room_id: id,
                            date: selectDate, 
                        },
                        //post请求的地址
                        url = 'http://devqyftapi.snsshop.net/room/book/access-time',
                        //将参数传递的方式改成form
                        postCfg = {
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            transformRequest: function(data) {
                                return $.param(data);
                            }
                        };
                    return $http.post(url, data, postCfg)
                }
            };
        }])
}).call(this);
