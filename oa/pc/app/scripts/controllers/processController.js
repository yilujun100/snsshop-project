/**
 * 流程模块控制器
 * @return {[type]} [description]
 */
(function() {
    'use strict';
    angular.module('process.controller', ['angularFileUpload', 'ui.bootstrap', 'bootstrapLightbox'])
        .controller('processCtrl', ['$scope', '$state', 'processApi', 'auth', 'longStatus', function($scope, $state, processApi, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
        }])
        .controller('apTopBarCtrl', ['$scope', '$state', '$location', 'auth', 'longStatus', function($scope, $state, $location, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            $scope.ref = $location.path();
            $scope.partState = 'index.process.approve';
            $scope.part = '审批';

            $scope.items = [
                { id: 0, title: '发起申请' },
                { id: 1, title: '审批管理' },
                { id: 2, title: '审批记录' }

            ];
            $scope.changeIndex = function(index) {
                switch (index) {
                    case 0:
                        $state.go("index.process.approve.apply");
                        break;
                    case 1:
                        $state.go("index.process.approve.manage");
                        break;
                    case 2:
                        $state.go("index.process.approve.record");
                        break;
                    default:
                        break;
                }
            };
            if ($scope.ref === '/index/process/approve' || $scope.ref === '/index/process/approve/apply' || $scope.ref.indexOf('/index/process/approve/ap_update') >= 0 || $scope.ref.indexOf('/index/process/approve/apply/') >= 0) {
                $scope.content = $scope.items[0];
                $scope.selectnav = $scope.items[0].title;
            } else if ($scope.ref === '/index/process/approve/manage') {
                $scope.content = $scope.items[1];
                $scope.selectnav = $scope.items[1].title;
            } else if ($scope.ref === '/index/process/approve/record') {
                $scope.content = $scope.items[2];
                $scope.selectnav = $scope.items[2].title;
            }
        }])
        .controller('leaveTopBarCtrl', ['$scope', '$state', '$location', 'auth', 'longStatus', function($scope, $state, $location, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            $scope.ref = $location.path();
            $scope.partState = 'index.process.leave';
            $scope.part = '请假';
            $scope.items = [
                { id: 0, title: '发起请假' },
                { id: 1, title: '审批管理' },
                { id: 2, title: '请假记录' }

            ];
            $scope.changeIndex = function(index) {
                switch (index) {
                    case 0:
                        $state.go("index.process.leave.apply");
                        break;
                    case 1:
                        $state.go("index.process.leave.manage");
                        break;
                    case 2:
                        $state.go("index.process.leave.record");
                        break;
                }
            };
            if ($scope.ref === '/index/process/leave' || $scope.ref === '/index/process/leave/apply' || $scope.ref.indexOf('/index/process/leave/update') >= 0) {
                $scope.content = $scope.items[0];
                $scope.selectnav = $scope.items[0].title;
            } else if ($scope.ref === '/index/process/leave/manage') {
                $scope.content = $scope.items[1];
                $scope.selectnav = $scope.items[1].title;
            } else if ($scope.ref === '/index/process/leave/record') {
                {
                    $scope.content = $scope.items[2];
                    $scope.selectnav = $scope.items[2].title;
                }
            }
        }])
        .controller('processleftbarCtrl', ['$scope', '$rootScope', 'personalCenterApi', 'approveApplyTypeApi', function($scope, $rootScope, personalCenterApi, approveApplyTypeApi) {
            $scope.isOpen = true;
            $scope.$watch('isOpen', function(val) {
                $rootScope.isOpen = val;
                $rootScope.$broadcast('leftChange', val);
            });


            if (localStorage['user']) {
                $scope.headimg = JSON.parse(localStorage['user']).avatar;
                $scope.name = JSON.parse(localStorage['user']).name;
            } else {
                personalCenterApi.doRequest().success(function(data) {
                    var resResult = data;
                    $scope.headimg = resResult.info.avatar;
                    $scope.name = resResult.info.name;
                });
            }

            $scope.toggleFn = function() {
                $scope.isOpen = !$scope.isOpen;
                if ($scope.isOpen === true) {
                    $('.leftbar').animate({ width: '190px' }, 300);
                } else {
                    $('.leftbar').animate({ width: '80px' }, 300);
                }
            };
            approveApplyTypeApi.doRequest().success(function(data) {
                $scope.flows = data;
            });
        }])
        // 审批管理
        .controller('approveManageCtrl', ['$scope', '$state', 'approveManageApi', 'auth', 'longStatus', function($scope, $state, approveManageApi, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            /*
            参数：
             pageNo为页码
             itemsCount为记录的数量
             pageSize为每页显示数量
             */
            $scope.page = {
                "pageSize": 7,
                "pageNo": 1,
                "totalCount": 10
            };
            $scope.apManage = {};
            $scope.doRequest = function(status, page, perPage) {
                approveManageApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.apManage.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                        // console.log(data, stat, headers);
                    });
            };
            $scope.apManage.status = '1'; //默认待审批
            $scope.doRequest('1', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.apManage.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        // 审批记录
        .controller('approveRecordCtrl', ['$scope', '$state', 'approveRecordApi', 'auth', 'longStatus', function($scope, $state, approveRecordApi, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            /*
            参数：
             pageNo为页码
             itemsCount为记录的数量
             pageSize为每页显示数量
             */
            $scope.page = {
                "pageSize": 7,
                "pageNo": 1,
                "totalCount": 50
            };
            $scope.apRecord = {};
            $scope.doRequest = function(status, page, perPage) {
                approveRecordApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.apRecord.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                        // console.log(data, stat, headers);
                    });
            };
            $scope.apRecord.status = ''; //默认显示全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.apRecord.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        //审批申请类型
        .controller('apApplyTypeCtrl', ['$scope', 'approveApplyTypeApi', function($scope, approveApplyTypeApi) {
            approveApplyTypeApi.doRequest().success(function(data) {
                $scope.flows = data;
            });
        }])
        //审批申请
        .controller('approveApplyFormCtrl', ['$scope', '$stateParams', '$state', 'FileUploader', 'globalFunction', 'apflowApi', 'aprecordApi', 'auth', 'longStatus', 'apiUrl', 'ngDialog', 'approveApplyTypeApi', function($scope, $stateParams, $state, FileUploader, globalFunction, apflowApi, aprecordApi, auth, longStatus, apiUrl, ngDialog, approveApplyTypeApi) {
            // apflowApi.query().$promise.then(function(data) {
            // var leaveId = data[0].id;
            // angular.forEach($scope.flows,function(flow){
            //     console.log(flow.id);
            // if(flow.department_id){
            //     flow.show_department = _.where(data,{form_id:flow.form_id}).length > 1;
            // }else{
            //     flow.show_department = false;
            // }
            // })
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            // 申请类型
            if ($stateParams.flow_name)
                $scope.flow_state_name = $stateParams.flow_name;
            else {
                approveApplyTypeApi.doRequest().success(function(data) {
                    $scope.flow_state_name = data.filter(function(item) {
                        return item.id == $stateParams.flow_id;
                    });
                    $scope.flow_state_name = $scope.flow_state_name[0].name;
                });
            }

            // select控件
            // $scope.selectApply = {};

            $scope.opts = {
                // startDate:moment(),
                singleDatePicker: true,
                timePicker24Hour: true,
                timePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    // "separator": " - ",
                    "applyLabel": "确定",
                    "cancelLabel": "取消",
                    // "fromLabel": "起始时间",
                    // "toLabel": "结束时间'",
                    // "customRangeLabel": "自定义",
                    "weekLabel": "W",
                    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "firstDay": 1
                },
            };


            apflowApi.get(globalFunction.generateUrlParams({ id: $stateParams.flow_id }, { form: { approvalFormFields: '' }, approvalSpecialFields: {}, approvalSpecialData: {} })).$promise.then(function(data) {
                $scope.flow = data;
                // if($routeParams.department_id)
                //     $scope.flow.department_id = $routeParams.department_id
                angular.forEach($scope.flow.form.approvalFormFields, function(field) {
                    switch (field.type) {
                        case 'select':
                            field.data = field.data.split(',');
                            break;
                        case 'attachment':
                            field.value = [];
                            break;
                        case 'datetime':
                            field.value = moment().format('YYYY-MM-DD HH:mm');
                            break;
                    }
                })
                $scope.special_fields = data.approvalSpecialFields;
                $scope.special_data = data.approvalSpecialData;
                $scope.fields = $scope.flow.form.approvalFormFields;
            });


            var submitAll = function() {
                aprecordApi.save($scope.flow).$promise.then(function() {
                    $scope.msg = $scope.flow.form.name + '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.process.approve.record');
                    });

                }, function(response) {
                    $scope.msg = response.data.message;
                    ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                })
            };


            var uploader = $scope.uploader = new FileUploader({
                url: apiUrl + '/leave/record/upload?PHPSESSID=' + localStorage.token,
                // formData: [{'corp_id': $scope.corp_id}],
                // autoUpload: true
            });

            // FILTERS

            uploader.filters.push({
                name: 'imageFilter',
                fn: function(item /*{File|FileLikeObject}*/ , options) {
                    var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            });

            uploader.onSuccessItem = function(fileItem, response, status, headers) {
                angular.forEach($scope.fields, function(field) {
                    switch (field.type) {
                        case 'attachment':
                            field.value.push({ "image": response.image, "size": response.size });
                            break;
                    }
                })
            };

            uploader.onCompleteAll = function() {
                submitAll();
            };

            //提交所有信息
            $scope.submit = function() {
                if (uploader.getNotUploadedItems().length)
                    uploader.uploadAll();
                else
                    submitAll();
            };
        }])
        // 每条审批记录详情
        .controller('approveDetailCtrl', ['$scope', '$timeout', '$state', '$stateParams', '$location', 'globalFunction', 'ModalService', 'modalExtension', 'apflowApi', 'aprecordApi', 'auth', 'longStatus', 'ngDialog', 'Lightbox','ftUrl',
            function($scope, $timeout, $state, $stateParams, $location, globalFunction, ModalService, modalExtension, apflowApi, aprecordApi, auth, longStatus, ngDialog, Lightbox, ftUrl) {
                $scope.showPage = false;
                if (auth.checkToken($state.current.name)) {
                    $scope.showPage = true;
                }
                //$scope.leftstatus = longStatus.getStatus();
                $scope.approval_info = {
                    comment: '',
                    result: '',
                    record_id: '',
                    update_time: ''
                };
                $scope.upload_url = ftUrl + '/upload/attachment/';
                $scope.is_submitting = false;

                aprecordApi.get(globalFunction.generateUrlParams({ id: $stateParams.detail_id }, { approvalRecordComments: {}, approvalRecordFields: {}, approvalRecordSteps: {}, approvalSpecialFields: {} })).$promise.then(function(data) {
                    var approval_step;
                    $scope.record = data;
                    $scope.approval_info.record_id = $scope.record.id;
                    $scope.approval_info.update_time = $scope.record.update_time;
                    approval_step = _.groupBy($scope.record.approvalRecordSteps, 'index');
                    $scope.approval_step = [];
                    _.each(approval_step, function(step_data) {
                        var step;
                        if (step_data.length == 1) { //如果审批人只有一个，则直接返回
                            step = step_data[0];
                        } else {
                            if (step_data[0].type == 1) { //或签
                                step = _.findWhere(step_data, { "status": 1 }); //已审批
                                if (!step) {
                                    step = {
                                        "approver_name": _.pluck(step_data, 'approver_name').join('/'), //"/"表示或
                                        "status": 0 //未审批
                                    }
                                }
                            } else { //会签
                                var step_reject;
                                step = {
                                    "approver_name": _.pluck(step_data, 'approver_name').join(',') // ","表示且
                                }
                                step_reject = _.findWhere(step_data, { "approve_result": 0 });
                                if (step_reject) //会签中，只要有一人拒绝，那这一步就当是拒绝的
                                    step = step_reject;
                                else { //如果全部人都已审批，且没有人拒绝，则视为该步骤通过
                                    step.status = _.where(step_data, { "status": 0 }).length > 0 ? 0 : 1; //未审批
                                    if (step.status)
                                        step.approve_result = 1;
                                }

                            }
                        }
                        $scope.approval_step.push(step)
                    })
                });
                $scope.approval = function(result) {
                    $scope.approval_info.result = result;
                    if ($scope.approval_info.comment == '' && !result) {
                        $scope.msg = '驳回时必须填写审批意见';
                        ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        return;
                    }
                    $scope.is_submitting = true;
                    aprecordApi.approve($scope.approval_info).$promise.then(function() {
                        $scope.msg = '审批成功';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $state.go("index.process.approve.manage");
                        });


                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.cancel = function() {
                    $scope.is_submitting = true;
                    aprecordApi.cancel({ id: $scope.record.id, update_time: $scope.record.update_time }).$promise.then(function() {
                        $scope.msg = '撤销成功';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $state.reload();
                        });


                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.resubmit = function() {
                    var loading;
                    $scope.is_submitting = true;
                    aprecordApi.get({ id: $scope.record.id }).$promise.then(function(data) {
                        if (data.update_time != $scope.record.update_time) {
                            $scope.msg = '申请已变更，请刷新页面';
                            ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                        } else {
                            $state.go('index.process.approve.update', { record_id: $scope.record.id });
                        }
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }

                $scope.openLightboxModal = function(images) {
                    $scope.images = [{
                        'url': images
                    }];
                    Lightbox.openModal($scope.images, 0);
                };
            }
        ])
        // 重新提交审批申请
        .controller('approveUpdateCtrl', ['$scope', '$stateParams', '$timeout', '$state', 'leaveIdApi', 'globalFunction', 'apflowApi', 'aprecordApi', 'auth', 'longStatus', 'FileUploader', 'apiUrl', 'ngDialog', 'approveApplyTypeApi', function($scope, $stateParams, $timeout, $state, leaveIdApi, globalFunction, apflowApi, aprecordApi, auth, longStatus, FileUploader, apiUrl, ngDialog, approveApplyTypeApi) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            // select控件
            // $scope.selectApply = {};

            $scope.opts = {
                // startDate:moment(),
                singleDatePicker: true,
                timePicker24Hour: true,
                timePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    // "separator": " - ",
                    "applyLabel": "确定",
                    "cancelLabel": "取消",
                    // "fromLabel": "起始时间",
                    // "toLabel": "结束时间'",
                    // "customRangeLabel": "自定义",
                    "weekLabel": "W",
                    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "firstDay": 1
                },
            };


            aprecordApi.get(globalFunction.generateUrlParams({ id: $stateParams.record_id }, { approvalRecordComments: {}, approvalRecordFields: {}, approvalRecordSteps: {}, approvalSpecialFields: {} })).$promise.then(function(data) {
                // 申请类型
                $scope.flow_state_name = data.form_name;

                $scope.record = data;
                // console.log(data);
                // if ($routeParams.department_id)
                //     $scope.flow.department_id = $routeParams.department_id

                angular.forEach($scope.record.approvalRecordFields, function(field) {
                    switch (field.type) {
                        case 'select':
                            field.data = field.data.split(',');
                            break;
                        case 'attachment':
                            if (!field.value)
                                field.value = [];
                            break;
                    }
                })
                $scope.special_fields = data.approvalSpecialFields;
                $scope.special_data = data.approvalSpecialData;
                $scope.fields = $scope.record.approvalRecordFields;
            })


            var submitAll = function() {
                aprecordApi.update($scope.record).$promise.then(function() {
                    $scope.msg = $scope.record.form_name + '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.process.approve.record');
                    });

                }, function(response) {
                    $scope.msg = response.data.message;
                    ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                })
            };


            var uploader = $scope.uploader = new FileUploader({
                url: apiUrl + '/leave/record/upload?PHPSESSID=' + localStorage.token,
                // formData: [{'corp_id': $scope.corp_id}],
                // autoUpload: true
            });

            // FILTERS
            uploader.filters.push({
                name: 'imageFilter',
                fn: function(item /*{File|FileLikeObject}*/ , options) {
                    var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            });

            uploader.onSuccessItem = function(fileItem, response, status, headers) {
                angular.forEach($scope.fields, function(field) {
                    switch (field.type) {
                        case 'attachment':
                            field.value.push({ "image": response.image, "size": response.size });
                            break;
                    }
                })
            };


            uploader.onCompleteAll = function() {
                submitAll();
            };

            //提交所有信息
            $scope.submit = function() {
                if (uploader.getNotUploadedItems().length)
                    uploader.uploadAll();
                else
                    submitAll();
            };
        }])
        //请假管理
        .controller('leaveManageCtrl', ['$scope', '$state', 'leaveManageApi', 'auth', 'longStatus', function($scope, $state, leaveManageApi, auth, longStatus) {

            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            //$scope.leftstatus = longStatus.getStatus();
            /*
            参数：
             pageNo为页码
             itemsCount为记录的数量
             pageSize为每页显示数量
             */
            $scope.page = {
                "pageSize": 7,
                "pageNo": 1,
                "totalCount": 10
            };
            $scope.leaveManage = {};
            $scope.doRequest = function(status, page, perPage) {
                leaveManageApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.leaveManage.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                        // console.log(headers('X-Pagination-Total-Count'));
                    });
            };
            $scope.leaveManage.status = '1'; //默认待审批
            $scope.doRequest('1', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.leaveManage.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        //请假记录
        .controller('leaveRecordCtrl', ['$scope', '$state', 'leaveRecordApi', 'auth', 'longStatus', function($scope, $state, leaveRecordApi, auth, longStatus) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            //$scope.leftstatus = longStatus.getStatus();
            /*
            参数：
             pageNo为页码
             itemsCount为记录的数量
             pageSize为每页显示数量
             */
            $scope.page = {
                "pageSize": 7,
                "pageNo": 1,
                "totalCount": 10
            };
            $scope.leaveRecord = {};
            $scope.doRequest = function(status, page, perPage) {
                leaveRecordApi.doRequest(status, page, perPage)
                    .success(function(data, stat, headers) {
                        $scope.leaveRecord.contents = data;
                        $scope.page.totalCount = headers('X-Pagination-Total-Count');
                        $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    });
            };
            $scope.leaveRecord.status = ''; //默认全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.leaveRecord.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        //请假申请
        .controller('leaveApplyCtrl', ['$scope', '$stateParams', '$timeout', '$state', 'FileUploader', 'leaveIdApi', 'globalFunction', 'leaveflowApi', 'leaverecordApi', 'auth', 'longStatus', 'apiUrl', 'ngDialog', function($scope, $stateParams, $timeout, $state, FileUploader, leaveIdApi, globalFunction, leaveflowApi, leaverecordApi, auth, longStatus, apiUrl, ngDialog) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }

            // select控件
            $scope.selectApply = {};

            // 日历控件
            $scope.selectDate = {
                start_time: moment().format('YYYY-MM-DD HH:mm'),
                end_time: moment().format('YYYY-MM-DD HH:mm'),
            };
            $scope.opts = {
                startDate: moment(),
                singleDatePicker: true,
                timePicker24Hour: true,
                timePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    // "separator": " - ",
                    "applyLabel": "确定",
                    "cancelLabel": "取消",
                    // "fromLabel": "起始时间",
                    // "toLabel": "结束时间'",
                    // "customRangeLabel": "自定义",
                    "weekLabel": "W",
                    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "firstDay": 1
                },
            };

            leaveflowApi.query().$promise.then(function(data) {
                var leaveId = data[0].id;
                leaveflowApi.get(globalFunction.generateUrlParams({ id: leaveId }, { form: { approvalFormFields: '' }, approvalSpecialFields: {}, approvalSpecialData: {} })).$promise.then(function(data) {
                    $scope.flow = data;
                    // if ($routeParams.department_id)
                    //     $scope.flow.department_id = $routeParams.department_id
                    $scope.special_fields = data.approvalSpecialFields;
                    $scope.special_data = data.approvalSpecialData;
                    $scope.fields = $scope.flow.form.approvalFormFields;
                    if (!$scope.fields[0].value)
                        $scope.fields[0].value = [];
                    // 类型id
                    $scope.$watch('selectApply.type', function(new_value) {
                        if (new_value)
                            $scope.special_fields.corp_type_id = new_value.id;
                    });
                    //开始日期
                    $scope.$watch('selectDate.start_time', function(newDate) {
                        if (newDate)
                            $scope.special_fields.start_time = newDate;
                    }, false);
                    //结束日期
                    $scope.$watch('selectDate.end_time', function(newDate) {
                        if (newDate)
                            $scope.special_fields.end_time = newDate;
                    }, false);
                });
            });


            var submitAll = function() {
                leaverecordApi.save($scope.flow).$promise.then(function() {
                    $scope.msg = $scope.flow.form.name + '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.process.leave.record');
                    });

                }, function(response) {
                    $scope.msg = response.data.message;
                    ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                })
            };

            //上传图片附件

            var uploader = $scope.uploader = new FileUploader({
                url: apiUrl + '/leave/record/upload?PHPSESSID=' + localStorage.token,
                // formData: [{'corp_id': $scope.corp_id}],
                // autoUpload: true
            });


            // FILTERS
            uploader.filters.push({
                name: 'imageFilter',
                fn: function(item /*{File|FileLikeObject}*/ , options) {
                    var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            });


            uploader.onSuccessItem = function(fileItem, response, status, headers) {
                // console.info('onCompleteItem', fileItem, response, status, headers);
                // console.log(response);
                $scope.fields[0].value.push({ "image": response.image, "size": response.size });
            };


            uploader.onCompleteAll = function() {
                submitAll();
            };

            //提交所有信息
            $scope.submit = function() {
                if (uploader.getNotUploadedItems().length)
                // console.log(uploader.getNotUploadedItems());
                    uploader.uploadAll();
                else
                    submitAll();
            };
        }])
        //每个请假记录详情
        .controller('leaveDetailCtrl', ['$scope', '$timeout', '$state', '$stateParams', '$location', 'globalFunction', 'ModalService', 'modalExtension', 'leaveflowApi', 'leaverecordApi', 'auth', 'longStatus', 'ngDialog', 'Lightbox','ftUrl',
            function($scope, $timeout, $state, $stateParams, $location, globalFunction, ModalService, modalExtension, leaveflowApi, leaverecordApi, auth, longStatus, ngDialog, Lightbox, ftUrl) {
                $scope.showPage = false;
                if (auth.checkToken($state.current.name)) {
                    $scope.showPage = true;
                }
                $scope.approval_info = {
                    comment: '',
                    result: '',
                    record_id: '',
                    update_time: ''
                };
                $scope.upload_url = ftUrl + '/upload/attachment';
                $scope.is_submitting = false;
                leaverecordApi.get(globalFunction.generateUrlParams({ id: $stateParams.detail_id }, { approvalRecordComments: {}, approvalRecordFields: {}, approvalRecordSteps: {}, approvalSpecialFields: {} })).$promise.then(function(data) {
                    var approval_step;
                    $scope.record = data;
                    $scope.approval_info.record_id = $scope.record.id;
                    $scope.approval_info.update_time = $scope.record.update_time;
                    approval_step = _.groupBy($scope.record.approvalRecordSteps, 'index');
                    $scope.approval_step = [];
                    _.each(approval_step, function(step_data) {
                        var step;
                        if (step_data.length == 1) { //如果审批人只有一个，则直接返回
                            step = step_data[0];
                        } else {
                            if (step_data[0].type == 1) { //或签
                                step = _.findWhere(step_data, { "status": 1 }); //已审批
                                if (!step) {
                                    step = {
                                        "approver_name": _.pluck(step_data, 'approver_name').join('/'), //"/"表示或
                                        "status": 0 //未审批
                                    }
                                }
                            } else { //会签
                                var step_reject;
                                step = {
                                    "approver_name": _.pluck(step_data, 'approver_name').join(',') // ","表示且
                                }
                                step_reject = _.findWhere(step_data, { "approve_result": 0 });
                                if (step_reject) //会签中，只要有一人拒绝，那这一步就当是拒绝的
                                    step = step_reject;
                                else { //如果全部人都已审批，且没有人拒绝，则视为该步骤通过
                                    step.status = _.where(step_data, { "status": 0 }).length > 0 ? 0 : 1; //未审批
                                    if (step.status)
                                        step.approve_result = 1;
                                }

                            }
                        }
                        $scope.approval_step.push(step)
                    })
                });
                $scope.approval = function(result) {
                    $scope.approval_info.result = result;
                    if ($scope.approval_info.comment == '' && !result) {
                        $scope.msg = '驳回时必须填写审批意见';
                        ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        return;
                    }
                    $scope.is_submitting = true;
                    leaverecordApi.approve($scope.approval_info).$promise.then(function() {
                        $scope.msg = '审批成功';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $state.go('index.process.leave.manage');
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.cancel = function() {
                    $scope.is_submitting = true;
                    leaverecordApi.cancel({ id: $scope.record.id, update_time: $scope.record.update_time }).$promise.then(function() {
                        $scope.msg = '撤销成功';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $state.reload();
                        });


                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.resubmit = function() {
                    var loading;
                    $scope.is_submitting = true;
                    leaverecordApi.get({ id: $scope.record.id }).$promise.then(function(data) {
                        if (data.update_time != $scope.record.update_time) {
                            $scope.msg = '申请已变更，请刷新页面';
                            ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                        } else {
                            $state.go('index.process.leave.update', { record_id: $scope.record.id });
                        }
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }

                $scope.openLightboxModal = function(images) {
                    $scope.images = [{
                        'url': images
                    }];
                    Lightbox.openModal($scope.images, 0);
                };
                // $scope.showImage = function(field, url) {
                //     weChat.previewImage({
                //         current: url,
                //         urls: _.map(field.value, function(image) {
                //             return $scope.upload_url + '/' + image.path
                //         })
                //     })
                // }
            }
        ])
        // 重新提交请假申请
        .controller('leaveUpdateCtrl', ['$scope', '$stateParams', '$timeout', '$state', 'FileUploader', 'leaveIdApi', 'globalFunction', 'leaveflowApi', 'leaverecordApi', 'auth', 'longStatus', 'apiUrl', 'ngDialog', function($scope, $stateParams, $timeout, $state, FileUploader, leaveIdApi, globalFunction, leaveflowApi, leaverecordApi, auth, longStatus, apiUrl, ngDialog) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }

            // select控件
            $scope.selectApply = {};

            // 日历控件 
            $scope.opts = {
                startDate: moment(),
                singleDatePicker: true,
                timePicker24Hour: true,
                timePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    // "separator": " - ",
                    "applyLabel": "确定",
                    "cancelLabel": "取消",
                    // "fromLabel": "起始时间",
                    // "toLabel": "结束时间'",
                    // "customRangeLabel": "自定义",
                    "weekLabel": "W",
                    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "firstDay": 1
                },
            };

            leaverecordApi.get(globalFunction.generateUrlParams({ id: $stateParams.record_id }, { approvalRecordFields: {}, approvalSpecialFields: {}, approvalSpecialData: {} })).$promise.then(function(data) {
                $scope.record = data;
                // console.log(data);
                // if ($routeParams.department_id)
                //     $scope.flow.department_id = $routeParams.department_id
                // angular.forEach($scope.flow.form.approvalFormFields, function(field) {
                //     switch (field.type) {
                //         case 'select':
                //             field.data = field.data.split(',');
                //             break;
                //     }
                // })
                $scope.special_fields = data.approvalSpecialFields;
                $scope.special_data = data.approvalSpecialData;
                $scope.fields = $scope.record.approvalRecordFields;
                angular.forEach($scope.special_data.types, function(field) {
                        if (field.id == $scope.special_fields.corp_type_id)
                            $scope.selectApply.type = field;
                    })
                    // 类型id
                $scope.$watch('selectApply.type', function(new_value) {
                    if (new_value)
                        $scope.special_fields.corp_type_id = new_value.id;
                });


                // console.log($scope.fields);
                if (!$scope.fields[0].value)
                    $scope.fields[0].value = [];
            })

            var submitAll = function() {
                leaverecordApi.update($scope.record).$promise.then(function() {
                    $scope.msg = $scope.record.form_name + '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.process.leave.record');
                    });

                }, function(response) {
                    $scope.msg = response.data.message;
                    ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                })
            };

            var uploader = $scope.uploader = new FileUploader({
                url: apiUrl + '/leave/record/upload?PHPSESSID=' + localStorage.token,
                // formData: [{'corp_id': $scope.corp_id}],
                // autoUpload: true
            });


            // FILTERS
            uploader.filters.push({
                name: 'imageFilter',
                fn: function(item /*{File|FileLikeObject}*/ , options) {
                    var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            });

            uploader.onSuccessItem = function(fileItem, response, status, headers) {
                $scope.fields[0].value.push({ "image": response.image, "size": response.size });
            };

            uploader.onCompleteAll = function() {
                submitAll();
            };

            //提交所有信息
            $scope.submit = function() {
                if (uploader.getNotUploadedItems().length)
                    uploader.uploadAll();
                else
                    submitAll();
            };
        }])
        // 考勤控制器
        .controller('checkInCtrl', ['$scope', '$filter', '$state', '$timeout', 'checkInApi', 'checkInRecordApi', 'attendanceSignApi', 'auth', 'longStatus', 'ngDialog', function($scope, $filter, $state, $timeout, checkInApi, checkInRecordApi, attendanceSignApi, auth, longStatus, ngDialog) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            //$scope.leftstatus = longStatus.getStatus();
            $scope.options = {
                showWeeks: false,
                formatDayTitle: "yyyy年MM月",
                formatMonthTitle: "yyyy年",
                formatMonth: "MM月",
                formatDayHeader: 'EEE',
            };
            $scope.condition = {
                time_min: { type: 'min', value: '' },
                time_max: { type: 'max', value: '' }
            }
            $scope.$watch('currentDate', function(new_value, old_value) {
                if (new_value) {
                    $scope.condition.time_min.value = Date.parse(new_value) / 1000;
                    $scope.condition.time_max.value = Date.parse(new_value) / 1000 + 86400;
                    checkInApi.doRequest($scope.condition.time_min.value, $scope.condition.time_max.value).success(function(data, stat, headers) {
                        $scope.logs = data;
                    });

                    // 全月考勤记录
                    $scope.logMonth = new_value;
                    $scope.logMonthCondition = {
                        date: { type: 'RLIKE', value: $filter('date')($scope.logMonth, 'yyyy-MM') }
                    }
                    $scope.day_arr = ['日', '一', '二', '三', '四', '五', '六'];
                    $scope.status_arr = ['', '正常', '迟到', '早退', '缺勤', '---', '迟到早退']
                        // $scope.isNextDay = function(record) {
                        //     return record.date < $filter('date')(record.sign_out_actual_time * 1000, 'yyyy-MM-dd');
                        // }
                    checkInRecordApi.doRequest($scope.logMonthCondition.date.value).success(function(data, stat, headers) {
                        $scope.records = data;
                    });



                } else {
                    $scope.logs = [];
                }
            })
            var now = new Date();
            $scope.currentDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            // checkInApi.doRequest($scope.condition.time_min.value, $scope.condition.time_max.value).success(function(data, stat, headers) {
            //     $scope.logs = data;
            // });
            // checkInApi.doRequest('', '').success(function(data, stat, headers) {
            //     $scope.logs = data;
            // });

            //考勤签到签退
            $scope.sign = function(signType) {
                if (signType == 1) //sign in
                    $scope.time_type = 'in';
                else
                    $scope.time_type = 'out';
                var sign_type = 'pc';

                attendanceSignApi.doRequest(signType, '', '', sign_type).success(function(data) {
                	if(data.error_code == 1) {
                		$scope.msg = data.error_msg;
                	}else{
                		// loading.close();
                        var type = signType == 1 ? "签到" : "签退";
                        $scope.time_type = data.time_type;
                        // modalExtension.alert(type + "成功,本次" + type + "时间为" + $filter('date')(data.time * 1000, 'HH:mm:ss'));
                        $scope.msg = type + "成功,本次" + type + "时间为" + $filter('date')(data.time * 1000, 'HH:mm:ss');
                	}
                    
                    // alert($scope.msg);
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.reload();
                    });


                }).error(function() {});

            };
        }]);
}).call(this);
