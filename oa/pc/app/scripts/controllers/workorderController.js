/**
 * 流程模块控制器
 * @return {[type]} [description]
 */
(function() {
    'use strict';
    angular.module('workorder.controller', ['angularFileUpload', 'ui.bootstrap', 'bootstrapLightbox'])
        .controller('workorderTopBarCtrl', ['$scope', '$state', '$location', 'auth', function($scope, $state, $location, auth) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            $scope.ref = $location.path();
            $scope.items = [
                { id: 0, title: '发起申请' },
                { id: 1, title: '审批管理' },
                { id: 2, title: '申请记录' }

            ];
            $scope.changeIndex = function(index) {
                switch (index) {
                    case 0:
                        $state.go("index.workorder.apply");
                        break;
                    case 1:
                        $state.go("index.workorder.manage");
                        break;
                    case 2:
                        $state.go("index.workorder.record");
                        break;
                }
            };
            if ($scope.ref === '/index/workorder' || $scope.ref.indexOf('/index/workorder/update') >= 0 || $scope.ref.indexOf('/index/workorder/apply') >= 0) {
                $scope.content = $scope.items[0];
                $scope.selectnav = $scope.items[0].title;
            } else if ($scope.ref === '/index/workorder/manage') {
                $scope.content = $scope.items[1];
                $scope.selectnav = $scope.items[1].title;
            } else if ($scope.ref === '/index/workorder/record') {
                $scope.content = $scope.items[2];
                $scope.selectnav = $scope.items[2].title;
            }
        }])
        .controller('workorderleftbarCtrl', ['$scope', '$rootScope', 'personalCenterApi', function($scope, $rootScope, personalCenterApi) {
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
        }])
        //申请管理
        .controller('workorderManageCtrl', ['$scope', '$state', 'workorderApi', 'auth', function($scope, $state, workorderApi, auth) {
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
            $scope.workorderManage = {};
            $scope.doRequest = function(status, page, perPage) {
                workorderApi.manageList({ status: status, page: (page - 1), count: perPage }).$promise
                    .then(function(res) {
                        $scope.workorderManage.contents = res.data;
                        if (res.page) {
                            $scope.page.totalCount = res.page.total_count;
                            $scope.page.totalPage = res.page.total_page;
                        }

                        // console.log(headers('X-Pagination-Total-Count'));
                    });
            };
            $scope.workorderManage.status = '0'; //默认待审批
            $scope.doRequest('0', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.workorderManage.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        //申请记录
        .controller('workorderRecordCtrl', ['$scope', '$state', 'workorderApi', 'auth', function($scope, $state, workorderApi, auth) {
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
            $scope.workOrderRecord = {};
            $scope.doRequest = function(status, page, perPage) {
                workorderApi.recordList({ status: status, page: (page - 1), count: perPage }).$promise
                    .then(function(res) {
                        // console.log(res);
                        $scope.workOrderRecord.contents = res.data;
                        $scope.page.totalCount = res.page.total_count;
                        $scope.page.totalPage = res.page.total_page;
                    });
            };
            $scope.workOrderRecord.status = ''; //默认全部
            $scope.doRequest('', $scope.page.pageNo, $scope.page.pageSize);


            $scope.changeState = function(status) {
                $scope.workOrderRecord.status = status;
                $scope.page.pageNo = 1;
                $scope.doRequest(status, $scope.page.pageNo, $scope.page.pageSize);
            };
        }])
        //工单申请
        .controller('workorderApplyCtrl', ['$scope', '$stateParams', '$interval', '$state', 'FileUploader', 'globalFunction', 'workorderApi', 'auth', 'apiUrl', 'ngDialog', function($scope, $stateParams, $interval, $state, FileUploader, globalFunction, workorderApi, auth, apiUrl, ngDialog) {
            //是否已登录授权
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }
            //是否有适用的流程提交
            $scope.show_submit = false;
            // 提交所有数据的对象
            $scope.createOrder = {};
            //工单类型，服务类型
            $scope.selelctForm = {};
            // 日期时间控件
            // $scope.dateRangePicker = {};
            // $scope.dateRangePicker.date = {
            //     start_time: moment(),
            //     end_time: moment(),
            // };

            $scope.opts = {
                drops: 'up',
                timePicker24Hour: true,
                singleDatePicker: true,
                timePicker: true,
                autoApply: true,
                // applyClass: 'btn-success',
                // cancelClass: 'btn-fail',
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    "separator": " - ",
                    "applyLabel": "确定",
                    "cancelLabel": "取消",
                    "fromLabel": "起始时间",
                    "toLabel": "结束时间'",
                    "customRangeLabel": "自定义",
                    "weekLabel": "W",
                    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "firstDay": 1
                },
                // ranges: {
                //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                //     'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                // }
            };

            //监听选择日期
            // var formFieldDate = false;
            // var timer = $interval(function() {
            //     if (formFieldDate) {
            //         $scope.$watch('dateRangePicker.date', function(newDate) {
            //             if (newDate) {
            //                 var date = _.where($scope.createOrder.form_field, { 'type': 'datetime' });
            //                 // date[0].value = newDate;
            //                 console.log('New date set: ', newDate,date);
            //             }
            //         }, false);
            //         $interval.cancel(timer);
            //     }
            // }, 500);


            // 工单类型
            workorderApi.ticketType().$promise.then(function(response) {
                if (response.data) {
                    $scope.show_submit = true;
                    $scope.forms = response.data;
                    $scope.selelctForm.form = $scope.forms[0];
                    $scope.$watch('selelctForm.form', function(new_value) {
                        if (new_value) {
                            //不同工单类型显示不同的表单
                            workorderApi.formField({ form_id: new_value.form_id }).$promise.then(function(res) {
                                $scope.createOrder.form_field = res.data.form_field;

                                //提交对象的流程id和工单类型id
                                $scope.createOrder.flow_id = Number(new_value.id);
                                $scope.createOrder.form_id = Number(new_value.form_id);

                                //服务类型
                                $scope.form_childs = new_value.form_child;
                                $scope.selelctForm.form_child = $scope.form_childs[0];
                                $scope.$watch('selelctForm.form_child', function(new_value) {
                                    if (new_value) {
                                        $scope.createOrder.form_child_id = Number(new_value.id);

                                        angular.forEach($scope.createOrder.form_field, function(field) {
                                            switch (field.type) {
                                                case 'form':
                                                    field.value = $scope.createOrder.form_id;
                                                    break;
                                                case 'form_child':
                                                    field.value = $scope.createOrder.form_child_id;
                                                    break;
                                                case 'textarea':
                                                    field.value = '';
                                                    break;
                                                case 'attachment':
                                                    if (!field.value)
                                                        field.value = [];
                                                    break;
                                                case 'depart':
                                                    field.value = res.data.department;
                                                    break;
                                                case 'datetime':
                                                    // formFieldDate = true;
                                                    field.value = moment().format('YYYY-MM-DD HH:mm');
                                                    break;
                                            }
                                        })
                                    }
                                });
                            });
                        }
                    }, false);
                } 
                // else {
                //     $scope.msg = response.errmsg;
                //     var dialog = ngDialog.open({
                //         template: './views/popup/alert.html',
                //         className: 'ngdialog-theme-default',
                //         showClose: false,
                //         scope: $scope
                //     });
                // }
            });

            var submitAll = function() {
                workorderApi.save($scope.createOrder).$promise.then(function() {
                    $scope.msg = '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.workorder.record');
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
                var attachment = _.where($scope.createOrder.form_field, { 'type': 'attachment' });
                attachment[0].value.push({ "image": response.image, "size": response.size });
            };


            uploader.onCompleteAll = function() {
                submitAll();
            };

            //提交所有信息
            $scope.submit = function() {
                if (uploader.getNotUploadedItems().length)
                // // console.log(uploader.getNotUploadedItems());
                    uploader.uploadAll();
                else
                    submitAll();
                // console.log($scope.createOrder);
            };
        }])
        //每个工单记录详情
        .controller('workorderDetailCtrl', ['$scope', '$timeout', '$state', '$stateParams', '$location', 'globalFunction', 'workorderApi', 'auth', 'ngDialog', 'Lightbox','ftUrl',
            function($scope, $timeout, $state, $stateParams, $location, globalFunction, workorderApi, auth, ngDialog, Lightbox,ftUrl) {
                $scope.approval_info = {
                    comment: '',
                    result: '',
                    record_id: '',
                    update_time: ''
                };
                $scope.upload_url = ftUrl + '/upload/attachment';
                $scope.is_submitting = false;
                workorderApi.recordDetail({ id: $stateParams.detail_id }).$promise.then(function(res) {
                    var approval_step;
                    $scope.record = res.data;
                    $scope.approval_info.record_id = $scope.record.id;
                    $scope.approval_info.update_time = $scope.record.update_time;
                    approval_step = _.groupBy($scope.record.ticketRecordSteps, 'index');
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
                                        "approver_user_name": _.pluck(step_data, 'approver_user_name').join('/'), //"/"表示或
                                        "status": 0 //未审批
                                    }
                                }
                            } else { //会签
                                var step_reject;
                                step = {
                                    "approver_user_name": _.pluck(step_data, 'approver_user_name').join(',') // ","表示且
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
                    workorderApi.approve($scope.approval_info).$promise.then(function() {
                        $scope.msg = '审批成功';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                        dialog.closePromise.then(function() {
                            $state.go('index.workorder.manage');
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.cancel = function() {
                    $scope.is_submitting = true;
                    workorderApi.cancel({ record_id: $scope.record.id, update_time: $scope.record.update_time }).$promise.then(function() {
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
                    workorderApi.recordDetail({ id: $scope.record.id }).$promise.then(function(res) {
                        if (res.data.update_time != $scope.record.update_time) {
                            $scope.msg = '申请已变更，请刷新页面';
                            ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                        } else {
                            $state.go('index.workorder.update', { record_id: $scope.record.id });
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
        //重新提交工单申请
        .controller('workorderUpdateCtrl', ['$scope', '$stateParams', '$timeout', '$state', '$interval', 'FileUploader', 'workorderApi', 'globalFunction', 'auth', 'apiUrl', 'ngDialog', function($scope, $stateParams, $timeout, $state, $interval, FileUploader, workorderApi, globalFunction, auth, apiUrl, ngDialog) {
            $scope.showPage = false;
            if (auth.checkToken($state.current.name)) {
                $scope.showPage = true;
            }

            workorderApi.recordDetail({ id: $stateParams.record_id }).$promise.then(function(res) {
                // console.log(res);
                // 提交所有数据的对象
                $scope.detailOrder = res.data;
                $scope.createOrder = {};
                $scope.createOrder.record_id = Number($stateParams.record_id);
                $scope.createOrder.update_time = res.data.update_time;
                $scope.createOrder.flow_id = res.data.flow_id;
                //工单类型，服务类型
                $scope.selelctForm = {};
                $scope.createOrder.form_field = res.data.ticketRecordFields;
                // angular.forEach($scope.createOrder.form_field, function(field) {
                //     switch (field.type) {
                //         case 'form':
                //             $scope.ticket_form_name = field.value;
                //             break;
                //         case 'form_child':
                //             $scope.sticket_form_child_name = field.value;
                //             break;
                //     }
                // })
                $scope.opts = {
                    drops: 'up',
                    timePicker24Hour: true,
                    singleDatePicker: true,
                    timePicker: true,
                    autoApply: true,
                    // applyClass: 'btn-success',
                    // cancelClass: 'btn-fail',
                    locale: {
                        format: 'YYYY-MM-DD HH:mm',
                        "separator": " - ",
                        "applyLabel": "确定",
                        "cancelLabel": "取消",
                        "fromLabel": "起始时间",
                        "toLabel": "结束时间'",
                        "customRangeLabel": "自定义",
                        "weekLabel": "W",
                        "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                        "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                        "firstDay": 1
                    },
                    // ranges: {
                    //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    //     'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                    // }
                };

                // 工单类型
                // workorderApi.ticketType().$promise.then(function(response) {
                //     if (response.data) {
                //         $scope.forms = response.data;

                //         angular.forEach($scope.forms, function(form) {
                //             if (form.name == $scope.ticket_form_name)
                //                 $scope.selelctForm.form = form;
                //         })

                //         //服务类型
                //         $scope.form_childs = $scope.selelctForm.form.form_child;
                //         angular.forEach($scope.form_childs, function(form_child) {
                //             if (form_child.name == $scope.sticket_form_child_name)
                //                 $scope.selelctForm.form_child = form_child;
                //         })

                //         //watch工单类型变化
                //         $scope.$watch('selelctForm.form', function(new_value, old_value) {
                //             if (new_value) {
                //                 //不同工单类型显示不同的表单
                //                 workorderApi.formField({ form_id: new_value.form_id }).$promise.then(function(res) {
                //                     //提交对象的流程id和工单类型id
                //                     $scope.createOrder.flow_id = new_value.id.toString();
                //                     $scope.createOrder.form_id = new_value.form_id.toString();
                //                     var flow_name = new_value.name;

                //                     //服务类型
                //                     if (new_value !== old_value) {
                //                         $scope.createOrder.form_field = res.data.form_field;
                //                         $scope.form_childs = new_value.form_child;
                //                         $scope.selelctForm.form_child = $scope.form_childs[0];
                //                     }


                //                     //watch服务类型变化
                //                     $scope.$watch('selelctForm.form_child', function(new_value, old_value) {
                //                         if (new_value) {
                //                             $scope.createOrder.form_child_id = new_value.id.toString();
                //                             var form_child_name = new_value.name;

                //                             if (new_value !== old_value) {
                //                                 angular.forEach($scope.createOrder.form_field, function(field) {
                //                                     switch (field.type) {
                //                                         case 'form':
                //                                             field.value = flow_name;
                //                                             break;
                //                                         case 'form_child':
                //                                             field.value = form_child_name;
                //                                             break;
                //                                         case 'attachment':
                //                                             if (!field.value)
                //                                                 field.value = '';
                //                                             break;
                //                                         case 'datetime':
                //                                             field.value = moment().format('YYYY-MM-DD HH:mm');
                //                                             break;
                //                                     }
                //                                 })
                //                             }
                //                         }
                //                     });
                //                 });
                //             }
                //         }, false);
                //     } else {
                //         $scope.msg = response.errmsg;
                //         var dialog = ngDialog.open({
                //             template: './views/popup/alert.html',
                //             className: 'ngdialog-theme-default',
                //             showClose: false,
                //             scope: $scope
                //         });
                //         dialog.closePromise.then(function() {
                //             $state.go('index.process');
                //         });
                //     }
                // });
            })

            var submitAll = function() {
                workorderApi.update($scope.createOrder).$promise.then(function() {
                    $scope.msg = $scope.detailOrder.form_name + '提交成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        $state.go('index.workorder.record');
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
                var attachment = _.where($scope.createOrder.form_field, { 'type': 'attachment' });
                attachment[0].value.push({ "image": response.image, "size": response.size });
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
                // console.log($scope.createOrder);
            };
        }])
}).call(this);
