// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('bbs', [
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

    'bbs.configurations',
    'bbs.controllers',
    'bbs.resource'

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
            templateUrl: 'views/bbs_index.html',
            controller:'bbsIndexController'
        })
        .when('/personal-center',{
            templateUrl: 'views/personal_center.html',
            controller:'personalCenterController'
        })
        .when('/score-detail',{
            templateUrl: 'views/score_detail.html',
            controller:'scoreDetailController'
        })
        .when('/circle',{
            templateUrl: 'views/circle.html',
            controller:'circleController'
        })
        .when('/circle-index',{
            templateUrl: 'views/circle_index.html',
            controller:'circleIndexController'
        })
        .when('/circle-rejoin',{
            templateUrl: 'views/circle_rejoin.html',
            controller:'circleRejoinController'
        })
        .when('/article-detail/:id',{
            templateUrl: 'views/article_detail.html',
            controller:'articleDetailController'
        })
        .when('/my-post',{
            templateUrl: 'views/my_post.html',
            controller:'myPostController'
        })
        .when('/my-reply',{
            templateUrl: 'views/my_reply.html',
            controller:'myReplyController'
        })
        .when('/my-collect',{
            templateUrl: 'views/my_collect.html',
            controller:'myCollectController'
        })
        .when('/my-circle',{
            templateUrl: 'views/my_circle.html',
            controller:'myCircleController'
        })
        .when('/post',{
            templateUrl: 'views/post.html',
            controller:'postController'
        })        
        .when('/search-result/:keyword',{
            templateUrl: 'views/search_result.html',
            controller:'searchController'
        })
        .when('/post-edit/:postId',{
            templateUrl: 'views/post_edit.html',
            controller:'postEditController'
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

app.config(function(weChatConfig){
    weChatConfig.jsApiList = ['onMenuShareTimeline','onMenuShareAppMessage']
});

app.config( [ '$compileProvider',function( $compileProvider ){
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|sms):/);
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(http|wxlocalresource|weixin|data):/);
    }
]);

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}]);