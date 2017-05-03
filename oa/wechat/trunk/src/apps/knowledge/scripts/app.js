// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('knowledge', [
    'ngRoute',
    'ngCookies',
    'ngAnimate',
    'ngTouch',
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

    'knowledge.configurations',
    'knowledge.controllers',
    'knowledge.resource'

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
            templateUrl: 'views/knowledge_lib.html',
            controller:'knowledgeIndexController'
        })
        .when('/my-collection',{
            templateUrl: 'views/my_collection.html',
            controller:'myCollectionController'
        })
        .when('/article/detail/:id',{
            templateUrl: 'views/article_detail.html',
            controller:'articleDetailController'
        })
        .when('/comment-list/:id',{
            templateUrl: 'views/comment.html',
            controller:'commentController'
        })
        .when('/personal-center',{
            templateUrl: 'views/personal_center.html',
            controller:'personalCenterController'
        })
        .when('/my-comments',{
            templateUrl: 'views/my_comments.html',
            controller:'myCommentController'
        })
        .when('/my-admire',{
            templateUrl: 'views/my_admire.html',
            controller:'myAdmireController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/search-result/:keyword',{
            templateUrl: 'views/search_result.html',
            controller:'searchController'
        })
        .when('/search-tag-result/:tagId',{
            templateUrl: 'views/search_result_tag.html',
            controller:'searchTagController'            
        })
        .when('/search/detail/:id',{
            templateUrl: 'views/article_detail.html',
            controller:'searchDetailController'
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
}]);