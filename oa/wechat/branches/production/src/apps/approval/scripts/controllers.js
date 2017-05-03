(function() {
    'use strict';
    angular.module('approval.controllers', [])
        .controller('mainController', ['$scope', '$location', '$route', 'userManager', 'user', function($scope, $location, $route, userManager, user) {
            $scope.showHeader = false;
            $scope.user = user;
            $scope.currentPath = '';
            $scope.$on('$routeChangeSuccess', function(e) {
                $scope.currentPath = $location.path();
            })
            $scope.isCurrentPath = function(paths) {
                return _.contains(paths.split(','), $scope.currentPath);
            };
        }])
        .controller('flowListController', ['$scope', '$timeout', '$location', 'globalPagination', 'globalConfig', 'modalExtension', 'flowApi',
            function($scope, $timeout, $location, globalPagination, globalConfig, modalExtension, flowApi) {
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)

                flowApi.query().$promise.then(function(data) {
                    if (globalConfig.moduleCode != 'approval' && data.length == 1) {
                        $location.path('/record/create/' + data[0].id + (data[0].department_id ? '/' + data[0].department_id : ''));
                    }
                    if (data.length == 0)
                        modalExtension.alert("目前没有你适用的审批流程");
                    $scope.flows = data;
                    angular.forEach($scope.flows, function(flow) {
                        if (flow.department_id) {
                            flow.show_department = _.where(data, { form_id: flow.form_id }).length > 1;
                        } else {
                            flow.show_department = false;
                        }
                    })
                });
            }
        ])
        .controller('applyListController', ['$scope', '$timeout', '$location', '$route', 'globalPagination', 'recordApi',
            function($scope, $timeout, $location, $route, globalPagination, recordApi) {
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0)
                $scope.condition = {
                    "keyword": "",
                    "status": ""
                };
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = recordApi;
                $scope.pagination.query_method = 'applyList';
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition_copy, angular.extend($route.current.$$route.expand, { approvalRecordFields: {} })).$promise.then(function(data) {
                        $scope.records = _.union($scope.records, data);
                    })
                };
                $scope.search = function() {
                    $scope.condition_copy = angular.copy($scope.condition);
                    $scope.records = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = function() {
                    if (!$scope.pagination.isLast())
                        $scope.select($scope.pagination.page + 1)
                }
                $scope.search();
            }
        ])
        .controller('approvalListController', ['$scope', '$timeout', '$location', '$route', 'globalPagination', 'recordApi', function($scope, $timeout, $location, $route, globalPagination, recordApi) {
            $timeout(function() {
                $scope.$parent.showHeader = true;
            }, 0)
            $scope.approvalRecordTotal = 0;
            $scope.condition = {
                "keyword": "",
                "approval_status": "1"
            };
            //初始化列表數據
            $scope.pagination = globalPagination.create();
            $scope.pagination.resource = recordApi;
            $scope.pagination.query_method = 'approvalList';
            $scope.select = function(page) {
                $scope.pagination.select(page, $scope.condition_copy, angular.extend($route.current.$$route.expand, { approvalRecordFields: {} })).$promise.then(function(data) {
                    $scope.records = _.union($scope.records, data);
                    if ($scope.condition.approval_status == 1)
                        $scope.approvalRecordTotal = $scope.pagination.total_items;
                })
            };
            $scope.search = function() {
                $scope.condition_copy = angular.copy($scope.condition);
                $scope.records = [];
                $scope.select(1);
            }

            $scope.setStatus = function(status) {
                $scope.condition.approval_status = status;
                $scope.search();
            }

            $scope.bottomReached = function() {
                if (!$scope.pagination.isLast())
                    $scope.select($scope.pagination.page + 1)
            }

            $scope.search();
        }])
        .controller('recordCreateController', ['$scope', '$timeout', '$routeParams', '$location', 'globalFunction', 'modalExtension', 'weChat', 'flowApi', 'recordApi',
            function($scope, $timeout, $routeParams, $location, globalFunction, modalExtension, weChat, flowApi, recordApi) {
                $scope.is_submitting = false;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                flowApi.get(globalFunction.generateUrlParams({ id: $routeParams.flow_id }, { form: { approvalFormFields: '' }, approvalSpecialFields: {}, approvalSpecialData: {} })).$promise.then(function(data) {
                    $scope.flow = data;
                    if ($routeParams.department_id)
                        $scope.flow.department_id = $routeParams.department_id
                    angular.forEach($scope.flow.form.approvalFormFields, function(field) {
                        switch (field.type) {
                            case 'select':
                                field.data = field.data.split(',');
                                break;
                        }
                    })
                    $scope.special_fields = data.approvalSpecialFields;
                    $scope.special_data = data.approvalSpecialData;
                    $scope.fields = $scope.flow.form.approvalFormFields;
                });
                $scope.submit = function() {
                    $scope.is_submitting = true;
                    recordApi.save($scope.flow).$promise.then(function() {
                        modalExtension.alert($scope.flow.form.name + '提交成功').then(function() {
                            $location.path('/apply/list')
                        })
                    }, function() {
                        $scope.is_submitting = false;
                    })
                };
                $scope.selectImage = function(field) {
                    var upload = function(localIds, index) {
                        if (localIds.length <= index) return;
                        weChat.uploadImage({
                            localId: localIds[index],
                            isShowProgressTips: 1
                        }).then(function(uploadData) {
                            if (!_.isArray(field.value)) {
                                field.value = [];
                            }
                            field.value.push({ "path": localIds[index], "media_id": uploadData.serverId });
                            upload(localIds, index + 1);
                        })
                    }
                    weChat.chooseImage({
                        count: 9,
                        sizeType: ['original', 'compressed'],
                        sourceType: ['album', 'camera']
                    }).then(function(chooseData) {
                        upload(chooseData.localIds, 0);
                    }, function() {
                        alert('打开图片窗口失败')
                    })

                }
                $scope.showImage = function(field, image) {
                    weChat.previewImage({
                        current: image.path,
                        urls: _.pluck(field.value, 'path')
                    })
                }
                $scope.removeImage = function(field, index) {
                    field.value.splice(index, 1);
                }


            }
        ])
        .controller('recordUpdateController', ['$scope', '$timeout', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'modalExtension', 'weChat', 'flowApi', 'recordApi',
            function($scope, $timeout, $routeParams, $location, globalFunction, globalConfig, modalExtension, weChat, flowApi, recordApi) {
                $scope.is_submitting = false;
                $scope.upload_url = globalConfig.uploadUrl;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                recordApi.get(globalFunction.generateUrlParams({ id: $routeParams.id }, { approvalRecordFields: {}, approvalSpecialFields: {}, approvalSpecialData: {} })).$promise.then(function(data) {
                    $scope.record = data;
                    angular.forEach($scope.record.approvalRecordFields, function(field) {
                        switch (field.type) {
                            case 'select':
                                field.data = field.data.split(',');
                                break;
                        }
                    })
                    $scope.special_fields = data.approvalSpecialFields;
                    $scope.special_data = data.approvalSpecialData;
                    $scope.fields = $scope.record.approvalRecordFields;
                });
                $scope.submit = function() {
                    $scope.is_submitting = true;
                    recordApi.update($scope.record).$promise.then(function() {
                        modalExtension.alert($scope.record.form_name + '提交成功').then(function() {
                            $location.path('/apply/list')
                        })
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.selectImage = function(field) {
                    weChat.chooseImage({
                        count: 1,
                        sizeType: ['original', 'compressed'],
                        sourceType: ['album', 'camera']
                    }).then(function(data) {
                        weChat.uploadImage({
                            localId: data.localIds[0],
                            isShowProgressTips: 1
                        }).then(function(data) {
                            if (!_.isArray(field.value)) {
                                field.value = [];
                            }
                            field.value.push({ "path": data.localId, "media_id": data.serverId });
                        })
                    }, function() {
                        alert('打开图片窗口失败')
                    })
                }
                $scope.showImage = function(field, image) {
                    weChat.previewImage({
                        current: image.id ? $scope.upload_url + '/' + image.path : image.path,
                        urls: _.map(field.value, function(image) {
                            return image.id ? $scope.upload_url + '/' + image.path : image.path })
                    })
                }
                $scope.removeImage = function(field, index) {
                    field.value.splice(index, 1);
                }

            }
        ])
        .controller('recordDetailController', ['$scope', '$timeout', '$route', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'weChat', 'ModalService', 'modalExtension', 'flowApi', 'recordApi',
            function($scope, $timeout, $route, $routeParams, $location, globalFunction, globalConfig, weChat, ModalService, modalExtension, flowApi, recordApi) {
                $scope.approval_info = {
                    comment: '',
                    result: '',
                    record_id: '',
                    update_time: ''
                };
                $scope.upload_url = globalConfig.uploadUrl;
                $scope.is_submitting = false;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                recordApi.get(globalFunction.generateUrlParams({ id: $routeParams.id }, { approvalRecordComments: {}, approvalRecordFields: {}, approvalRecordSteps: {}, approvalSpecialFields: {} })).$promise.then(function(data) {
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
                        modalExtension.error('驳回时必须填写审批意见');
                        return;
                    }
                    $scope.is_submitting = true;
                    recordApi.approve($scope.approval_info).$promise.then(function() {
                        modalExtension.alert('审批成功').then(function() {
                            $location.path('/approval/list')
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.cancel = function() {
                    $scope.is_submitting = true;
                    recordApi.cancel({ id: $scope.record.id, update_time: $scope.record.update_time }).$promise.then(function() {
                        modalExtension.alert('撤销成功').then(function() {
                            $route.reload();
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.resubmit = function() {
                    var loading;
                    $scope.is_submitting = true;
                    recordApi.get({ id: $scope.record.id }).$promise.then(function(data) {
                        if (data.update_time != $scope.record.update_time) {
                            modalExtension.error('申请已变更，请刷新页面');
                        } else {
                            $location.path('/record/update/' + $scope.record.id);
                        }
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.showApprovalStep = function() {
                    ModalService.showModal({
                        templateUrl: "../approval/views/approval-flow.html",
                        controller: ['$scope', 'close', 'record', function($scope, close, record) {
                            var approval_step;
                            $scope.record = record;
                            approval_step = _.groupBy(record.approvalRecordSteps, 'index');
                            $scope.approval_step = [];
                            _.each(approval_step, function(step_data) {
                                var step;
                                if (step_data.length == 1) {
                                    step = step_data[0];
                                } else {
                                    if (step_data[0].type == 1) { //或签
                                        step = _.findWhere(step_data, { "status": 1 }); //已审批
                                        if (!step) {
                                            step = {
                                                "approver_name": _.pluck(step_data, 'approver_name').join('/'),
                                                "status": 0 //未审批
                                            }
                                        }
                                    } else {
                                        $scope.approval_step = $scope.approval_step.concat(_.where(step_data, { status: 1 }));
                                        if (_.where(step_data, { status: 0 }).length > 0) {
                                            step = {
                                                "approver_name": _.pluck(_.where(step_data, { status: 0 }), 'approver_name').join(','),
                                                "status": 0 //未审批
                                            }
                                        }
                                    }
                                }
                                if (step)
                                    $scope.approval_step.push(step)
                            })
                            $scope.close = function() {
                                close(null, 0);
                            }
                            $scope.$on('$routeChangeStart', function(e) {
                                $scope.close();
                            })
                        }],
                        inputs: {
                            "record": $scope.record
                        }
                    });
                }
                $scope.showImage = function(field, url) {
                    weChat.previewImage({
                        current: url,
                        urls: _.map(field.value, function(image) {
                            return $scope.upload_url + '/' + image.path })
                    })
                }
            }
        ])
        .controller('extraController', ['$scope', '$timeout', '$location', '$route', '$routeParams', 'globalPagination', 'recordApi',
            function($scope, $timeout, $location, $route, $routeParams, globalPagination, recordApi) {
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                $scope.routeDate = $routeParams.date;

                // $scope.approvalRecordTotal = 0;
                $scope.condition = {
                    "date": $routeParams.date
                };
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = recordApi;
                $scope.pagination.query_method = 'report';
                // console.log($route.current);
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition_copy, { recordDepartments: {} }).$promise.then(function(data) {
                        angular.forEach(data, function(item) {
                            item.end_time = new Date(item.end_time * 1000);
                        })
                        $scope.data = _.union($scope.data, data);
                        // if($scope.condition.approval_status == 1)
                        // $scope.approvalRecordTotal = $scope.pagination.total_items;
                    })
                };
                $scope.search = function() {
                    $scope.condition_copy = angular.copy($scope.condition);
                    $scope.data = [];
                    $scope.select(1);
                }

                $scope.bottomReached = function() {
                    if (!$scope.pagination.isLast())
                        $scope.select($scope.pagination.page + 1)
                }

                $scope.search();
                // recordApi.report({expand:'recordDepartments',date:$routeParams.date}).$promise.then(function(data){
                //     $scope.data = data;
                //     angular.forEach($scope.data,function(item){
                //         item.end_time =new Date(item.end_time *1000);
                //     })
                //     console.log($scope.data);
                // })
            }
        ])
}).call(this);
