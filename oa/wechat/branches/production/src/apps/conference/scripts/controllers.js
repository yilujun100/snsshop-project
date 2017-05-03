(function() {
    'use strict';
    angular.module('conference.controllers', [])
        .controller('mainController', ['$scope', '$location', '$route', 'userManager', 'user', function($scope, $location, $route, userManager, user) {
            $scope.showHeader = false;
            $scope.user = user;
            $scope.currentPath = '';
            $scope.$on('$routeChangeSuccess', function(e) {
                $scope.currentPath = $location.path();
                // console.log($scope.currentPath);
            })
            $scope.isCurrentPath = function(paths) {
                var pathsArray = paths.split(',');
                var pathsLength = pathsArray.length;
                var flag = false;
                for (var i = 0; i < pathsLength; i++) {
                    if ($scope.currentPath.indexOf(pathsArray[i]) >= 0) {
                        flag = true;
                    }
                }
                return flag;
            };
        }])
        .controller('recordListController', ['$scope', '$timeout', '$location', '$route', 'globalPagination', 'conferenceApi',
            function($scope, $timeout, $location, $route, globalPagination, conferenceApi) {
                var now = new Date();
                $scope.currentDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                conferenceApi.query().$promise.then(function(data) {
                    $scope.conferenceItems = data;
                    $scope.currentItem = data[0];
                    // conferenceApi.bookList({ room_id: $scope.currentItem.id, date: Date.parse($scope.currentDate) / 1000 }).$promise.then(function(data) {
                    // console.log($scope.currentDate);
                    // })
                    // conferenceApi.bookList({ room_id: $scope.currentItem.id, date: Date.parse($scope.currentDate) / 1000 }).$promise.then(function(data) {
                    //     // console.log($scope.currentItem.id);
                    //     var result = [];
                    //     for (var x = 0; x < (data.data.length / 2); x++) {
                    //         var start = x * 2;
                    //         var end = start + 2;
                    //         result.push(data.data.slice(start, end));
                    //     }
                    //     // console.log(result);
                    //     $scope.bookRecords = result;
                    // })

                    $scope.$watch('currentDate', function(new_value, old_value) {
                        if (new_value) {
                            // $scope.selectDate = Date.parse(new_value) / 1000; //选择日期的时间戳
                            var newDate = new Date(new_value);
                            var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                            var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                            $scope.selectDate = newDate.getFullYear() + '-' + selectMonth + '-' + selectDay;
                            // console.log($scope.selectDate);
                            //获取可选时间段
                            conferenceApi.bookTimeList({ room_id: $scope.currentItem.id, date: $scope.selectDate }).$promise.then(function(data) {
                                // console.log($scope.currentItem.id);
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
                    })
                })

                // 选择会议室
                $scope.showSelect = false;
                $scope.selectConference = function() {
                    $scope.showSelect = $scope.showSelect ? false : true;
                }
                $scope.clickMask = function() {
                    $scope.showSelect = false;
                }
                $scope.changeData = function(e, index) {
                    e.stopPropagation();
                    $scope.currentItem = $scope.conferenceItems[index];
                    $scope.showSelect = false;
                    var newDate = new Date($scope.currentDate);
                    var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                    var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                    $scope.selectDate = newDate.getFullYear() + '-' + selectMonth + '-' + selectDay;

                    conferenceApi.bookList({ room_id: $scope.currentItem.id, date: $scope.selectDate }).$promise.then(function(data) {
                        // console.log($scope.currentItem.id);
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
                $scope.leftStyle = {
                    height: (100 * 2) + 'px'
                }
                $scope.leftItemStyle = {
                    height: ((100 * 2) / 2 - 30) + 'px'
                }

            }
        ])
        .controller('recordCreateController', ['$scope', '$timeout', '$routeParams', '$location', '$filter', 'globalFunction', 'modalExtension', 'conferenceApi',
            function($scope, $timeout, $routeParams, $location, $filter, globalFunction, modalExtension, conferenceApi) {
                $scope.bookCreate = {};
                $scope.bookCreate.book_clock = 10;
                // $scope.currentItem = {name:'选择会议室', id:''};

                // 会议室选择列表
                conferenceApi.query().$promise.then(function(data) {
                    $scope.conferenceItems = data;
                    $scope.currentItem = data[0];
                    $scope.bookCreate.room_id = $scope.currentItem.id;
                    // console.log(data);
                    //获取可选时间段
                    // conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: Date.parse($scope.currentDate) / 1000 }).$promise.then(function(data) {
                    //     console.log(data);
                    //     $scope.accessTime = data;
                    // })

                    $scope.$watch('currentDate', function(new_value, old_value) {
                        if (new_value) {
                            // console.log(new_value);
                            // $scope.selectDate = Date.parse(new_value) / 1000; //选择日期的时间戳
                            var newDate = new Date(new_value);
                            var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                            var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                            $scope.bookCreate.book_date = newDate.getFullYear() + '-' + selectMonth + '-' + selectDay;
                            $scope.bookCreate.times = [];
                            $scope.showStartTime = '';
                            $scope.showEndTime = '';
                            // console.log($scope.bookCreate.book_date,selectMonth,selectDay);
                            //获取可选时间段
                            conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: $scope.bookCreate.book_date }).$promise.then(function(data) {
                                // console.log(data);
                                $scope.accessTime = data;
                            })
                        }
                    })
                })

                // 选择会议室
                $scope.showSelect = false;
                $scope.selectConference = function() {
                        $scope.showSelect = $scope.showSelect ? false : true;
                    }
                    //蒙层
                $scope.clickMask = function() {
                    $scope.showSelect = false;
                }
                $scope.changeData = function(e, index) {
                    e.stopPropagation();
                    $scope.currentItem = $scope.conferenceItems[index];
                    $scope.bookCreate.room_id = $scope.currentItem.id;
                    $scope.bookCreate.times = [];
                    $scope.showStartTime = '';
                    $scope.showEndTime = '';
                    $scope.showSelect = false;
                    conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: $scope.bookCreate.book_date }).$promise.then(function(data) {
                        // console.log(data);
                        $scope.accessTime = data;
                    })
                }


                //选择日期
                var now = new Date();
                $scope.currentDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                // console.log($scope.currentDate);


                // 选择参加会议人员
                $scope.bookUsers = function() {
                    $scope.showBookUsers = true;
                    $scope.showUsersContent = true;
                }
                $scope.clickMaskUser = function() {
                    $scope.showBookUsers = false;
                    $scope.showUsersContent = false;
                }

                var buildTree = function(node, data) {
                    node.items = _.where(data, { 'parent_id': node.id });
                    if (node.items) {
                        _.each(node.items, function(item) {
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
                    $scope.list.push(topNode);

                    buildTree(topNode, data.data);
                    // $scope.parentDepartment = $filter('filter')(data.data,{'parent_id':1});                  
                    // console.log($scope.list);
                })

                $scope.collapseVar = [];

                $scope.check = function(x, item, e) {
                    e.stopPropagation();
                    $scope.selectUserDarp(x, item, e);
                    if (x == $scope.collapseVar[x])
                        $scope.collapseVar[x] = 0;
                    else {
                        $scope.collapseVar[x] = x;
                        // $scope.pagination.currentDepartmentId = x;
                    }
                };

                $scope.selectUserDarp = function(x, item, e) {
                    e.stopPropagation();
                    $scope.departmentUsers = [];
                    $scope.pagination = { currentDepartmentId: 0, page: 0, count: 15, total_pages: 0 };
                    $scope.pagination.currentDepartmentId = x;
                    $scope.select($scope.pagination.page, $scope.pagination.currentDepartmentId, $scope.pagination.count);
                };

                //初始化列表數據

                $scope.select = function(page, select_id, count) {
                    conferenceApi.users({ id: select_id, page: page, count: count }).$promise.then(function(data) {
                        $scope.pagination.total_pages = data.page.total_page;
                        $scope.departmentUsers = _.union($scope.departmentUsers, data.data);
                        // console.log(data);
                    })
                };
                $scope.isLast = function(page) {
                    return page >= $scope.pagination.total_pages - 1
                }
                $scope.bottomReached = function() {
                    if (!$scope.isLast($scope.pagination.page)) {
                        $scope.pagination.page = $scope.pagination.page + 1;
                        $scope.select($scope.pagination.page, $scope.pagination.currentDepartmentId, $scope.pagination.count)
                    }
                }


                //点击每个人员
                $scope.bookCreate.users = [];
                // $scope.sureAllCheckedUsers = [];
                var selected = [];
                $scope.allCheckedUsers = [];
                var updateBookCreateUsers = function(action, id, item) {
                    if (action == 'add' && $scope.bookCreate.users.indexOf(id) == -1) {
                        $scope.bookCreate.users.push(id);
                        $scope.allCheckedUsers.push(item);
                    }
                    if (action == 'remove' && $scope.bookCreate.users.indexOf(id) != -1) {
                        var idx = $scope.bookCreate.users.indexOf(id);
                        $scope.bookCreate.users.splice(idx, 1);
                        $scope.allCheckedUsers.splice(idx, 1);
                    }
                }

                var updateSelected = function(action, id) {
                    if (action == 'add' && selected.indexOf(id) == -1) {
                        selected.push(id);
                    }
                    if (action == 'remove' && selected.indexOf(id) != -1) {
                        var idx = selected.indexOf(id);
                        selected.splice(idx, 1);
                    }
                }

                $scope.isSelected = function(id) {
                    return selected.indexOf(id) >= 0;
                }

                $scope.checkedUser = function(e, item) {
                    e.stopPropagation();
                    // console.log(e.target.checked);
                    var checkbox = e.target;
                    var action = (checkbox.checked ? 'add' : 'remove');
                    updateBookCreateUsers(action, item.id, item);
                    // var flag = 0;
                    // var usersLength = var allCheckedUsers.length;
                    // if (!usersLength) {
                    //     var allCheckedUsers.push(item);
                    // } else {
                    //     for (var i = 0; i < usersLength; i++) {
                    //         if (var allCheckedUsers[i] == item) {
                    //             var allCheckedUsers.splice(i, 1);
                    //             var flag = 1;
                    //         }
                    //     }
                    //     if (!flag) {
                    //         var allCheckedUsers.push(item);
                    //     }
                    // }
                    // console.log(var allCheckedUsers,var selected);
                }

                var funcArray = [];
                $scope.pushfuncArray = function(e, item) {

                    funcArray.push([e, item]);
                    var checkbox = e.target;
                    var action = (checkbox.checked ? 'add' : 'remove');
                    updateSelected(action, item.id);
                    // console.log($scope.bookCreate.users,selected);
                }

                //搜索人员
                $scope.searchUserFn = function() {
                    conferenceApi.searchUser({ name: $scope.inputName }).$promise.then(function(data) {
                        // console.log(data);
                        $scope.departmentUsers = data.data;
                    })
                }

                //删除选中人员
                $scope.removeUser = function(user) {
                        var idx = $scope.bookCreate.users.indexOf(user.id);
                        $scope.bookCreate.users.splice(idx, 1);
                        $scope.allCheckedUsers.splice(idx, 1);
                        var idx = $scope.selected.indexOf(user.id);
                        $scope.selected.splice(idx, 1);
                    }
                    //确定选择人员
                $scope.sureUsers = function() {
                    angular.forEach(funcArray, function(item) {
                        $scope.checkedUser(item[0], item[1]);
                    })
                    $scope.clickMaskUser();
                }

                //取消选择人员
                $scope.noSUsers = function() {
                    $scope.clickMaskUser();
                }

                //提交
                $scope.submit = function() {
                    // console.log($scope.bookCreate);
                    conferenceApi.bookCreate($scope.bookCreate).$promise.then(function(data) {
                        if (data.errcode == 0) {
                            $scope.book_id = data.data.book_id;
                            modalExtension.alert('预定成功').then(function() {
                                $location.path('/record/detail/' + $scope.book_id);
                            })
                        }
                        if (data.errcode == 1)
                            modalExtension.alert(data.errmsg);
                    })
                };


                //选择时间段
                $scope.bookCreate.times = [];
                var updateSelectedTimes = function(action, point) {
                    if (action == 'add' && $scope.bookCreate.times.indexOf(point) == -1) {
                        $scope.bookCreate.times.push(point);
                        $scope.maxAndMinTime();
                    }
                    if (action == 'remove' && $scope.bookCreate.times.indexOf(point) != -1) {
                        var idx = $scope.bookCreate.times.indexOf(point);
                        $scope.bookCreate.times.splice(idx, 1);
                        $scope.maxAndMinTime();
                    }
                }
                $scope.isSelectedTime = function(item) {
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    return $scope.bookCreate.times.indexOf(point) >= 0;
                }
                $scope.checkboxClick = function(item, e) {
                    e.stopPropagation();
                    var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                    var checkbox = e.target;
                    var action = (checkbox.checked ? 'add' : 'remove');
                    updateSelectedTimes(action, point);
                    // var timesLength = $scope.bookCreate.times.length;
                    // var flag = 0;
                    // if (!timesLength) {
                    //     $scope.bookCreate.times.push(point);
                    // } else {
                    //     for (var i = 0; i < timesLength; i++) {
                    //         if ($scope.bookCreate.times[i] == point) {
                    //             $scope.bookCreate.times.splice(i, 1);
                    //             var flag = 1;
                    //         }
                    //     }
                    //     if (!flag) {
                    //         $scope.bookCreate.times.push(point);
                    //     }
                    // }
                    // console.log($scope.bookCreate.times,$scope.selectedTimes);
                }

                // $scope.maxAndMinTime = function() {
                //     var timesArray = [];
                //     angular.forEach($scope.selectedTimesId, function(item, index) {
                //         timesArray[index] = item;
                //     });
                //     if (!timesArray.length) {
                //         $scope.showStartTime = '';
                //     } else if (timesArray.length == 1) {
                //         $scope.showStartTime = $filter('filter')($scope.accessTime[timesArray[0]]).point;
                //         $scope.showEndTime = $scope.accessTime[timesArray[0]].point.slice(0, 3) + (Number($scope.accessTime[timesArray[0]].point.slice(3)) + 29);
                //         // $scope.showEndTime = new Date(timesArray[0] + 29 * 60 * 1000).toString().slice(16, 21);
                //     } else {
                //         var bookStartTime = Math.min.apply(null, timesArray);
                //         var bookEndTime = Math.max.apply(null, timesArray);
                //         $scope.showStartTime = $scope.accessTime[bookStartTime].point;
                //         $scope.showEndTime = $scope.accessTime[bookEndTime].point.slice(0, 3) + (Number($scope.accessTime[bookEndTime].point.slice(3)) + 29);
                //     }

                //     console.log($scope.showStartTime, $scope.showEndTime,timesArray);
                // }



                //显示选择时间段
                $scope.maxAndMinTime = function() {
                    var timesArray = [];
                    angular.forEach($scope.bookCreate.times, function(item, index) {
                        var partArray = item.split(' ');
                        var leftPart = partArray[0].split('-');
                        var rightPart = partArray[1].split(':');
                        timesArray[index] = Date.parse(new Date(leftPart[0], (Number(leftPart[1]) - 1), Number(leftPart[2]), rightPart[0], rightPart[1], rightPart[2]));
                    })
                    if (!timesArray.length) {
                        $scope.showStartTime = '';
                    } else if (timesArray.length == 1) {
                        $scope.showStartTime = new Date(parseInt(timesArray[0])).toString().slice(16, 21);
                        $scope.showEndTime = new Date(parseInt(timesArray[0]) + 29 * 60 * 1000).toString().slice(16, 21);
                    } else {
                        var bookStartTime = Math.min.apply(null, timesArray);
                        var bookEndTime = Math.max.apply(null, timesArray);

                        $scope.showStartTime = new Date(bookStartTime).toString().slice(16, 21);
                        $scope.showEndTime = new Date(parseInt(bookEndTime) + 29 * 60 * 1000).toString().slice(16, 21);
                    }

                    // console.log($scope.showStartTime, $scope.showEndTime,timesArray);
                }



            }
        ])
        .controller('recordUpdateController', ['$scope', '$timeout', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'modalExtension', 'conferenceApi',
            function($scope, $timeout, $routeParams, $location, globalFunction, globalConfig, modalExtension, conferenceApi) {


                conferenceApi.bookDetail({ id: $routeParams.id }).$promise.then(function(data) {
                    $scope.bookCreate = data;
                    $scope.bookCreate.book_clock = 10;
                    $scope.room_id = data.room_id;
                    $scope.book_date = data.book_date;
                    $scope.startTime = data.book_start.slice(11, 16);
                    $scope.endTime = data.book_end.slice(11, 16);
                    $scope.book_date_format = Date.parse(new Date($scope.book_date)) / 1000;
                    $scope.currentDate = new Date($scope.bookCreate.book_date);



                    // 会议室选择列表
                    conferenceApi.query().$promise.then(function(data) {
                        $scope.conferenceItems = data;
                        angular.forEach(data, function(item) {
                            if (item.id == $scope.bookCreate.room_id)
                                $scope.currentItem = item;
                        })
                        $scope.bookCreate.room_id = $scope.currentItem.id;
                        // console.log(data);
                        //获取可选时间段
                        // conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: Date.parse($scope.currentDate) / 1000 }).$promise.then(function(data) {
                        //     console.log(data);
                        //     $scope.accessTime = data;
                        // })

                        $scope.$watch('currentDate', function(new_value, old_value) {
                            if (new_value) {
                                $scope.selectDate = Date.parse(new_value) / 1000; //选择日期的时间戳
                                var newDate = new Date(new_value);
                                var selectMonth = newDate.getMonth() >= 9 ? (newDate.getMonth() + 1) : '0' + (newDate.getMonth() + 1);
                                var selectDay = newDate.getDate() >= 10 ? newDate.getDate() : '0' + newDate.getDate();
                                $scope.bookCreate.book_date = newDate.getFullYear() + '-' + selectMonth + '-' + selectDay;


                                if ($scope.currentItem.id == $scope.room_id && $scope.book_date_format == $scope.selectDate) {
                                    $scope.bookCreate.times = $scope.bookCreate.room_book_time;
                                    $scope.showStartTime = $scope.startTime;
                                    $scope.showEndTime = $scope.endTime;
                                } else {
                                    $scope.bookCreate.times = [];
                                    $scope.showStartTime = '';
                                    $scope.showEndTime = '';
                                }
                                // console.log(new Date($scope.book_date),newDate);
                                //获取可选时间段
                                conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: $scope.bookCreate.book_date, book_id: $routeParams.id }).$promise.then(function(data) {
                                    // console.log(data);
                                    $scope.accessTime = data;
                                })
                            }
                        })
                    })



                    // 选择会议室
                    $scope.showSelect = false;
                    $scope.selectConference = function() {
                            $scope.showSelect = $scope.showSelect ? false : true;
                        }
                        //蒙层
                    $scope.clickMask = function() {
                        $scope.showSelect = false;
                    }
                    $scope.changeData = function(e, index) {
                        e.stopPropagation();
                        $scope.currentItem = $scope.conferenceItems[index];
                        $scope.bookCreate.room_id = $scope.currentItem.id;
                        $scope.showSelect = false;

                        if ($scope.currentItem.id == $scope.room_id && $scope.book_date_format == $scope.selectDate) {
                            $scope.bookCreate.times = $scope.bookCreate.room_book_time;
                            $scope.showStartTime = $scope.startTime;
                            $scope.showEndTime = $scope.endTime;
                        } else {
                            $scope.bookCreate.times = [];
                            $scope.showStartTime = '';
                            $scope.showEndTime = '';
                        }

                        conferenceApi.accessTime({ room_id: $scope.currentItem.id, date: $scope.bookCreate.book_date, book_id: $routeParams.id }).$promise.then(function(data) {
                            // console.log(data);
                            $scope.accessTime = data;
                        })
                    }




                    // 选择参加会议人员
                    $scope.bookUsers = function() {
                        $scope.showBookUsers = true;
                        $scope.showUsersContent = true;
                    }
                    $scope.clickMaskUser = function() {
                        $scope.showBookUsers = false;
                        $scope.showUsersContent = false;
                    }

                    var buildTree = function(node, data) {
                        node.items = _.where(data, { 'parent_id': node.id });
                        if (node.items) {
                            _.each(node.items, function(item) {
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
                        $scope.list.push(topNode);

                        buildTree(topNode, data.data);
                        // $scope.parentDepartment = $filter('filter')(data.data,{'parent_id':1});                  
                        // console.log($scope.list);
                    })

                    $scope.collapseVar = [];

                    $scope.check = function(x, item, e) {
                        e.stopPropagation();
                        $scope.selectUserDarp(x, item, e);
                        if (x == $scope.collapseVar[x])
                            $scope.collapseVar[x] = 0;
                        else {
                            $scope.collapseVar[x] = x;
                            // $scope.pagination.currentDepartmentId = x;
                        }
                    };

                    $scope.selectUserDarp = function(x, item, e) {
                        e.stopPropagation();
                        $scope.departmentUsers = [];
                        $scope.pagination = { currentDepartmentId: 0, page: 0, count: 15, total_pages: 0 };
                        $scope.pagination.currentDepartmentId = x;
                        $scope.select($scope.pagination.page, $scope.pagination.currentDepartmentId, $scope.pagination.count);
                    };

                    //初始化列表數據

                    $scope.select = function(page, select_id, count) {
                        conferenceApi.users({ id: select_id, page: page, count: count }).$promise.then(function(data) {
                            $scope.pagination.total_pages = data.page.total_page;
                            $scope.departmentUsers = _.union($scope.departmentUsers, data.data);
                            // console.log(data);
                        })
                    };
                    $scope.isLast = function(page) {
                        return page >= $scope.pagination.total_pages - 1
                    }
                    $scope.bottomReached = function() {
                        if (!$scope.isLast($scope.pagination.page)) {
                            $scope.pagination.page = $scope.pagination.page + 1;
                            $scope.select($scope.pagination.page, $scope.pagination.currentDepartmentId, $scope.pagination.count)
                        }
                    }


                    //点击每个人员
                    $scope.bookCreate.users = [];
                    var selected = [];
                    $scope.allCheckedUsers = [];
                    angular.forEach($scope.bookCreate.room_book_user, function(item) {
                        $scope.bookCreate.users.push(item.book_user_id);
                        selected.push(item.book_user_id);
                        $scope.allCheckedUsers.push(item.user);
                    })



                    var updateBookCreateUsers = function(action, id, item) {
                        if (action == 'add' && $scope.bookCreate.users.indexOf(id) == -1) {
                            $scope.bookCreate.users.push(id);
                            $scope.allCheckedUsers.push(item);
                        }
                        if (action == 'remove' && $scope.bookCreate.users.indexOf(id) != -1) {
                            var idx = $scope.bookCreate.users.indexOf(id);
                            $scope.bookCreate.users.splice(idx, 1);
                            $scope.allCheckedUsers.splice(idx, 1);
                        }
                    }

                    var updateSelected = function(action, id) {
                        if (action == 'add' && selected.indexOf(id) == -1) {
                            selected.push(id);
                        }
                        if (action == 'remove' && selected.indexOf(id) != -1) {
                            var idx = selected.indexOf(id);
                            selected.splice(idx, 1);
                        }
                    }

                    $scope.isSelected = function(id) {
                        return selected.indexOf(id) >= 0;
                    }

                    $scope.checkedUser = function(e, item) {
                        e.stopPropagation();
                        // console.log(e.target.checked);
                        var checkbox = e.target;
                        var action = (checkbox.checked ? 'add' : 'remove');
                        updateBookCreateUsers(action, item.id, item);
                        // var flag = 0;
                        // var usersLength = var allCheckedUsers.length;
                        // if (!usersLength) {
                        //     var allCheckedUsers.push(item);
                        // } else {
                        //     for (var i = 0; i < usersLength; i++) {
                        //         if (var allCheckedUsers[i] == item) {
                        //             var allCheckedUsers.splice(i, 1);
                        //             var flag = 1;
                        //         }
                        //     }
                        //     if (!flag) {
                        //         var allCheckedUsers.push(item);
                        //     }
                        // }
                        // console.log(var allCheckedUsers,var selected);
                    }

                    var funcArray = [];
                    $scope.pushfuncArray = function(e, item) {

                        funcArray.push([e, item]);
                        var checkbox = e.target;
                        var action = (checkbox.checked ? 'add' : 'remove');
                        updateSelected(action, item.id);
                        // console.log($scope.bookCreate.users,selected);
                    }

                    //搜索人员
                    $scope.searchUserFn = function() {
                        conferenceApi.searchUser({ name: $scope.inputName }).$promise.then(function(data) {
                            // console.log(data);
                            $scope.departmentUsers = data.data;
                        })
                    }

                    //删除选中人员
                    $scope.removeUser = function(user) {
                        var idx = $scope.bookCreate.users.indexOf(user.id);
                        $scope.bookCreate.users.splice(idx, 1);
                        $scope.allCheckedUsers.splice(idx, 1);
                        var idx = selected.indexOf(user.id);
                        selected.splice(idx, 1);
                    }

                    //确定选择人员
                    $scope.sureUsers = function() {
                        angular.forEach(funcArray, function(item) {
                            $scope.checkedUser(item[0], item[1]);
                        })
                        $scope.clickMaskUser();
                    }

                    //取消选择人员
                    $scope.noSUsers = function() {
                        $scope.clickMaskUser();
                    }

                    //选择时间段

                    var updateSelectedTimes = function(action, point) {
                        if (action == 'add' && $scope.bookCreate.times.indexOf(point) == -1) {
                            $scope.bookCreate.times.push(point);
                            $scope.maxAndMinTime();
                        }
                        if (action == 'remove' && $scope.bookCreate.times.indexOf(point) != -1) {
                            var idx = $scope.bookCreate.times.indexOf(point);
                            $scope.bookCreate.times.splice(idx, 1);
                            $scope.maxAndMinTime();
                        }
                    }
                    $scope.isSelectedTime = function(item) {
                        var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                        return $scope.bookCreate.times.indexOf(point) >= 0;
                    }
                    $scope.checkboxClick = function(item, e) {
                        e.stopPropagation();
                        var point = $scope.bookCreate.book_date + ' ' + item.point + ':00';
                        var checkbox = e.target;
                        var action = (checkbox.checked ? 'add' : 'remove');
                        updateSelectedTimes(action, point);
                        // var timesLength = $scope.bookCreate.times.length;
                        // var flag = 0;
                        // if (!timesLength) {
                        //     $scope.bookCreate.times.push(point);
                        // } else {
                        //     for (var i = 0; i < timesLength; i++) {
                        //         if ($scope.bookCreate.times[i] == point) {
                        //             $scope.bookCreate.times.splice(i, 1);
                        //             var flag = 1;
                        //         }
                        //     }
                        //     if (!flag) {
                        //         $scope.bookCreate.times.push(point);
                        //     }
                        // }
                        // console.log($scope.bookCreate.times,$scope.selectedTimes);
                    }

                    $scope.maxAndMinTime = function() {
                        var timesArray = [];
                        angular.forEach($scope.bookCreate.times, function(item, index) {
                            var partArray = item.split(' ');
                            var leftPart = partArray[0].split('-');
                            var rightPart = partArray[1].split(':');
                            timesArray[index] = Date.parse(new Date(leftPart[0], (Number(leftPart[1]) - 1), Number(leftPart[2]), rightPart[0], rightPart[1], rightPart[2]));
                        })
                        if (!timesArray.length) {
                            $scope.showStartTime = '';
                        } else if (timesArray.length == 1) {
                            $scope.showStartTime = new Date(timesArray[0]).toString().slice(16, 21);
                            $scope.showEndTime = new Date(parseInt(timesArray[0]) + 29 * 60 * 1000).toString().slice(16, 21);
                        } else {
                            var bookStartTime = Math.min.apply(null, timesArray);
                            var bookEndTime = Math.max.apply(null, timesArray);

                            $scope.showStartTime = new Date(bookStartTime).toString().slice(16, 21);
                            $scope.showEndTime = new Date(parseInt(bookEndTime) + 29 * 60 * 1000).toString().slice(16, 21);
                        }

                        // console.log($scope.showStartTime, $scope.showEndTime,timesArray);
                    }


                    //提交
                    $scope.submit = function() {
                        // console.log($scope.bookCreate,data.book_date,data.room_id,$scope.room_id,$scope.book_date);
                        conferenceApi.bookUpdate($scope.bookCreate).$promise.then(function(data) {
                            if (data.errcode == 0) {
                                modalExtension.alert('修改成功').then(function() {
                                    $location.path('/record/detail/' + $routeParams.id);
                                })
                            }
                            if (data.errcode == 1)
                                modalExtension.alert(data.errmsg);
                        })
                    };


                })
            }
        ])
        .controller('recordDetailController', ['$scope', '$timeout', '$route', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'weChat', 'ModalService', 'modalExtension', 'conferenceApi',
            function($scope, $timeout, $route, $routeParams, $location, globalFunction, globalConfig, weChat, ModalService, modalExtension, conferenceApi) {
                // $scope.$parent.showEditing = true;
                //格式化时期时间
                // Date.prototype.Format = function(fmt) {
                //     var o = {
                //         "M+": this.getMonth() + 1, //月份   
                //         "d+": this.getDate(), //日   
                //         "h+": this.getHours(), //小时   
                //         "m+": this.getMinutes(), //分   
                //         "s+": this.getSeconds(), //秒   
                //         "q+": Math.floor((this.getMonth() + 3) / 3), //季度   
                //         "S": this.getMilliseconds() //毫秒   
                //     };
                //     if (/(y+)/.test(fmt))
                //         fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                //     for (var k in o)
                //         if (new RegExp("(" + k + ")").test(fmt))
                //             fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                //     return fmt;
                // }
                conferenceApi.bookDetail({ id: $routeParams.id }).$promise.then(function(data) {
                    // console.log(data.book_start.slice(11,16));
                    $scope.data = data;
                    var startTime = data.book_start.slice(11, 16);
                    var endTime = data.book_end.slice(11, 16);
                    $scope.data.bookDateTime = $scope.data.book_date + ' ' + startTime + '-' + endTime;
                })
            }
        ])
        .filter('accessTimeFilter', function() {
            return function(input) {
                angular.forEach(input, function(item) {
                    item.point = String(item.point).slice(0, 5);
                })
                return input;
            }
        })
        .filter('changeHref', function() {
            return function(input) {
                angular.forEach(input, function(item) {
                    if (item.data)
                        item.href = "#/record/detail/" + item.data.book_id;
                    else
                        item.href = '';
                })
                return input;
            }
        })
        .directive('errSrc', function() {
            return {
                link: function(scope, element, attrs) {
                    element.bind('error', function() {
                        if (attrs.src != attrs.errSrc) {
                            attrs.$set('src', attrs.errSrc);
                        }
                    });
                }
            }
        })
        .directive('slimScroll', [
            function() {
                return {
                    restrict: 'A',
                    link: function(scope, ele, attrs) {
                        return $(ele).slimScroll({
                            height: attrs.scrollHeight || '100%',
                            color: '#fff',
                            wheelStep: '50px'
                        });
                    }
                };
            }
        ]);
}).call(this);
