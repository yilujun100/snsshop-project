(function() {
    'use strict';
    angular.module('directory.configurations',['app.configurations.config']).config(function(globalConfig){
        globalConfig.moduleCode = 'directory';
    })
}).call(this);
;
// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('directory', [
    'ngRoute',
    'ngCookies',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'angular-loading-bar',

    'app.configurations.config',
    'app.configurations.config-local',
    'app.constants.function-param',
    'app.constants.function-config',
    'app.directives.ui',
    'app.services.resource',
    'app.services.function',
    'app.controllers.auth',

    'directory.configurations',
    'directory.controllers',
    'directory.resource'

]);

app.run(['$transform',function($transform) {
    window.$transform = $transform;
}]);

// 
// You can configure ngRoute as always, but to take advantage of SharedState location
// feature (i.e. close sidebar on backbutton) you should setup 'reloadOnSearch: false' 
// in order to avoid unwanted routing.
// 
app.config(['$routeProvider',function($routeProvider) {
    $routeProvider
        .when('/list',{
            templateUrl: 'views/list.html',
            controller:'listController'
        })
        .when('/useful-list',{
            templateUrl: 'views/list.html',
            controller:'listController'
        })
        .when('/detail/:id',{
            templateUrl: 'views/detail.html',
            controller:'detailController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/qy_auth',{
            templateUrl: '../../views/auth.html',
            controller:'qyauthController'
        })
}]);
app.config( [ '$compileProvider',function( $compileProvider ){
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|sms):/);
    }
]);

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}])
;
(function() {
    'use strict';
    angular.module('directory.controllers',[])
        .controller('mainController', [ '$scope','$location','$route','userManager','user',function($scope,$location,$route,userManager,user){
            $scope.user = user;
            $scope.keyword = '';
            $scope.search = function(){
                $scope.$broadcast('search',$scope.keyword)
            }
        }])
        .controller('listController',['$scope','$timeout','$location','globalPagination','userApi',function($scope,$timeout,$location,globalPagination,userApi){
            $timeout(function(){
                $scope.$parent.showHeader = true;
                $scope.$parent.keyword = '';
            },0)
            $scope.condition = {
                "keyword":""
            };
            if($location.path() == '/useful-list')
                $scope.condition.only_useful = 1;
            //初始化列表數據
            $scope.pagination = globalPagination.create();
            $scope.pagination.resource = userApi;
            $scope.pagination.sort = 'initial_letter ASC,name ASC,id ASC';
            $scope.select = function(page) {
                $scope.pagination.select(page,$scope.condition_copy).$promise.then(function(data){
                    $scope.users = _.union($scope.users,data);
                })
            };
            $scope.search = function(){
                $scope.condition_copy = angular.copy($scope.condition);
                $scope.users = [];
                $scope.select(1);
            }

            $scope.bottomReached = function(){
                if(!$scope.pagination.isLast())
                    $scope.select($scope.pagination.page+1)
            }

            $scope.search();

            $scope.$on('search',function(event,keyword){
                $scope.condition.keyword = keyword;
                $scope.search();
            })
        }])
        .controller('detailController',['$timeout','$scope','$routeParams','userApi',function($timeout,$scope,$routeParams,userApi){
            $timeout(function(){
                $scope.$parent.showHeader = false;
            },0)
            $scope.currentUser = userApi.get({"id":$routeParams.id});
        }])
}).call(this);
;
/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('directory.resource',['ngResource'])
        .factory('userApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('directory/user');
        }])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.constants.function-param', [])
        .constant('conditionTypes',{
            "equal":'EQUAL',
            "notEqual":'NOTEQUAL',
            "null":'NULL',
            "like":'LIKE',
            "leftLike":'LLIKE',
            "rightLike":'RLIKE',
            "in":'IN',
            "notIn":'NOTIN',
            "min":'MIN',
            "max":'MAX'
        })
        .constant('genders',{
            "0":'',
            "1":'男',
            "2":'女'
        })
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.constants.function-config', [])
        .constant('weChatConfig',{
            apiUrl:"/common/user/js-api-config",
            qy_apiUrl:"/common/qy-wx-user/js-api-config-qywx",
            jsApiList:[]
        })
        .constant('paginationConfig', {
            itemsPerPage: 15
        })
        .config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
            cfpLoadingBarProvider.includeBar = true;
            cfpLoadingBarProvider.includeSpinner = true;
            cfpLoadingBarProvider.spinnerTemplate = '<div class="modal"><div class="toast"><div class="loading-box" ><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div><div class="obtain">加载中</div></div></div>'
        }])
}).call(this);

