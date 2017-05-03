// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('attendance', [
    'ngRoute',
    'ngCookies',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'ui.bootstrap',
    'ngAnimate',
    'angular-loading-bar',

    'app.configurations.config',
    'app.configurations.config-local',
    'app.constants.function-param',
    'app.constants.function-config',
    'app.directives.ui',
    'app.services.resource',
    'app.services.function',
    'app.controllers.auth',

    'attendance.configurations',
    'attendance.controllers',
    'attendance.resource'

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
        .when('/record/:date',{
            templateUrl: 'views/record.html',
            controller:'recordController'
        })
        .when('/sign-in',{
            templateUrl: 'views/sign.html',
            controller:'signController',
            resolve:{
                "signType":function(){return 1}
            }
        })
        .when('/sign-out',{
            templateUrl: 'views/sign.html',
            controller:'signController',
            resolve:{
                "signType":function(){return 2}
            }
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

app.config(function(weChatConfig){
    weChatConfig.jsApiList = ['getLocation']
});

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}])