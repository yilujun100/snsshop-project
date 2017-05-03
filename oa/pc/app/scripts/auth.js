(function() {
    'use strict';
    angular.module('app.controllers.auth', [])
        .constant('apiUrl', 'http://devqyftapi.snsshop.net') //测试http://devqyftapi.snsshop.net 正式 http://qyftapi.vikduo.com
        .constant('ftUrl', 'http://devqy.snsshop.net') //测试http://devqy.snsshop.net 正式http://qy.vikduo.com
        .controller('authController', ['$scope', '$location', '$state', 'auth', function($scope, $location, $state, auth) {
            var auth_code = $location.search().auth_code,
                currentUrl = $location.search().currentUrl;
            auth.doRequest(auth_code, currentUrl);
        }])
        .controller('auth1Controller', ['$scope', '$state', 'auth1', function($scope,$state ,auth1) {
            $scope.user_id = null;
            $scope.loginFn = function(user_id) {
                if (user_id) {
                    auth1.doRequest(user_id).success(function(data) {
                        localStorage.setItem('user', JSON.stringify(data));
                        localStorage.setItem('token', data.token);
                        localStorage.setItem('curTime', new Date().getTime());
                        $state.go('index.portal');
                        // sessionStorage.setItem('user', JSON.stringify(data));
                        // sessionStorage.setItem('token', data.token);
                    })
                }
                else{
                    alert('请输入id');
                }

            }

        }])
        .factory('auth', ['$http', '$state', 'apiUrl', 'ftUrl', function($http, $state, apiUrl, ftUrl) {
            // var signed = false;
            return {
                doRequest: function(auth_code, currentUrl) {
                    $http({
                        method: 'GET',
                        url: apiUrl + '/common/user/wx-login?auth_code=' + auth_code
                    }).success(function(data) {
                        // console.log(data);
                        localStorage.setItem('user', JSON.stringify(data));
                        localStorage.setItem('token', data.token);
                        localStorage.setItem('curTime', new Date().getTime());
                        $state.go(currentUrl);
                    }).error(function() {
                        window.location.href = 'https://qy.weixin.qq.com/cgi-bin/loginpage?corp_id=wx6b6b870bdac0911d&usertype=member&redirect_uri=' + encodeURIComponent(ftUrl + '/pc/#/login?currentUrl=' + currentUrl);
                    });
                },
                checkToken: function(currentUrl) {
                    if (!localStorage['token']) {
                        window.location.href = 'https://qy.weixin.qq.com/cgi-bin/loginpage?corp_id=wx6b6b870bdac0911d&usertype=member&redirect_uri=' + encodeURIComponent(ftUrl + '/pc/#/login?currentUrl=' + currentUrl);
                        // return true;
                    } else {
                        return true;
                    }
                }
            }
        }])
        .factory('auth1', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function(id) {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/common/user/login?userid='+ id
                    })
                }

            }
        }])
        .factory('logout', ['$http', 'apiUrl', function($http, apiUrl) {
            return {
                doRequest: function() {
                    return $http({
                        method: 'GET',
                        url: apiUrl + '/common/user/logout'
                    })
                }

            }
        }])
}).call(this);
