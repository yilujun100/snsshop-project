/**
 * [description]
 * @return {[type]} [description]
 */
(function() {
    'use strict';
    angular.module('approval.resource', ['ngResource'])
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
                        url: apiUrl + '/approval/record/approval-list?PHPSESSID=' + sessionStorage.token + '&expand=approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&approval_status=' + status
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
                        url: apiUrl + '/approval/record/apply-list?PHPSESSID=' + sessionStorage.token + '&expand=approvalRecordFields&expand-fields=&fields=&keyword=&page=' + page + '&per-page=' + perPage + '&status=' + status
                    });
                }
            };
        }])
        // 审批发起申请
        .factory('approveApplyApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                // doRequest: function(index) {
                //     return $http({
                //         method: 'GET',
                //         url: 'http://devqyftapi.snsshop.net/leave/record/apply-list?expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=1&per-page=15&status='
                //     });
                // }
            };
        }])
        // 请假审批管理
        .factory('leaveManageApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(index) {
                    return $http({
                        method: 'GET',
                        url: 'http://devqyftapi.snsshop.net/leave/record/apply-list?expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=1&per-page=15&status='
                    });
                }
            };
        }])
        // 请假记录
        .factory('leaveRecordApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(index) {
                    return $http({
                        method: 'GET',
                        url: 'http://devqyftapi.snsshop.net/leave/record/apply-list?expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=1&per-page=15&status='
                    });
                }
            };
        }])
        // 请假发起申请
        .factory('leaveApplyApi', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                // doRequest: function(index) {
                //     return $http({
                //         method: 'GET',
                //         url: 'http://devqyftapi.snsshop.net/leave/record/apply-list?expand=leaveRecord,approvalRecordFields&expand-fields=&fields=&keyword=&page=1&per-page=15&status='
                //     });
                // }
            };
        }])
}).call(this);