;
/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('app.services.resource',['ngResource'])
        .factory('commonUserApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('common/user',{},{
            	'qyauth':{method:'get',url:('common/qy-wx-user/login')},
                'auth':{method:'POST',url:('common/user/auth')},
                'login':{method:'GET',url:('common/user/login')}
            });
        }])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.services.function', ['ngCookies'])
        .service('globalFunction', ['globalConfig', '$resource', function(globalConfig, $resource) {
            this.getApiUrl = function(url) {
                return globalConfig.apiUrl + '/' + url;
            }
            this.generateUrlParams = function(condtion, fields) {
                var params = {};
                //set condition
                var setParams = function(params, obj, prefix) {
                    _.each(obj, function(value, key, list) {
                        if (_.isObject(value)) {
                            if (_.isEqual(_.keys(value), ['type', 'value'])) {
                                params[prefix + key] = value.type + '_' + value.value;
                            } else if (_.isEqual(_.keys(value), ['value'])) {
                                params[prefix + key] = value.value;
                            } else {
                                setParams(params, value, prefix + key + '.')
                            }
                        } else {
                            params[prefix + key] = value;
                        }

                    })
                    return obj;
                }
                setParams(params, condtion, '')

                //set fields
                if (fields) {
                    params['fields'] = [];
                    params['expand'] = [];
                    params['expand-fields'] = {};
                    _.each(fields, function(value, key, list) {
                        if (_.isObject(value)) {
                            //console.log(key);
                            params['expand'].push(key);
                            setParams(params['expand-fields'], value, key + '.');
                        } else {
                            params['fields'].push(key);
                        }
                    })
                    params['fields'] = params['fields'].join(',');
                    params['expand'] = params['expand'].join(',');
                    params['expand-fields'] = _.keys(params['expand-fields']).join(',');
                }
                return params;
            }
            this.createResource = function(url, param_defaults, actions) {
                var self = this;
                var inner_actions = {
                    'get': { method: 'GET', url: this.getApiUrl(url + '/:id') },
                    'query': { method: 'GET', isArray: true },
                    'update': { method: 'PUT', url: this.getApiUrl(url + '/:id') },
                    'delete': { method: 'DELETE', url: this.getApiUrl(url + '/:id') }
                };

                var inner_param_defaults = { id: "@id" };
                if (sessionStorage.token) {
                    inner_param_defaults.PHPSESSID = sessionStorage.token;
                }

                _.each(actions, function(action) { action.url = self.getApiUrl(action.url) })

                actions = _.extend(inner_actions, actions);
                param_defaults = _.extend(inner_param_defaults, param_defaults);
                return $resource(this.getApiUrl(url), param_defaults, actions);
            }
            this.debounce = function(fun, wait) {
                if (angular.isUndefined(wait))
                    wait = 800;
                return _.debounce(fun, wait);
            }
        }])
        .factory('globalPagination', ['paginationConfig', '$http', '$q', 'globalFunction', function(paginationConfig, $http, $q, globalFunction) {
            return {
                create: function(options) {
                    var pagination = {
                        items_per_page: paginationConfig.itemsPerPage,
                        total_items: 0,
                        total_pages: 0,
                        page: 1,
                        max_size: paginationConfig.maxSize,
                        query_method: 'query',
                        resource: null,
                        sort: "",
                        condition: {},
                        fields: {},
                        select: function(page, condition, fields) {
                            if (condition == null)
                                condition = this.condition;
                            if (fields == null)
                                fields = this.fields;
                            condition['page'] = this.page = page ? page : 1;
                            condition['per-page'] = this.items_per_page;
                            var _self = this;
                            if (this.sort)
                                condition.sort = this.sort;
                            else
                                delete condition.sort;
                            return this.resource[_self.query_method](globalFunction.generateUrlParams(condition, fields), function(data, headers) {
                                _self.total_items = headers('X-Pagination-Total-Count');
                                _self.total_pages = headers('X-Pagination-Page-Count');
                            });
                        },
                        isLast: function() {
                            return this.total_pages <= this.page;
                        }
                    }
                    return _.extend(pagination, options);
                }
            }

        }])
        .factory('validateInterceptor', ['$q', 'validateForms', 'topAlert', function($q, validateForms, topAlert) {
            return {
                'responseError': function(response) {
                    var form_name = response.config.method + response.config.url;
                    if (/[a-zA-Z0-9]{32}$/.test(form_name))
                        form_name = form_name.slice(0, -33);
                    if (response.status == 422 && validateForms.forms.hasOwnProperty(form_name)) {
                        var current_form = validateForms.forms[form_name];
                        _.each(current_form, function(field, key) {
                            var err_item;
                            if (key.substr(0, 1) != '$' && typeof(current_form[key]) != "function") {
                                current_form[key].$setValidity('server', true);
                                current_form[key].server_error = "";
                            }
                        })

                        var setErrors = function(error, prefix) {
                            _.each(error, function(value, key, list) {
                                if (/^\d+$/.test(key) && prefix == '')
                                    prefix = 'parent_'
                                if (_.isObject(value))
                                    setErrors(value, prefix + key + '_');
                                else {
                                    var current_key = prefix + key;
                                    if (!angular.isUndefined(current_form[current_key])) {
                                        current_form[current_key].$setValidity('server', false);
                                        current_form[current_key].server_error = value;
                                    } else {
                                        console.log(current_key);
                                        //TODO 返回来的错误在form中找不到对应的元素，应放在全局错误提示里
                                    }
                                    topAlert.warning(value);
                                }
                            })
                            return error;
                        }
                        setErrors(response.data, '');
                    }
                    return $q.reject(response);
                }
            }
        }])
        .factory('errorInterceptor', ['$q', '$rootScope', 'globalConfig', '$window', function($q, $rootScope, globalConfig, $window) {
            var showError = function(msg) {
                var error = $('<div class="modal"><div class="toast"><div class="obtain">' + msg + '</div></div></div>');
                $('body').append(error);
                setTimeout(function() {
                    error.remove();
                }, 2000)
            }
            return {
                'responseError': function(response) {
                    if (response.data.status == 400 || response.data.status == 403) {
                        showError(response.data.message);
                    }
                    return $q.reject(response);
                }
            }
        }])
        .service('validateForms', [function() {
            this.forms = [];
        }])
        .service('user', function() {
            this.id;
            this.checkAuth = function() {
                return this.id ? true : false;
            }
        })
        .service('userManager', ['commonUserApi', '$q', '$cookies', 'globalFunction', 'globalConfig', '$location', 'user',
            function(commonUserApi, $q, $cookies, globalFunction, globalConfig, $location, user) {
                var _self = this;
                this.restorageUserInfo = function(data) {
                    if (data)
                        _.extend(user, data);
                    else if (sessionStorage.user)
                        _.extend(user, JSON.parse(sessionStorage.user));
                    else if ($cookies.user) {
                        sessionStorage.setItem('user', $cookies.user);
                        sessionStorage.setItem("token", $cookies.user.token);
                        _.extend(user, $cookies.user);
                    }

                }
                this.auth = function(user_info) {
                    var deferred = $q.defer();
                    commonUserApi.auth(user_info).$promise.then(function(response) {
                        _self.restorageUserInfo(response);
                        sessionStorage.setItem('user', JSON.stringify(response));
                        $cookies.user = response;
                        sessionStorage.setItem("token", response.token);
                        deferred.resolve();
                    }, function(response) {
                        deferred.reject();
                    });
                    return deferred.promise;
                }
                this.gotoAuth = function(corp_id) {
                    var oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
                    oauth_url += corp_id;
                    oauth_url += '&redirect_uri=';
                    oauth_url += encodeURIComponent($location.absUrl());
                    oauth_url += '&response_type=code&scope=snsapi_base&state=#wechat_redirect';
                    location.href = oauth_url;
                }
                this.gotoqyAuth = function(corp_id) {
                    var oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
                    oauth_url += corp_id;
                    oauth_url += '&redirect_uri=';
                    oauth_url += encodeURIComponent($location.absUrl());
                    oauth_url += '&response_type=code&scope=SCOPE&state=STATE#wechat_redirect';
                    location.href = oauth_url;
                }
                this.qyauth = function(user_info) {
                    var deferred = $q.defer();
                    commonUserApi.qyauth(user_info).$promise.then(function(response) {
                        _self.restorageUserInfo(response);
                        sessionStorage.setItem('user', JSON.stringify(response));
                        $cookies.user = response;
                        sessionStorage.setItem("token", response.token);
                        localStorage.setItem('user', JSON.stringify(response));
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('curTime', new Date().getTime());
                        deferred.resolve();
                    }, function(response) {
                        deferred.reject();
                    });
                    return deferred.promise;
                }
                this.login = function(userid) {
                        var deferred = $q.defer();
                        commonUserApi.login(userid).$promise.then(function(response) {
                            _self.restorageUserInfo(response);
                            sessionStorage.setItem('user', JSON.stringify(response));
                            $cookies.user = response;
                            sessionStorage.setItem("token", response.token);
                            deferred.resolve();
                        }, function(response) {
                            deferred.reject();
                        });
                        return deferred.promise;
                    }
                    /*this.login = function(){
                        var login_url = 'http://devqyftapi.snsshop.net/common/user/login?userid=452';
                    }*/
            }
        ])
        .factory('modalExtension', ['ModalService', '$q', function(ModalService, $q) {
            return {
                alert: function(msg, btnConfirmText) {
                    var deferred;
                    deferred = $q.defer();
                    ModalService.showModal({
                        templateUrl: "../../views/alert.html",
                        controller: ['$scope', 'close', 'msg', 'btnConfirmText', function($scope, close, msg, btnConfirmText) {
                            $scope.msg = msg;
                            $scope.btnConfirmText = btnConfirmText;
                            $scope.close = function() {
                                close(null, 0);
                            }
                            $scope.$on('$routeChangeStart', function(e) {
                                $scope.close();
                            })
                        }],
                        inputs: {
                            "msg": msg,
                            "btnConfirmText": btnConfirmText
                        }
                    }).then(function(modal) {
                        modal.close.then(function(result) {
                            deferred.resolve();
                        });
                    });
                    return deferred.promise;
                },
                confirm: function(msg) {
                    var deferred;
                    deferred = $q.defer();
                    ModalService.showModal({
                        templateUrl: "../../views/confirm.html",
                        controller: ['$scope', 'close', 'msg', function($scope, close, msg) {
                            $scope.msg = msg;
                            $scope.close = function() {
                                    close(null, 0);
                                },
                                $scope.close1 = function() {
                                    $('.modal').remove();
                                },
                                $scope.$on('$routeChangeStart', function(e) {
                                    $scope.close();
                                })
                        }],
                        inputs: {
                            "msg": msg
                        }
                    }).then(function(modal) {
                        modal.close.then(function(result) {
                            deferred.resolve();
                        });
                    });
                    return deferred.promise;
                },
                loading: function(msg) {
                    var result = {
                        fun: {},
                        close: function() {
                            this.fun(null, 0);
                        }
                    }
                    ModalService.showModal({
                        templateUrl: "../../views/loading.html",
                        controller: ['$scope', 'close', 'msg', 'result', function($scope, close, msg, result) {
                            $scope.msg = msg;
                            result.fun = close;
                            $scope.$on('$routeChangeStart', function(e) {
                                close(null, 0);
                            })
                        }],
                        inputs: {
                            "msg": msg,
                            "result": result
                        }
                    });
                    return result;
                },
                tips: function(msg) {
                    var result = {
                        fun: {},
                        close: function() {
                            this.fun(null, 0);
                        }
                    }
                    ModalService.showModal({
                        templateUrl: "../../views/tips.html",
                        controller: ['$scope', '$timeout', 'close', 'msg', function($scope, $timeout, close, msg) {
                            $scope.msg = msg;
                            $timeout(function() {
                                close(null, 0);
                            }, 1500);
                        }],
                        inputs: {
                            "msg": msg
                        }
                    });
                    return result;
                },
                error: function(msg) {
                    ModalService.showModal({
                        templateUrl: "../../views/error.html",
                        controller: ['$scope', '$timeout', 'close', 'msg', function($scope, $timeout, close, msg) {
                            $scope.msg = msg;
                            $timeout(function() {
                                close(null, 0);
                            }, 2000)
                            $scope.$on('$routeChangeStart', function(e) {
                                close(null, 0);
                            })
                        }],
                        inputs: {
                            "msg": msg
                        }
                    });
                }
            }
        }])
        .factory('weChat', ['$http', '$q', '$timeout', 'weChatConfig', 'globalConfig', function($http, $q, $timeout, weChatConfig, globalConfig) {
            var deferred = $q.defer();
            //标识微信JS接口状态，wx.ready执行后会变成true
            var is_ready = false;
            //判断是企业号还是企业微信
            var sUserAgent = navigator.userAgent.toLowerCase();
            var wxwork = sUserAgent.match(/wxwork/i) == "wxwork";
            if (wxwork) {
                //获取企业微信JS-SDK使用权限签名
                $http.get(globalConfig.apiUrl + weChatConfig.qy_apiUrl + "?app_code=" + globalConfig.moduleCode + "&PHPSESSID=" + sessionStorage.token).then(function(response) {
                    var config = response.data;
                    config.debug = globalConfig.webChatDebug;
                    config.jsApiList = weChatConfig.jsApiList;
                    wx.config(config);
                });
            } else {
                //获取JS-SDK使用权限签名
                $http.get(globalConfig.apiUrl + weChatConfig.apiUrl + "?app_code=" + globalConfig.moduleCode + "&PHPSESSID=" + sessionStorage.token).then(function(response) {
                    var config = response.data;
                    config.debug = globalConfig.webChatDebug;
                    config.jsApiList = weChatConfig.jsApiList;
                    wx.config(config);
                });
            }
            wx.ready(function() {
                is_ready = true;
                deferred.resolve();
            });
            wx.error(function(err) {
                deferred.reject(err);
            });
            /*
             * 检查微信API是否是ready状态
             * 调用微信JS接口时，会检查是否已ready，如果还没有ready，则等到ready后再执行相应JS接口
             * */
            var checkReady = function() {
                    var ready_deferred = $q.defer();
                    if (is_ready == true) {
                        setTimeout(function() {
                            ready_deferred.resolve();
                        })

                    } else {
                        deferred.promise.then(function(res) {
                            ready_deferred.resolve(res);
                        }, function(err) {
                            ready_deferred.reject(err);
                        })
                    }
                    return ready_deferred.promise;
                }
                /*
                 * 执行微信JS接口的统一入口
                 * 该函数主要实现两个功能
                 * 1.统一使用checkReady函数判断是否需要延时执行
                 * 2.统一将微信的api的success和fail参数转化为$q的promise模式
                 * */
            var callJSApi = function(name, config) {
                var deferred = $q.defer();
                checkReady().then(function() {
                    wx[name](angular.extend({}, config, {
                        success: function(res) {
                            deferred.resolve(res);
                            if (!angular.isUndefined(config.success))
                                config.success(res);
                        },
                        fail: function(err) {
                            deferred.reject(err);
                            if (!angular.isUndefined(config.fail))
                                config.success(err);
                        }
                    }));
                }, function(err) {
                    deferred.reject(err);
                });
                return deferred.promise;
            }
            return {
                get readyPromise() {
                    return deferred.promise;
                },
                get isReady() {
                    return is_ready;
                },
                onMenuShareTimeline: function(config) {
                    return callJSApi('onMenuShareTimeline', config)
                },
                onMenuShareAppMessage: function(config) {
                    return callJSApi('onMenuShareAppMessage', config)
                },
                onMenuShareQQ: function(config) {
                    return callJSApi('onMenuShareQQ', config)
                },
                onMenuShareWeibo: function(config) {
                    return callJSApi('onMenuShareWeibo', config)
                },
                onMenuShareQZone: function(config) {
                    return callJSApi('onMenuShareQZone', config)
                },
                startRecord: function(config) {
                    return callJSApi('startRecord', config)
                },
                stopRecord: function(config) {
                    return callJSApi('stopRecord', config)
                },
                onVoiceRecordEnd: function(config) {
                    return callJSApi('onVoiceRecordEnd', config)
                },
                playVoice: function(config) {
                    return callJSApi('playVoice', config)
                },
                pauseVoice: function(config) {
                    return callJSApi('pauseVoice', config)
                },
                stopVoice: function(config) {
                    return callJSApi('stopVoice', config)
                },
                onVoicePlayEnd: function(config) {
                    return callJSApi('onVoicePlayEnd', config)
                },
                uploadVoice: function(config) {
                    return callJSApi('uploadVoice', config)
                },
                downloadVoice: function(config) {
                    return callJSApi('downloadVoice', config)
                },
                chooseImage: function(config) {
                    return callJSApi('chooseImage', config)
                },
                previewImage: function(config) {
                    return callJSApi('previewImage', config)
                },
                uploadImage: function(config) {
                    return callJSApi('uploadImage', config)
                },
                downloadImage: function(config) {
                    return callJSApi('downloadImage', config)
                },
                translateVoice: function(config) {
                    return callJSApi('translateVoice', config)
                },
                getNetworkType: function(config) {
                    return callJSApi('getNetworkType', config)
                },
                openLocation: function(config) {
                    return callJSApi('openLocation', config)
                },
                getLocation: function(config) {
                    return callJSApi('getLocation', config)
                },
                hideOptionMenu: function(config) {
                    return callJSApi('hideOptionMenu', config)
                },
                showOptionMenu: function(config) {
                    return callJSApi('showOptionMenu', config)
                },
                hideMenuItems: function(config) {
                    return callJSApi('hideMenuItems', config)
                },
                showMenuItems: function(config) {
                    return callJSApi('showMenuItems', config)
                },
                hideAllNonBaseMenuItem: function(config) {
                    return callJSApi('hideAllNonBaseMenuItem', config)
                },
                showAllNonBaseMenuItem: function(config) {
                    return callJSApi('showAllNonBaseMenuItem', config)
                },
                closeWindow: function(config) {
                    return callJSApi('closeWindow', config)
                },
                scanQRCode: function(config) {
                    return callJSApi('scanQRCode', config)
                }
            }
        }])
        .factory('ModalService', ['$animate', '$document', '$compile', '$controller', '$http', '$rootScope', '$q', '$templateRequest', '$timeout',
            function($animate, $document, $compile, $controller, $http, $rootScope, $q, $templateRequest, $timeout) {

                //  Get the body of the document, we'll add the modal to this.
                var body = $document.find('body');

                function ModalService() {

                    var self = this;

                    //  Returns a promise which gets the template, either
                    //  from the template parameter or via a request to the
                    //  template url parameter.
                    var getTemplate = function(template, templateUrl) {
                        var deferred = $q.defer();
                        if (template) {
                            deferred.resolve(template);
                        } else if (templateUrl) {
                            $templateRequest(templateUrl, true)
                                .then(function(template) {
                                    deferred.resolve(template);
                                }, function(error) {
                                    deferred.reject(error);
                                });
                        } else {
                            deferred.reject("No template or templateUrl has been specified.");
                        }
                        return deferred.promise;
                    };

                    //  Adds an element to the DOM as the last child of its container
                    //  like append, but uses $animate to handle animations. Returns a
                    //  promise that is resolved once all animation is complete.
                    var appendChild = function(parent, child) {
                        var children = parent.children();
                        if (children.length > 0) {
                            return $animate.enter(child, parent, children[children.length - 1]);
                        }
                        return $animate.enter(child, parent);
                    };

                    self.showModal = function(options) {

                        //  Create a deferred we'll resolve when the modal is ready.
                        var deferred = $q.defer();

                        //  Validate the input parameters.
                        var controllerName = options.controller;
                        if (!controllerName) {
                            deferred.reject("No controller has been specified.");
                            return deferred.promise;
                        }

                        //  Get the actual html of the template.
                        getTemplate(options.template, options.templateUrl)
                            .then(function(template) {

                                //  Create a new scope for the modal.
                                var modalScope = $rootScope.$new();

                                //  Create the inputs object to the controller - this will include
                                //  the scope, as well as all inputs provided.
                                //  We will also create a deferred that is resolved with a provided
                                //  close function. The controller can then call 'close(result)'.
                                //  The controller can also provide a delay for closing - this is
                                //  helpful if there are closing animations which must finish first.
                                var closeDeferred = $q.defer();
                                var closedDeferred = $q.defer();
                                var inputs = {
                                    $scope: modalScope,
                                    close: function(result, delay) {
                                        if (delay === undefined || delay === null) delay = 0;
                                        $timeout(function() {
                                            //  Resolve the 'close' promise.
                                            closeDeferred.resolve(result);

                                            //  Let angular remove the element and wait for animations to finish.
                                            $animate.leave(modalElement)
                                                .then(function() {
                                                    //  Resolve the 'closed' promise.
                                                    closedDeferred.resolve(result);

                                                    //  We can now clean up the scope
                                                    modalScope.$destroy();

                                                    //  Unless we null out all of these objects we seem to suffer
                                                    //  from memory leaks, if anyone can explain why then I'd
                                                    //  be very interested to know.
                                                    inputs.close = null;
                                                    deferred = null;
                                                    closeDeferred = null;
                                                    modal = null;
                                                    inputs = null;
                                                    modalElement = null;
                                                    modalScope = null;
                                                });
                                        }, delay);
                                    }
                                };

                                //  If we have provided any inputs, pass them to the controller.
                                if (options.inputs) angular.extend(inputs, options.inputs);

                                //  Compile then link the template element, building the actual element.
                                //  Set the $element on the inputs so that it can be injected if required.
                                var linkFn = $compile(template);
                                var modalElement = linkFn(modalScope);
                                inputs.$element = modalElement;

                                //  Create the controller, explicitly specifying the scope to use.
                                var modalController = $controller(options.controller, inputs);

                                if (options.controllerAs) {
                                    modalScope[options.controllerAs] = modalController;
                                }
                                //  Finally, append the modal to the dom.
                                if (options.appendElement) {
                                    // append to custom append element
                                    appendChild(options.appendElement, modalElement);
                                } else {
                                    // append to body when no custom append element is specified
                                    appendChild(body, modalElement);
                                }

                                //  We now have a modal object...
                                var modal = {
                                    controller: modalController,
                                    scope: modalScope,
                                    element: modalElement,
                                    close: closeDeferred.promise,
                                    closed: closedDeferred.promise
                                };

                                //  ...which is passed to the caller via the promise.
                                deferred.resolve(modal);

                            })
                            .then(null, function(error) { // 'catch' doesn't work in IE8.
                                deferred.reject(error);
                            });

                        return deferred.promise;
                    };

                }

                return new ModalService();
            }
        ])
}).call(this);

