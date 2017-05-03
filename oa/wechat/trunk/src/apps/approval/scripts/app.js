// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('approval', [
    'ngRoute',
    'ngCookies',
    'ngAnimate',
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

    'approval.configurations',
    'approval.controllers',
    'approval.resource'

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
        .when('/flow/list',{
            templateUrl: 'views/flow-list.html',
            controller:'flowListController'
        })
        .when('/apply/list',{
            templateUrl: 'views/apply-list.html',
            controller:'applyListController',
            expand:{}
        })
        .when('/approval/list',{
            templateUrl: 'views/approval-list.html',
            controller:'approvalListController',
            expand:{}
        })
        .when('/record/create/:flow_id/:department_id',{
            templateUrl: 'views/record-form.html',
            controller:'recordCreateController'
        })
        .when('/record/create/:flow_id',{
            templateUrl: 'views/record-form.html',
            controller:'recordCreateController'
        })
        .when('/record/update/:id',{
            templateUrl: 'views/record-form.html',
            controller:'recordUpdateController'
        })
        .when('/record/detail/:id',{
            templateUrl: 'views/record-detail.html',
            controller:'recordDetailController'
        })
        .when('/extra',{
            templateUrl: 'views/flex-table.html',
            controller:'extraController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/qy_auth',{
            templateUrl: '../../views/auth.html',
            controller:'qyauthController'
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
}]);

app.config(function(weChatConfig) {
    weChatConfig.jsApiList = ['chooseImage','previewImage','uploadImage','downloadImage']
});