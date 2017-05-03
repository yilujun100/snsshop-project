// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('portal', [
    'ngRoute',
    'ngCookies',
    'ngAnimate',
    'ngTouch',
    'angular-carousel',
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

    'portal.configurations',
    'portal.controllers',
    'portal.resource'

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
        .when('/index',{
            templateUrl: 'views/portal-index.html',
            controller:'portalIndexController'
        })
        .when('/notice/list',{
            templateUrl: 'views/notice-list.html',
            controller:'noticeListController'
        })
        .when('/notice/detail/:id',{
            templateUrl: 'views/notice-detail.html',
            controller:'noticeDetailController'
        })
        .when('/news/list',{
            templateUrl: 'views/events-list.html',
            controller:'eventsListController'
        })
        .when('/news/detail/:id',{
            templateUrl: 'views/events-detail.html',
            controller:'eventDetailController'
        })
        .when('/system/list',{
            templateUrl: 'views/system-list.html',
            controller:'systemListController'
        })
        .when('/system/detail/:id',{
            templateUrl: 'views/system-detail.html',
            controller:'systemDetailController'
        })
        .when('/new/list',{
            templateUrl: 'views/new-list.html',
            controller:'newPeopleListController'
        })
        .when('/new/intro/:id',{
            templateUrl: 'views/new-intro.html',
            controller:'newPeopleIntroController'
        })
        .when('/personal-center',{
            templateUrl: 'views/personal-center.html',
            controller:'personalCenterController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/qy_auth',{
            templateUrl: '../../views/auth.html',
            controller:'qyauthController'
        })
        .otherwise({
            redirectTo: '/index'
        });
}]);

app.config( [ '$compileProvider',function( $compileProvider ){
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|sms):/);
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(http|wxlocalresource|weixin):/);
    }
]);

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}]);;