;
(function() {
  angular.module('app.directives.ui', [])
      //页面切换过度动画
      .directive('pageLoading',[
          function(){
              return {
                  restrict:"A",
                  scope: {loading:"=",openLoading:"="},
                  replace:true,
                  template:'<div ng-show="loading || openLoading" class="app-content-loading"></div>',
                  link: function(scope, ele, attrs) {
                      scope.$on('$routeChangeStart', function(){
                          scope.loading = true;
                      });
                      scope.$on('$routeChangeSuccess', function(){
                          scope.loading = false;
                      });
                  }
              }

          }])
      .directive('msDate',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().date({
                          dateFormat:'yy-mm-dd',
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msDateTime',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().datetime({
                          dateFormat:'yy-mm-dd',
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msTime',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().time({
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msSelect',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      setTimeout(function(){
                          $(ele).mobiscroll().select({
                              display:'bottom',
                              lang:'zh',
                              multiple:attrs.multiple,
                              placeholder:attrs.placeholder,
                              onSelect:function(value,inst){
                                  modelCtrl.$setViewValue(inst._tempValue);
                                  scope.$apply();
                              }
                          });
                      },10)
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.controllers.auth',[])
        .controller('authController',['$rootScope', '$scope','$location','$route','userManager','user','globalConfig',function($rootScope,$scope,$location,$route,userManager,user,globalConfig){
            $rootScope.openLoading = true;
            var params = $location.search();
            // console.log(params);
            userManager.restorageUserInfo();
            if(user.checkAuth()){
                $rootScope.openLoading = false;
                $location.path(decodeURIComponent(params.url));
            }else {
                //var params = $location.search();
                // console.log(globalConfig.debug);
                // return;
                /*userManager.auth({
                    "corp_id": params.state,
                    "app_code": globalConfig.moduleCode,
                    "code": params.code
                }).then(function (data) {
                    $rootScope.openLoading = false;
                    $location.path(decodeURIComponent(params.url));
                })*/
                var result;
                //console.log('id',params);
                if (globalConfig.debug == true) {
                    result = userManager.login({
                        "userid": params.id
                    });                 
                } else {
                    if (!params.code) {
                        userManager.gotoAuth(params.corp_id);
                        return;
                    }
                    result = userManager.auth({
                        "corp_id": params.state,
                        "app_code": globalConfig.moduleCode,
                        "code": params.code
                    });
                }
                result.then(function (data) {                    
                    $rootScope.openLoading = false;
                    $location.path(decodeURIComponent(params.url));
                });
            }
        }])
        .controller('qyauthController', ['$rootScope', '$scope', '$location','$window', '$route', 'userManager', 'user', 'globalConfig', function($rootScope, $scope, $location,$window, $route, userManager, user, globalConfig) {
            // $rootScope.openLoading = true;
            var params = $location.search();
            userManager.restorageUserInfo();
            //判断移动还是pc
            $scope.browserRedirect = function() {
                var sUserAgent = navigator.userAgent.toLowerCase();
                var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
                var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
                var bIsMidp = sUserAgent.match(/midp/i) == "midp";
                var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
                var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
                var bIsAndroid = sUserAgent.match(/android/i) == "android";
                var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
                var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
                var bIsWwechat = sUserAgent.match(/windowswechat/i) == "windowswechat";
                if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
                    //移动端企业微信
                    $location.path(decodeURIComponent(params.url));
                } else if (bIsWwechat) {
                    //pc企业微信
                    $window.location.href = globalConfig.url+'/pc/#/'+decodeURIComponent(params.pc_url);
                    // location.href = 'http://qy.vikduo.com/pc/#/index/conference/list';
                } else {
                    //pc其他浏览器
                    $scope.aa = 'pc浏览器';
                }
            };

            if (user.checkAuth()) {
                $rootScope.openLoading = false;
                $scope.browserRedirect();
            } else {
                var result;

                // $scope.bb = globalConfig.corpID + '    '+ globalConfig.moduleCode;

                if (!params.code) {
                    userManager.gotoqyAuth(globalConfig.corpID);
                    return;
                }

                result = userManager.qyauth({
                    "auth_code": params.code,
                    "app_code": globalConfig.moduleCode
                });
                result.then(function(data) {
                    $rootScope.openLoading = false;
                    $scope.browserRedirect();
                });
            }
        }])
}).call(this);

