/**
 * [description]
 * @return {[type]} [description]
 */
(function() {
    'use strict';
    angular.module('approval.resource', ['ngResource'])
        // 流程模块
        .factory('processApi', ['$http', function($http) {
            // return $resource('/card/user/:userID/:id', { userID: 123, id: '@id' }, { charge: { method: 'POST', params: { charge: true }, isArray: false } })
            return {}
        }])
        // 审批管理
        .factory('approveManageApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(status, page, perPage) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/approval/record/approval-list?PHPSESSID=' + localStorage.token + '&expand=approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&approval_status=' + status
                    });
                }
            };
        }])
        // 审批记录
        .factory('approveRecordApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(status, page, perPage) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/approval/record/apply-list?PHPSESSID=' + localStorage.token + '&expand=approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&status=' + status
                    });
                }
            };
        }])
        // 审批发起申请获取类型
        .factory('approveApplyTypeApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function() {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/approval/flow?PHPSESSID=' + localStorage.token
                    });
                }
            };
        }])
        //每条记录详情
        // .factory('approveDetailApi', ['$http', 'apiUrl', function($http, apiUrl) {
        //     return {
        //         doRequest: function(id) {
        //             return $http({
        //                 method: 'GET',
        //                 url: apiUrl + '/approval/record/' + id + '?expand=approvalRecordComments,approvalRecordFields,approvalRecordSteps,approvalSpecialFields&expand-fields=&fields='
        //             });
        //         }
        //     };
        // }])
        .factory('apflowApi', ['globalFunction', function(globalFunction) {
            return globalFunction.createResource('approval/flow');
        }])
        .factory('aprecordApi', ['globalFunction', function(globalFunction) {
            return globalFunction.createResource('approval/record', {}, {
                'applyList': { method: 'GET', url: ('approval/record/apply-list'), isArray: true },
                'approvalList': { method: 'GET', url: ('approval/record/approval-list'), isArray: true },
                'approve': { method: 'POST', url: ('approval/record/approve') },
                'cancel': { method: 'POST', url: ('approval/record/cancel') }
            });
        }])
        // 请假审批管理
        .factory('leaveManageApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(status, page, perPage) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/leave/record/approval-list?PHPSESSID=' + localStorage.token + '&expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&approval_status=' + status
                    });
                }
            };
        }])
        // 请假记录
        .factory('leaveRecordApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(status, page, perPage) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/leave/record/apply-list?PHPSESSID=' + localStorage.token + '&expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&status=' + status
                    });
                }
            };
        }])
        // 请假发起申请
        .factory('leaveIdApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(index) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/leave/flow'
                    });
                }
            };
        }])
        // .factory('leaveApplyFieldApi', ['$http', 'apiUrl', function($http, apiUrl) {
        //     return{
        //         doRequest: function(file) {
        //             var data = {
        //                     file: file,
        //                     corp_id: 28
        //                 },
        //                 //post请求的地址
        //                 url = apiUrl + '/leave/record/upload?PHPSESSID=' + localStorage.token,
        //                 //将参数传递的方式改成form
        //                 postCfg = {
        //                     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        //                     transformRequest: function(data) {
        //                         return $.param(data);
        //                     }
        //                 };
        //             return $http.post(url, data, postCfg)
        //         }
        //     };
        // }])
        // 每条请假记录详情
        .factory('leaveflowApi', ['globalFunction', function(globalFunction) {
            return globalFunction.createResource('leave/flow');
        }])
        .factory('leaverecordApi', ['globalFunction', function(globalFunction) {
            return globalFunction.createResource('leave/record', {}, {
                'applyList': { method: 'GET', url: ('leave/record/apply-list'), isArray: true },
                'userApplyList': { method: 'GET', url: ('leave/record/user-apply-list'), isArray: true },
                'approvalList': { method: 'GET', url: ('leave/record/approval-list'), isArray: true },
                'approve': { method: 'POST', url: ('leave/record/approve') },
                'cancel': { method: 'POST', url: ('leave/record/cancel') }
            });
        }])
        //考勤
        .factory('checkInApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(time_min, time_max) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/attendance/log?PHPSESSID=' + localStorage.token + '&time_max=MAX_' + time_max + '&time_min=MIN_' + time_min
                    });
                }
            };
        }])
        .factory('checkInRecordApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(date) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/attendance/record?PHPSESSID=' + localStorage.token + '&date=RLIKE_' + date
                    });
                }
            };
        }])
        .factory('attendanceSignApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(type, latitude, longitude, sign_type) {
                    var data = {
                            type: type,
                            latitude: latitude,
                            longitude: longitude,
                            sign_type: sign_type
                        },
                        //post请求的地址
                        url = apiUrl + '/attendance/sign?PHPSESSID=' + localStorage.token,
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
