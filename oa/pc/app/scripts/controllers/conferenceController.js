/**
 * 会议室模块控制器
 * @return {[type]} [description]
 */
(function() {
    'use strict';
    angular.module('conference.controller', ['mwl.calendar', 'ui.bootstrap', 'ui.select'])
        .controller('conferenceListCtrl', ['$scope', '$state', '$uibModal', 'globalFunction', 'conferenceApi', 'ngDialog', 'calendarConfig', function($scope, $state, $uibModal, globalFunction, conferenceApi, ngDialog, calendarConfig) {
            $scope.options = {
                showWeeks: false,
                formatDayTitle: "yyyy年MM月",
                formatMonthTitle: "yyyy年",
                formatMonth: "MM月",
                formatDayHeader: 'EEE',
                // bookRoomData: 
            };

            // 用户id
            if (localStorage['user']) {
                $scope.user_id = JSON.parse(localStorage['user']).id;
            }
            //返回用户上次浏览选择日或月形式
            if (angular.isDefined(sessionStorage['selectMonth'])) {
                $scope.selectMonth = sessionStorage['selectMonth'] === 'true' ? true : false;
            } else {
                $scope.selectMonth = true;
            }
            conferenceApi.query().$promise.then(function(data) {

                $scope.conferenceItems = data;
                //选择日期
                var now = new Date();
                if (sessionStorage['lastDateNote']) {
                    $scope.currentDateNote = new Date(sessionStorage['lastDateNote']);
                } else {
                    $scope.currentDateNote = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                }
                if (sessionStorage['lastDate']) {
                    $scope.currentDate = new Date(sessionStorage['lastDate']);
                } else {
                    $scope.currentDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                }


                //选择会议室
                $scope.condition = {};
                if (sessionStorage['selectRoom']) {
                    $scope.condition.selectRoom = JSON.parse(sessionStorage['selectRoom']);
                    $scope.room_id = $scope.condition.selectRoom.id;
                } else {
                    $scope.room_id = '';
                }





                // 按天日历
                $scope.$watch('currentDate', function(new_value, old_value) {
                    if (new_value) {
                        // console.log(new_value);
                        // $scope.selectDate = Date.parse(new_value) / 1000; //选择日期的时间戳
                        var newDate = new Date(new_value);
                        var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                        var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                        $scope.book_date = newDate.getFullYear() + '-' + selectMonth + '-' + selectDay;
                        sessionStorage.setItem("lastDate", $scope.book_date);
                        //获取当天记录
                        conferenceApi.bookTimeList({ room_id: $scope.room_id, date: $scope.book_date }).$promise.then(function(data) {
                            var result = [];
                            for (var x = 0; x < (data.data.length / 2); x++) {
                                var start = x * 2;
                                var end = start + 2;
                                result.push(data.data.slice(start, end));
                            }
                            // console.log(result);
                            $scope.bookRecords = result;
                        })
                    }
                });


                // 按月日历
                $scope.$watch('currentDateNote', function(new_value, old_value) {
                    if (new_value) {
                        var newDate = new Date(new_value);
                        var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                        var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                        $scope.book_date_note = newDate.getFullYear() + '-' + selectMonth;
                        sessionStorage.setItem("lastDateNote", $scope.book_date_note);
                        // console.log($scope.book_date_note);
                        //获取全月份会议记录
                        conferenceApi.bookMonthList({ room_id: $scope.room_id, month: $scope.book_date_note }).$promise.then(function(data) {
                            $scope.options.bookRoomData = data;
                            angular.forEach(data.data, function(dayItem) {
                                angular.forEach(dayItem.data, function(item) {
                                    if ($scope.user_id == item.book_user_id) {
                                        item.owner = true;
                                    }
                                })
                            });
                            // console.log(data);
                        });
                    }
                });


                // 选择会议室
                $scope.$watch('condition.selectRoom', function(new_value, old_value) {
                    if (new_value) {
                        sessionStorage.setItem("selectRoom", JSON.stringify(new_value));
                        $scope.room_id = new_value.id;

                        //获取当天记录
                        conferenceApi.bookTimeList({ room_id: $scope.room_id, date: $scope.book_date }).$promise.then(function(data) {
                            var result = [];
                            for (var x = 0; x < (data.data.length / 2); x++) {
                                var start = x * 2;
                                var end = start + 2;
                                result.push(data.data.slice(start, end));
                            }
                            // console.log(result);
                            $scope.bookRecords = result;
                        });

                        // 获取全月份会议记录
                        conferenceApi.bookMonthList({ room_id: $scope.room_id, month: $scope.book_date_note }).$promise.then(function(data) {
                            $scope.options.bookRoomData = data;
                            angular.forEach(data.data, function(dayItem) {
                                angular.forEach(dayItem.data, function(item) {
                                    if ($scope.user_id == item.book_user_id) {
                                        item.owner = true;
                                    }
                                })
                            });
                            // console.log(data);
                        });
                    }
                });
            });

            // 选择天或月

            $scope.changeType = function(value) {
                $scope.selectMonth = value;
                sessionStorage.setItem("selectMonth", value);
            };



            // var vm = this;

            // //These variables MUST be set as a minimum for the calendar to work
            // vm.calendarView = 'month';
            // vm.viewDate = new Date();
            // var actions = [{
            //     label: '<i class=\'glyphicon glyphicon-pencil\'></i>',
            //     onClick: function(args) {
            //         alert.show('Edited', args.calendarEvent);
            //     }
            // }, {
            //     label: '<i class=\'glyphicon glyphicon-remove\'></i>',
            //     onClick: function(args) {
            //         alert.show('Deleted', args.calendarEvent);
            //     }
            // }];
            // vm.events = [{
            //     title: 'An event',
            //     color: calendarConfig.colorTypes.warning,
            //     startsAt: moment().startOf('week').subtract(2, 'days').add(8, 'hours').toDate(),
            //     endsAt: moment().startOf('week').add(1, 'week').add(9, 'hours').toDate(),
            //     draggable: true,
            //     resizable: true,
            //     actions: actions
            // }, {
            //     title: '<i class="glyphicon glyphicon-asterisk"></i> <span class="text-primary">Another event</span>, with a <i>html</i> title',
            //     color: calendarConfig.colorTypes.info,
            //     startsAt: moment().subtract(1, 'day').toDate(),
            //     endsAt: moment().add(5, 'days').toDate(),
            //     draggable: true,
            //     resizable: true,
            //     actions: actions
            // }, {
            //     title: 'This is a really long event title that occurs on every year',
            //     color: calendarConfig.colorTypes.important,
            //     startsAt: moment().startOf('day').add(7, 'hours').toDate(),
            //     endsAt: moment().startOf('day').add(19, 'hours').toDate(),
            //     recursOn: 'year',
            //     draggable: true,
            //     resizable: true,
            //     actions: actions
            // }];

            // vm.cellIsOpen = true;

            // vm.addEvent = function() {
            //     vm.events.push({
            //         title: 'New event',
            //         startsAt: moment().startOf('day').toDate(),
            //         endsAt: moment().endOf('day').toDate(),
            //         color: calendarConfig.colorTypes.important,
            //         draggable: true,
            //         resizable: true
            //     });
            // };

            // vm.eventClicked = function(event) {
            //     alert.show('Clicked', event);
            // };

            // vm.eventEdited = function(event) {
            //     alert.show('Edited', event);
            // };

            // vm.eventDeleted = function(event) {
            //     alert.show('Deleted', event);
            // };

            // vm.eventTimesChanged = function(event) {
            //     alert.show('Dropped or resized', event);
            // };

            // vm.toggle = function($event, field, event) {
            //     $event.preventDefault();
            //     $event.stopPropagation();
            //     event[field] = !event[field];
            // };

            // vm.timespanClicked = function(date, cell) {

            //     if (vm.calendarView === 'month') {
            //         if ((vm.cellIsOpen && moment(date).startOf('day').isSame(moment(vm.viewDate).startOf('day'))) || cell.events.length === 0 || !cell.inMonth) {
            //             vm.cellIsOpen = false;
            //         } else {
            //             vm.cellIsOpen = true;
            //             vm.viewDate = date;
            //         }
            //     } else if (vm.calendarView === 'year') {
            //         if ((vm.cellIsOpen && moment(date).startOf('month').isSame(moment(vm.viewDate).startOf('month'))) || cell.events.length === 0) {
            //             vm.cellIsOpen = false;
            //         } else {
            //             vm.cellIsOpen = true;
            //             vm.viewDate = date;
            //         }
            //     }

            // };
        }])
        .controller('conferenceApplyCtrl', ['$scope', '$state', '$uibModal', 'globalFunction', 'conferenceApi', 'ngDialog', function($scope, $state, $uibModal, globalFunction, conferenceApi, ngDialog) {
            $scope.options = {
                showWeeks: false,
                formatDayTitle: "yyyy年MM月",
                formatMonthTitle: "yyyy年",
                formatMonth: "MM月",
                formatDayHeader: 'EEE',
            };
            $scope.bookCreate = {};
            $scope.bookCreate.book_clock = null;
            $scope.book_clocks = [5, 10, 15, 30];
            //选择日期
            var now = new Date();
            $scope.currentDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            // 会议室选择时间段列表
            $scope.bookCreate.room_id = '';
            conferenceApi.query().$promise.then(function(data) {
                $scope.conferenceItems = data;
                $scope.$watch('currentDate', function(new_value, old_value) {
                    if (new_value) {
                        var newDate = new Date(new_value);
                        $scope.bookCreate.book_date = moment(newDate).format('YYYY-MM-DD');
                        $scope.bookCreate.times = [];
                        $scope.selectTimeIndex = [];
                        $scope.bookCreate.room_id = '';
                        $scope.showStartTime = '';
                        $scope.showEndTime = '';
                        //获取可选时间段
                        $scope.accessTimes = [];
                        // console.log($scope.conferenceItems);
                        angular.forEach($scope.conferenceItems, function(item) {
                            conferenceApi.accessTime({ room_id: item.id, date: $scope.bookCreate.book_date }).$promise.then(function(data) {
                                $scope.accessTimes.push({ room_id: item.id, data: data, room_name: item.name, row: item['sort'] });
                                // $scope.accessTimes = _.sortBy($scope.accessTimes, 'room_id');
                            })
                        });
                    }
                })
            })

            //选择时间段
            $scope.bookCreate.times = [];
            $scope.bookCreate.room_id = '';
            $scope.selectTimeIndex = [];
            var updateSelectedTimes = function(action, point, index) {
                    if (action == 'add' && $scope.bookCreate.times.indexOf(point) == -1) {
                        $scope.bookCreate.times.push(point);
                        $scope.selectTimeIndex.push(index);
                        $scope.maxAndMinTime();
                    }
                    if (action == 'remove' && $scope.bookCreate.times.indexOf(point) != -1) {
                        var idx = $scope.bookCreate.times.indexOf(point);
                        var idIndex = $scope.selectTimeIndex.indexOf(index);
                        $scope.bookCreate.times.splice(idx, 1);
                        $scope.selectTimeIndex.splice(idIndex, 1);
                        if (!$scope.bookCreate.times.length)
                            $scope.bookCreate.room_id = '';
                        $scope.maxAndMinTime();
                    }
                }
                //判断是否已选中
            $scope.isSelectedTime = function(item, room_id) {
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    return ($scope.bookCreate.times.indexOf(point) >= 0 && room_id == $scope.bookCreate.room_id)
                }
                //点击时间点方法
            $scope.checkboxClick = function(room_id, item, index, e) {
                e.stopPropagation();
                if (item.is_use == 1) {
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    var checkbox = e.target;
                    var action = ($scope.bookCreate.times.indexOf(point) < 0 ? 'add' : 'remove');
                    if (!$scope.bookCreate.room_id) {
                        $scope.bookCreate.room_id = room_id;
                        updateSelectedTimes(action, point, index);
                    } else if ($scope.bookCreate.room_id != room_id) {
                        e.target.checked = false;
                        $scope.msg = '请选择同一个会议室的时间段';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                    } else if (!$scope.bookCreate.times.length) {
                        updateSelectedTimes(action, point, index);
                    } else if ($scope.bookCreate.times.length) {
                        var room = _.where($scope.accessTimes, { 'room_id': room_id });
                        room = room[0].data;
                        var bookMinIndex = Math.min.apply(null, $scope.selectTimeIndex);
                        var bookMaxIndex = Math.max.apply(null, $scope.selectTimeIndex);
                        if ($scope.selectTimeIndex.indexOf(index) >= 0) {
                            if (bookMaxIndex == index || bookMinIndex == index) {
                                var item = _.where(room, { 'id': index });
                                var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                updateSelectedTimes('remove', new_point, index);
                            } else if (index - bookMinIndex == bookMaxIndex - index) {
                                for (var i = index; i <= bookMaxIndex; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            } else if (bookMaxIndex - index > index - bookMinIndex) {
                                for (var i = bookMinIndex; i <= index; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            } else {
                                for (var i = index; i <= bookMaxIndex; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            }
                        } else {
                            if (index > bookMaxIndex) {
                                // bookMinIndex----index
                                var flag = true;
                                for (var i = bookMaxIndex + 1; i <= index; i++) {
                                    var item = _.where(room, { 'id': i });
                                    if (item[0].is_use != 1) {
                                        flag = false;
                                        break;
                                    }
                                }
                                if (flag) {
                                    for (var i = bookMaxIndex + 1; i <= index; i++) {
                                        var item = _.where(room, { 'id': i });
                                        var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                        updateSelectedTimes('add', new_point, i);
                                    }
                                } else {
                                    checkbox.checked = false;
                                    $scope.msg = '选择的时间段内有已预订';
                                    var dialog = ngDialog.open({
                                        template: './views/popup/alert.html',
                                        className: 'ngdialog-theme-default',
                                        showClose: false,
                                        scope: $scope
                                    });
                                }
                            } else {
                                // index-----bookMaxIndex
                                var flag = true;
                                for (var i = index; i <= bookMinIndex - 1; i++) {
                                    var item = _.where(room, { 'id': i });
                                    if (item[0].is_use != 1) {
                                        flag = false;
                                        break;
                                    }
                                }
                                if (flag) {
                                    for (var i = index; i <= bookMinIndex - 1; i++) {
                                        var item = _.where(room, { 'id': i });
                                        var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                        updateSelectedTimes('add', new_point, i);
                                    }
                                } else {
                                    checkbox.checked = false;
                                    $scope.msg = '选择的时间段内有已预订';
                                    var dialog = ngDialog.open({
                                        template: './views/popup/alert.html',
                                        className: 'ngdialog-theme-default',
                                        showClose: false,
                                        scope: $scope
                                    });
                                }
                            }
                        }
                    }
                }
                console.log($scope.bookCreate.times, $scope.selectTimeIndex);
            }

            //显示选择时间段
            $scope.maxAndMinTime = function() {
                var timesArray = [];
                angular.forEach($scope.bookCreate.times, function(item, index) {
                    timesArray[index] = Date.parse(new Date(item));
                })

                if (!timesArray.length) {
                    $scope.showStartTime = '';
                } else if (timesArray.length == 1) {
                    $scope.showStartTime = moment(new Date(parseInt(timesArray[0]))).format('HH:mm');
                    $scope.showEndTime = moment(new Date(parseInt(timesArray[0]) + 29 * 60 * 1000)).format('HH:mm');
                } else {
                    var bookStartTime = Math.min.apply(null, timesArray);
                    var bookEndTime = Math.max.apply(null, timesArray);

                    $scope.showStartTime = moment(new Date(bookStartTime)).format('HH:mm');
                    $scope.showEndTime = moment(new Date(parseInt(bookEndTime) + 29 * 60 * 1000)).format('HH:mm');
                }
            }


            // 会议人员部门列表
            var buildTree = function(node, data) {
                node.items = _.where(data, { 'parent_id': node.id });
                if (node.items) {
                    _.each(node.items, function(item) {
                        item.indent = node.indent + 1;
                        buildTree(item, data);
                    })
                } else {
                    node.items = [];
                }
            }
            $scope.list = [];
            conferenceApi.departmentList().$promise.then(function(data) {
                // console.log(data);
                $scope.departments = data.data;

                var topNode = _.find(data.data, function(node) {
                    return node.parent_id == 0
                })
                topNode.indent = 0;
                $scope.list.push(topNode);
                buildTree(topNode, data.data);
                // console.log($scope.list);
            })


            //参加会议人员
            $scope.bookCreate.users = [];
            $scope.selected = [];
            $scope.allCheckedUsers = [];

            $scope.open = function() {
                var modalInstance = $uibModal.open({
                    templateUrl: 'myModalContent.html',
                    controller: 'ModalInstanceCtrl',
                    resolve: {
                        items: function() {
                            return $scope.items;
                        },
                        //部门列表
                        lists: function() {
                            return $scope.list;
                        },
                        //选择了的人员
                        allCheckedUsers: function() {
                            return $scope.allCheckedUsers;
                        },
                        //选中人员的ids
                        selectUsers: function() {
                            return $scope.selected;
                        },
                        // 提交表单人员ids字段
                        bookCreateUsers: function() {
                            return $scope.bookCreate.users;
                        }
                    }
                });
                modalInstance.opened.then(function() { //模态窗口打开之后执行的函数  
                    // console.log('modal is opened');
                });
                modalInstance.result.then(function(result) {
                    // console.log(result);
                }, function(reason) {
                    // console.log(reason); //点击空白区域，总会输出backdrop click，点击取消，则会暑促cancel  
                    // $log.info('Modal dismissed at: ' + new Date());
                });
            };

            //删除选中的参议人员
            $scope.removeUser = function(user) {
                var idx = $scope.bookCreate.users.indexOf(user.id);
                $scope.bookCreate.users.splice(idx, 1);
                $scope.allCheckedUsers.splice(idx, 1);
                var idx = $scope.selected.indexOf(user.id);
                $scope.selected.splice(idx, 1);
            }

            //提交
            $scope.submit = function() {
                console.log($scope.allCheckedUsers, $scope.selected, $scope.bookCreate);

                if (!$scope.bookCreate.times.length) {
                    $scope.msg = '请选择时间段';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                } else
                if (!$scope.bookCreate.book_name) {
                    $scope.msg = '会议主题不能为空';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                } else {
                    conferenceApi.bookCreate($scope.bookCreate).$promise.then(function(data) {
                        if (data.errcode == 0) {
                            $scope.book_id = data.data.book_id;


                            $scope.msg = '预定成功';
                            var dialog = ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                            dialog.closePromise.then(function() {
                                // console.log($scope.book_id);
                                $state.go('index.conference.detail', { detail_id: $scope.book_id });
                            });
                        }
                        if (data.errcode == 1)
                        // modalExtension.alert(data.errmsg);
                        {
                            $scope.msg = data.errmsg;
                            var dialog = ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                        }
                    })
                }
            };
        }])
        .controller('conferenceUpdateCtrl', ['$scope', '$state', '$stateParams', '$uibModal', 'globalFunction', 'conferenceApi', 'ngDialog', function($scope, $state, $stateParams, $uibModal, globalFunction, conferenceApi, ngDialog) {

            $scope.options = {
                showWeeks: false,
                formatDayTitle: "yyyy年MM月",
                formatMonthTitle: "yyyy年",
                formatMonth: "MM月",
                formatDayHeader: 'EEE',
            };

            $scope.bookCreate = {};

            $scope.book_clocks = [5, 10, 15, 30];
            $scope.bookCreate.id = $stateParams.update_id;

            conferenceApi.bookDetail({ id: $stateParams.update_id }).$promise.then(function(data) {
                $scope.bookDetailData = data;
                $scope.bookCreate.book_clock = $scope.bookDetailData.book_clock;
                $scope.book_date_format = Date.parse(new Date(data.book_date)) / 1000;
                $scope.bookCreate.book_name = $scope.bookDetailData.book_name;
                $scope.bookCreate.book_content = $scope.bookDetailData.book_content;


                //选择日期
                $scope.currentDate = new Date(data.book_date);

                // 会议室选择列表
                $scope.condition = {};
                conferenceApi.query().$promise.then(function(data) {
                    $scope.conferenceItems = data;
                    angular.forEach(data, function(item) {
                        if (item.id == $scope.bookDetailData.room_id)
                            $scope.condition.selectRoom = item;
                    });
                    $scope.bookCreate.room_id = $scope.condition.selectRoom.id;

                    $scope.$watch('currentDate', function(new_value, old_value) {
                        if (new_value) {
                            // console.log(new_value);
                            var newDate = new Date(new_value);
                            $scope.bookCreate.book_date = moment(newDate).format('YYYY-MM-DD');
                            $scope.selectDate = Date.parse($scope.bookCreate.book_date) / 1000; //选择日期的时间戳
                            // console.log($scope.bookCreate.book_date,$scope.book_date_format,$scope.selectDate,new_value);
                            if ($scope.book_date_format == $scope.selectDate) {
                                $scope.bookCreate.times = $scope.bookDetailData.room_book_time;
                                $scope.showStartTime = moment(new Date($scope.bookDetailData.book_start)).format('HH:mm');
                                $scope.showEndTime = moment(new Date($scope.bookDetailData.book_end)).format('HH:mm');

                                $scope.selectTimeIndex = []; //判断选中的时间段最大最小值
                                var bookPointArray = []; //放$scope.bookCreate.times去掉日期的时间点
                                _.each($scope.bookCreate.times, function(value, index) {
                                    bookPointArray.push(value.split(' ')[1]);
                                });
                                conferenceApi.accessTime({ room_id: $scope.bookCreate.room_id, date: $scope.bookCreate.book_date }).$promise.then(function(data) {
                                    $scope.accessTimeOne = data; //为了获取对应时间点的id，后面selectTimeIndex用到
                                }).then(function() {
                                    _.each($scope.accessTimeOne, function(value, index) {
                                        if (bookPointArray.indexOf(value.point) >= 0) {
                                            $scope.selectTimeIndex.push(value.id);
                                        }
                                    })
                                });
                            } else {
                                $scope.bookCreate.times = [];
                                $scope.selectTimeIndex = [];
                                $scope.showStartTime = '';
                                $scope.showEndTime = '';
                            }
                            //获取可选时间段
                            $scope.accessTimes = [];
                            angular.forEach($scope.conferenceItems, function(item) {
                                conferenceApi.accessTime({ room_id: item.id, date: $scope.bookCreate.book_date, book_id: $stateParams.update_id }).$promise.then(function(data) {
                                    $scope.accessTimes.push({ room_id: item.id, data: data, room_name: item.name, row: item['sort'] });
                                })
                            });
                        }
                    })
                })

                //参加会议人员
                $scope.bookCreate.users = [];
                $scope.selected = [];
                $scope.allCheckedUsers = [];
                angular.forEach($scope.bookDetailData.room_book_user, function(item) {
                    $scope.bookCreate.users.push(item.book_user_id);
                    $scope.selected.push(item.book_user_id);
                    $scope.allCheckedUsers.push(item.user);
                })
            });



            //选择时间段

            var updateSelectedTimes = function(action, point, index) {
                    if (action == 'add' && $scope.bookCreate.times.indexOf(point) == -1) {
                        $scope.bookCreate.times.push(point);
                        $scope.selectTimeIndex.push(index);
                        $scope.maxAndMinTime();
                    }
                    if (action == 'remove' && $scope.bookCreate.times.indexOf(point) != -1) {
                        var idx = $scope.bookCreate.times.indexOf(point);
                        var idIndex = $scope.selectTimeIndex.indexOf(index);
                        $scope.bookCreate.times.splice(idx, 1);
                        $scope.selectTimeIndex.splice(idIndex, 1);
                        if (!$scope.bookCreate.times.length)
                            $scope.bookCreate.room_id = '';
                        $scope.maxAndMinTime();
                    }
                }
                //判断是否已选中
            $scope.isSelectedTime = function(item, room_id) {
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    return ($scope.bookCreate.times.indexOf(point) >= 0 && room_id == $scope.bookCreate.room_id)
                }
                //点击时间点方法
            $scope.checkboxClick = function(room_id, item, index, e) {
                e.stopPropagation();
                if (item.is_use == 1) {
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    var checkbox = e.target;
                    var action = ($scope.bookCreate.times.indexOf(point) < 0 ? 'add' : 'remove');
                    if (!$scope.bookCreate.room_id) {
                        $scope.bookCreate.room_id = room_id;
                        updateSelectedTimes(action, point, index);
                    } else if ($scope.bookCreate.room_id != room_id) {
                        e.target.checked = false;
                        $scope.msg = '请选择同一个会议室的时间段';
                        var dialog = ngDialog.open({
                            template: './views/popup/alert.html',
                            className: 'ngdialog-theme-default',
                            showClose: false,
                            scope: $scope
                        });
                    } else if (!$scope.bookCreate.times.length) {
                        updateSelectedTimes(action, point, index);
                    } else if ($scope.bookCreate.times.length) {
                        var room = _.where($scope.accessTimes, { 'room_id': room_id });
                        room = room[0].data;
                        var bookMinIndex = Math.min.apply(null, $scope.selectTimeIndex);
                        var bookMaxIndex = Math.max.apply(null, $scope.selectTimeIndex);
                        if ($scope.selectTimeIndex.indexOf(index) >= 0) {
                            if (bookMaxIndex == index || bookMinIndex == index) {
                                var item = _.where(room, { 'id': index });
                                var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                updateSelectedTimes('remove', new_point, index);
                            } else if (index - bookMinIndex == bookMaxIndex - index) {
                                for (var i = index; i <= bookMaxIndex; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            } else if (bookMaxIndex - index > index - bookMinIndex) {
                                for (var i = bookMinIndex; i <= index; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            } else {
                                for (var i = index; i <= bookMaxIndex; i++) {
                                    var item = _.where(room, { 'id': i });
                                    var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                    updateSelectedTimes('remove', new_point, i);
                                }
                            }
                        } else {
                            if (index > bookMaxIndex) {
                                // bookMinIndex----index
                                var flag = true;
                                for (var i = bookMaxIndex + 1; i <= index; i++) {
                                    var item = _.where(room, { 'id': i });
                                    if (item[0].is_use != 1) {
                                        flag = false;
                                        break;
                                    }
                                }
                                if (flag) {
                                    for (var i = bookMaxIndex + 1; i <= index; i++) {
                                        var item = _.where(room, { 'id': i });
                                        var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                        updateSelectedTimes('add', new_point, i);
                                    }
                                } else {
                                    checkbox.checked = false;
                                    $scope.msg = '选择的时间段内有已预订';
                                    var dialog = ngDialog.open({
                                        template: './views/popup/alert.html',
                                        className: 'ngdialog-theme-default',
                                        showClose: false,
                                        scope: $scope
                                    });
                                }
                            } else {
                                // index-----bookMaxIndex
                                var flag = true;
                                for (var i = index; i <= bookMinIndex - 1; i++) {
                                    var item = _.where(room, { 'id': i });
                                    if (item[0].is_use != 1) {
                                        flag = false;
                                        break;
                                    }
                                }
                                if (flag) {
                                    for (var i = index; i <= bookMinIndex - 1; i++) {
                                        var item = _.where(room, { 'id': i });
                                        var new_point = $scope.bookCreate.book_date + ' ' + item[0].point + ':00';
                                        updateSelectedTimes('add', new_point, i);
                                    }
                                } else {
                                    checkbox.checked = false;
                                    $scope.msg = '选择的时间段内有已预订';
                                    var dialog = ngDialog.open({
                                        template: './views/popup/alert.html',
                                        className: 'ngdialog-theme-default',
                                        showClose: false,
                                        scope: $scope
                                    });
                                }
                            }
                        }
                    }
                }
                console.log($scope.bookCreate.times, $scope.selectTimeIndex,$scope.bookCreate.room_id);
            }

            //显示选择时间段
            $scope.maxAndMinTime = function() {
                var timesArray = [];
                angular.forEach($scope.bookCreate.times, function(item, index) {
                    timesArray[index] = Date.parse(new Date(item));
                })

                if (!timesArray.length) {
                    $scope.showStartTime = '';
                } else if (timesArray.length == 1) {
                    $scope.showStartTime = moment(new Date(parseInt(timesArray[0]))).format('HH:mm');
                    $scope.showEndTime = moment(new Date(parseInt(timesArray[0]) + 29 * 60 * 1000)).format('HH:mm');
                } else {
                    var bookStartTime = Math.min.apply(null, timesArray);
                    var bookEndTime = Math.max.apply(null, timesArray);

                    $scope.showStartTime = moment(new Date(bookStartTime)).format('HH:mm');
                    $scope.showEndTime = moment(new Date(parseInt(bookEndTime) + 29 * 60 * 1000)).format('HH:mm');
                }
            }


            // 会议人员部门列表
            var buildTree = function(node, data) {
                node.items = _.where(data, { 'parent_id': node.id });
                if (node.items) {
                    _.each(node.items, function(item) {
                        item.indent = node.indent + 1;
                        buildTree(item, data);
                    })
                } else {
                    node.items = [];
                }
            }
            $scope.list = [];
            conferenceApi.departmentList().$promise.then(function(data) {
                // console.log(data);
                $scope.departments = data.data;

                var topNode = _.find(data.data, function(node) {
                    return node.parent_id == 0
                })
                topNode.indent = 0;
                $scope.list.push(topNode);
                buildTree(topNode, data.data);
            })



            //参加会议人员
            $scope.open = function() {
                var modalInstance = $uibModal.open({
                    templateUrl: 'myModalContent.html',
                    controller: 'ModalInstanceCtrl',
                    resolve: {
                        items: function() {
                            return $scope.items;
                        },
                        lists: function() {
                            return $scope.list;
                        },
                        allCheckedUsers: function() {
                            return $scope.allCheckedUsers;
                        },
                        selectUsers: function() {
                            return $scope.selected;
                        },
                        bookCreateUsers: function() {
                            return $scope.bookCreate.users;
                        }
                    }
                });
                modalInstance.opened.then(function() { //模态窗口打开之后执行的函数  
                    // console.log('modal is opened');
                });
                modalInstance.result.then(function(result) {
                    // console.log(result);
                }, function(reason) {
                    // console.log(reason); //点击空白区域，总会输出backdrop click，点击取消，则会暑促cancel  
                    // $log.info('Modal dismissed at: ' + new Date());
                });
            };

            //删除选中的参议人员
            $scope.removeUser = function(user) {
                var idx = $scope.bookCreate.users.indexOf(user.id);
                $scope.bookCreate.users.splice(idx, 1);
                $scope.allCheckedUsers.splice(idx, 1);
                var idx = $scope.selected.indexOf(user.id);
                $scope.selected.splice(idx, 1);
            }

            //提交
            $scope.submit = function() {
                // console.log($scope.bookCreate);
                if (!$scope.bookCreate.book_name) {
                    $scope.msg = '会议主题不能为空';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                } else {
                    conferenceApi.bookUpdate($scope.bookCreate).$promise.then(function(data) {
                        if (data.errcode == 0) {
                            $scope.msg = '修改成功';
                            var dialog = ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                            dialog.closePromise.then(function() {
                                // console.log($scope.book_id);
                                $state.go('index.conference.detail', { detail_id: $stateParams.update_id });
                            });
                        }
                        if (data.errcode == 1)
                        // modalExtension.alert(data.errmsg);
                        {
                            $scope.msg = data.errmsg;
                            var dialog = ngDialog.open({
                                template: './views/popup/alert.html',
                                className: 'ngdialog-theme-default',
                                showClose: false,
                                scope: $scope
                            });
                        }
                    })
                }
            };
        }])
        .controller('ModalInstanceCtrl', ['$scope', '$uibModalInstance', 'items', 'lists', 'allCheckedUsers', 'selectUsers', 'bookCreateUsers', 'conferenceApi', function($scope, $uibModalInstance, items, lists, allCheckedUsers, selectUsers, bookCreateUsers, conferenceApi) {
            $scope.list = lists;

            //点击每个人员
            // $scope.bookCreate.users = [];
            // $scope.sureAllCheckedUsers = [];
            // var selected = [];
            // $scope.allCheckedUsers = [];

            // 搜索人员
            $scope.searchUserFn = function() {
                conferenceApi.searchUser({ name: $scope.inputName }).$promise.then(function(data) {
                    // console.log(data);
                    $scope.departmentUsers = data.data;
                })
            }

            $scope.selectUserDarp = function(x, item, e) {
                e.stopPropagation();
                // $scope.currentDepartId = x;
                $scope.departmentUsers = [];
                conferenceApi.allusers({ id: x }).$promise.then(function(data) {
                    $scope.departmentUsers = data.data;
                })
            };

            var updateBookCreateUsers = function(action, id, item) {
                if (action == 'add' && bookCreateUsers.indexOf(id) == -1) {
                    bookCreateUsers.push(id);
                    allCheckedUsers.push(item);
                }
                if (action == 'remove' && bookCreateUsers.indexOf(id) != -1) {
                    var idx = bookCreateUsers.indexOf(id);
                    bookCreateUsers.splice(idx, 1);
                    allCheckedUsers.splice(idx, 1);
                }
            }

            var updateSelected = function(action, id) {
                if (action == 'add' && selectUsers.indexOf(id) == -1) {
                    selectUsers.push(id);
                }
                if (action == 'remove' && selectUsers.indexOf(id) != -1) {
                    var idx = selectUsers.indexOf(id);
                    selectUsers.splice(idx, 1);
                }
            }

            $scope.isSelected = function(id) {
                return selectUsers.indexOf(id) >= 0;
            }

            $scope.isSelectedAll = function(departmentUsers) {
                var flag = true;
                _.each(departmentUsers, function(value, key, list) {
                    if (selectUsers.indexOf(value.id) < 0) {
                        flag = false;
                    }
                })
                return flag;
            }
            $scope.checkedUser = function(e, item) {
                e.stopPropagation();
                // console.log(e.target.checked);
                var checkbox = e.target;
                var action = (checkbox.checked ? 'add' : 'remove');
                updateBookCreateUsers(action, item.id, item);
            }

            var funcArray = [];
            $scope.pushfuncArray = function(e, item) {

                    funcArray.push([e, item]);
                    var checkbox = e.target;
                    var action = (checkbox.checked ? 'add' : 'remove');
                    updateSelected(action, item.id);
                    // console.log($scope.bookCreate.users,selected);
                }
                //全选
            $scope.userCheckAll = function(e, departmentUsers) {
                var checkbox = e.target;
                angular.forEach(departmentUsers, function(item) {
                    $scope.pushfuncArray(e, item)
                })

            }

            //确定选择人员
            $scope.sureUsers = function() {
                angular.forEach(funcArray, function(item) {
                        $scope.checkedUser(item[0], item[1]);
                        $uibModalInstance.close();
                    })
                    // console.log($scope);

            }

            //取消选择人员
            $scope.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
            // $scope.ok = function() {
            //     $uibModalInstance.close($scope.selected);
            // };
        }])
        .controller('conferenceleftbarCtrl', ['$scope', '$rootScope', 'personalCenterApi', function($scope, $rootScope, personalCenterApi) {
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
        .controller('conferenceDetailCtrl', ['$scope', '$state', '$stateParams', '$uibModal', 'globalFunction', 'conferenceApi', 'ngDialog', function($scope, $state, $stateParams, $uibModal, globalFunction, conferenceApi, ngDialog) {
            conferenceApi.bookDetail({ id: $stateParams.detail_id }).$promise.then(function(data) {
                // console.log(data.book_start.slice(11,16));
                $scope.data = data;
                $scope.update_id = $stateParams.detail_id;
                var startTime = data.book_start.slice(11, 16);
                var endTime = data.book_end.slice(11, 16);
                $scope.data.bookTime = startTime + '-' + endTime;
            })
        }])
        .controller('conferenceOwnCtrl', ['$scope', '$state', '$stateParams', '$uibModal', 'globalFunction', 'conferenceApi', 'ngDialog', function($scope, $state, $stateParams, $uibModal, globalFunction, conferenceApi, ngDialog) {

            $scope.page = {
                "pageSize": 7,
                "pageNo": 0,
                "totalCount": 0,
                "totalPage": 0
            };
            $scope.book_records = {};
            $scope.doRequest = function(page, perPage) {
                conferenceApi.ownBook({ page: page, count: perPage }).$promise.then(function(res) {
                    $scope.book_records = res.data;
                    $scope.page.totalCount = res.page.total_count;
                    $scope.page.totalPage = res.page.total_page;
                    // $scope.page.totalPage = headers('X-Pagination-Page-Count');
                    // console.log(data, stat, headers);
                });
            };
            $scope.doRequest($scope.page.pageNo, $scope.page.pageSize);

            $scope.remove = function(id) {
                conferenceApi.remove({ id: id }).$promise.then(function(res) {
                    $scope.msg = '撤销成功';
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                    dialog.closePromise.then(function() {
                        // console.log($scope.book_id);
                        $state.reload();
                    });
                }, function(res) {
                    $scope.msg = res.data.message;
                    var dialog = ngDialog.open({
                        template: './views/popup/alert.html',
                        className: 'ngdialog-theme-default',
                        showClose: false,
                        scope: $scope
                    });
                });
            }
        }])
        .filter('propsFilter', function() {
            return function(items, props) {
                var out = [];

                if (angular.isArray(items)) {
                    items.forEach(function(item) {
                        var itemMatches = false;
                        var keys = Object.keys(props);
                        for (var i = 0; i < keys.length; i++) {
                            var prop = keys[i];
                            var text = props[prop].toLowerCase();
                            if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                                itemMatches = true;
                                break;
                            }
                        }
                        if (itemMatches) {
                            out.push(item);
                        }
                    });
                } else {
                    out = items;
                }
                return out;
            };
        })
        .filter('accessTimeFilter', function() {
            return function(input) {
                angular.forEach(input, function(item) {
                    item.point = String(item.point).slice(0, 5);
                })
                return input;
            }
        })
        .directive('slimScroll', [
            function() {
                return {
                    restrict: 'A',
                    link: function(scope, ele, attrs) {
                        return ele.slimScroll({
                            height: attrs.scrollHeight || '100%',
                            color: '#abc',
                            wheelStep: '12px',
                        }).on('slimscroll', function(e, pos) {
                            if (pos == 'bottom') {
                                // attrs['bottomReached'];
                                // scope.$apply(attrs['bottomReached']);
                                if (angular.isFunction(scope.bottomReached)) {
                                    // scope.bottomReached();
                                }
                            }
                            // scope.bottomReached();
                            // console.log(attrs['bottomReached']);
                        });
                    }
                };
            }
        ])
        .directive('errSrc', function() {
            return {
                link: function(scope, element, attrs) {
                    // element.on('error', function() {
                    // if (attrs.src != attrs.errSrc) {
                    if (attrs.src == '') {
                        attrs.$set('src', attrs.errSrc);
                    }
                    // });
                }
            }
        })
        // .directive('heightAsWidth', function() {
        //     return {
        //         link: function(scope, element, attrs) {
        //             element.css('height', element[0].offsetWidth);
        //         }
        //     }
        // })
        .directive('fixedWidth', function() {
            return {
                link: function(scope, element, attrs) {
                    var when_width = window.innerWidth || document.body.clientWidth;
                    element.css('width', (when_width - 730 - 70) + 'px');
                    window.onresize = function() {
                        var when_width = window.innerWidth || document.body.clientWidth;
                        element.css('width', (when_width - 730 - 70) + 'px');
                    }
                }
            }
        })
        // .directive('alertBookTime', function() {
        //     return {
        //         link: function(scope, element, attrs) {
        //             // element.css('height', element[0].offsetWidth);
        //             // console.log(element);
        //             // 
        //             var actualWidth = element[0].offsetWidth;
        //             var actualHeight = element[0].offsetHeight;
        //             // var actualLeft = element[0].offsetLeft;
        //             // var current = element[0].offsetParent;
        //             // while (current !== null) {
        //             //     actualLeft += angular.element(current)[0].offsetLeft;
        //             //     current = angular.element(current)[0].offsetParent;
        //             // }
        //             var cur_input = element.find('input');
        //             var cur_element = element.find('.float_remind_box');
        //             var item = angular.fromJson(attrs.item);
        //             var row = Number(attrs.row);
        //             if (item.is_use) {
        //                 element.on('mouseenter', function(event) {
        //                     event.stopPropagation();
        //                     if (cur_input[0].checked) {
        //                         cur_element.css('display', 'none');
        //                         // var box_book = angular.element(angular.element.find('.float_remind_box_book')[0]);
        //                         // box_book.css('display', 'block');
        //                         // box_book.css('left', actualWidth * item.id + 'px');
        //                         // box_book.css('top', (row * (actualHeight + 7) + 40) + 'px');
        //                     } else {
        //                         cur_element.css('display', 'block');
        //                     }
        //                     // cur_element.css('display', 'block');
        //                     // var actualLeft = element[0].offsetLeft;
        //                     //  var actualTop = element[0].offsetTop;
        //                     // var current = element[0].offsetParent;                        
        //                     // // actualLeft += angular.element(current)[0].offsetLeft;
        //                     // while (current !== null) {
        //                     //     actualLeft += angular.element(current)[0].offsetLeft;
        //                     //     actualTop += angular.element(current)[0].offsetTop;
        //                     //     current = angular.element(current)[0].offsetParent;
        //                     // }

    //                     // cur_element.css('left', (actualLeft-61)+'px');
    //                     // cur_element.css('top', (actualTop-36)+'px');
    //                     // console.log(item, actualWidth, actualHeight);

    //                 });
    //                 element.on('mouseleave', function(event) {
    //                     event.stopPropagation();
    //                     cur_element.css('display', 'none');
    //                     angular.element(angular.element.find('.float_remind_box_book')[0]).css('display', 'none');
    //                 });
    //             }
    //         }
    //     }
    // });
}).call(this);
