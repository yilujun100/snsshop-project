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
