(function () {
  'use strict';

  angular
    .module('pincodeApp', [
      'ngAnimate',
      'ngCookies',
      'ngResource',
      'ui.router',
      'ngSanitize',
      'ngTouch',
      'daterangepicker',
      'highcharts-ng',
      'ui.tinymce',
      'pincodeApp.services.function',
      'pincodeApp.controller.auth',
      'pincodeApp.controller.topbar',
      'pincodeApp.controller.leftbar',
      'pincodeApp.controller.home',
      'pincodeApp.controller.activityList',
      'pincodeApp.controller.codeCheck',
      'pincodeApp.controller.activityRecord',
      'pincodeApp.controller.securityList',
      'pincodeApp.controller.securityRecord',
      'pincodeApp.controller.securityCreate',
      'pincodeApp.controller.rootsList',
      'pincodeApp.controller.rootsRecord',
      'pincodeApp.services.function',
      'pincodeApp.controller.prizeRecord',
      'pincodeApp.controller.code',
      'pincodeApp.controller.log',
      'pincodeApp.controller.codeAdministrate',
      'pincodeApp.controller.statisActivity',
      'pincodeApp.controller.statisSecurity',
      'pincodeApp.controller.statisRoots',
      'pincodeApp.controller.actiTemplate',
      'pincodeApp.controller.newActiTemplate',
      'pincodeApp.controller.rootsTemplate',
      'pincodeApp.controller.newRootsTemplate'
    ])
    .config(function ($urlRouterProvider, $stateProvider, $locationProvider) {
      $locationProvider.hashPrefix('');
      $urlRouterProvider.otherwise("/");
      $stateProvider
        .state('login', {
          url: '/login',
          views: {
            'indexView': {
              templateUrl: 'views/login.html',
              controller: 'authCtrl'
            }
          },
          data: {
            title: '登录'
          }
        })
        .state('index', {
          url: '/',
          views: {
            'indexView': {
              templateUrl: 'views/main.html'
            },
            'topbar@index': {
              templateUrl: 'views/public/topbar.html',
              controller: 'topbarCtrl'
            },
            'leftbar@index': {
              templateUrl: 'views/public/leftbar.html',
              controller: 'leftbarCtrl'
            },
            'content@index': {
              templateUrl: 'views/home.html',
              controller: 'homeCtrl'
            }
          },
          data: {
            title: '总览'
          }
        })
        .state('index.activityList', {
          url: 'activityList',
          views: {
            'content@index': {
              templateUrl: 'views/draw/activity_list.html',
              controller: 'activityListCtrl'
            }
          },
          data: {
            title: '活动码策略'
          }
        }).state('index.activityRecord', {
          url: 'activityRecord',
          views: {
            'content@index': {
              templateUrl: 'views/draw/activity-record.html',
              controller: 'activityRecordCtrl'
            }
          },
          data: {
            title: '扫码记录'
          }
        })
        .state('index.securityList', {
          url: 'securityList',
          views: {
            'content@index': {
              templateUrl: 'views/draw/security-list.html',
              controller: 'securityListCtrl'
            }
          },
          data: {
            title: '防伪码策略'
          }
        }).state('index.securityCreate', {
        url: 'securityCreate',
        views: {
          'content@index': {
            templateUrl: 'views/draw/security-create.html',
            controller: 'securityCreateCtrl'
          }
        },
        data: {
          title: '创建策略码'
        }
      }).state('index.securityRecord', {
        url: 'securityRecord',
        views: {
          'content@index': {
            templateUrl: 'views/draw/security-record.html',
            controller: 'securityRecordCtrl'
          }
        },
        data: {
          title: '扫码记录'
        }
      }).state('index.rootsList', {
        url: 'rootsList',
        views: {
          'content@index': {
            templateUrl: 'views/draw/roots-List.html',
            controller: 'rootsListCtrl'
          }
        },
        data: {
          title: '溯源码策略'
        }
      }).state('index.rootsRecord', {
          url: 'rootsRecord',
          views: {
            'content@index': {
              templateUrl: 'views/draw/roots-record.html',
              controller: 'rootsRecordCtrl'
            }
          },
          data: {
            title: '扫码记录'
          }
        }).state('index.code', {
          url: 'code',
          views: {
            'content@index': {
              templateUrl: 'views/recharge/code.html',
              controller: 'codeCtrl'
            }
          },
          data: {
            title: '充值券充值'
          }
        }).state('index.log', {
          url: 'log',
          views: {
            'content@index': {
              templateUrl: 'views/recharge/log.html',
              controller: 'logCtrl'
            }
          },
          data: {
            title: '充值记录'
          }
        }).state('index.checkLog', {
          url: 'checkLog',
          views: {
            'content@index': {
              templateUrl: 'views/recharge/check-log.html',
              controller: 'checkLogCtrl'
            }
          },
          data: {
            title: '积分记录'
          }
        }).state('index.prizeRecord', {
          url: 'prizeRecord',
          views: {
            'content@index': {
              templateUrl: 'views/draw/prize-record.html',
              controller: 'prizeRecordCtrl'
            }
          },
          data: {
            title: '中奖记录'
          }
        }).state('index.codeAdministrate', {
          url: 'codeAdministrate',
          views: {
            'content@index': {
              templateUrl: 'views/draw/code-administrate.html',
              controller: 'codeAdministrateCtrl'
            }
          },
          data: {
            title: '中奖记录'
          }
        })
        .state('index.codeCheck', {
          url: 'codeCheck',
          views: {
            'content@index': {
              templateUrl: 'views/analyse/code_check.html',
              controller: 'codeCheckCtrl'
            }
          },
          data: {
            title: '扫码概况'
          }
        })
        .state('index.statisActivity', {
          url: 'statisActivity',
          views: {
            'content@index': {
              templateUrl: 'views/analyse/statis_activity.html',
              controller: 'statisActivityCtrl'
            }
          },
          data: {
            title: '活动码统计'
          }
        })
        .state('index.statisSecurity', {
          url: 'statisSecurity',
          views: {
            'content@index': {
              templateUrl: 'views/analyse/statis_security.html',
              controller: 'statisSecurityCtrl'
            }
          },
          data: {
            title: '防伪码统计'
          }
        })
        .state('index.statisRoots', {
          url: 'statisRoots',
          views: {
            'content@index': {
              templateUrl: 'views/analyse/statis_roots.html',
              controller: 'statisRootsCtrl'
            }
          },
          data: {
            title: '溯源码统计'
          }
        })
        .state('index.actiTemplate', {
          url: 'actiTemplate',
          views: {
            'content@index': {
              templateUrl: 'views/draw/activity_template.html',
              controller: 'actiTemplateCtrl'
            }
          },
          data: {
            title: '活动模板'
          }
        })
        .state('index.newActiTemplate', {
          url: 'newActiTemplate',
          views: {
            'content@index': {
              templateUrl: 'views/draw/new_acti_template.html',
              controller: 'newActiTemplateCtrl'
            }
          },
          data: {
            title: '创建活动模板'
          }
        })
        .state('index.rootsTemplate', {
          url: 'rootsTemplate',
          views: {
            'content@index': {
              templateUrl: 'views/roots/roots_template.html',
              controller: 'rootsTemplateCtrl'
            }
          },
          data: {
            title: '溯源模板'
          }
        })
        .state('index.newRootsTemplate', {
          url: 'newRootsTemplate',
          views: {
            'content@index': {
              templateUrl: 'views/roots/new_roots_template.html',
              controller: 'newRootsTemplateCtrl'
            }
          },
          data: {
            title: '创建溯源模板'
          }
        });
    })
    .run(['$rootScope', '$window', '$location', function ($rootScope, $window, $location) {
      $rootScope.$on('$stateChangeStart', function (event, toState) {
        $rootScope.$state = toState;
        $window.document.title = toState.data.title;

        var currentPath = $location.path();
        angular.forEach($rootScope.menu, function (item) {
          item.isActive = false;
          angular.forEach(item.submenu, function (itemInner) {
            itemInner.isActive = false;
            if (itemInner.url === currentPath) {
              itemInner.isActive = true;
              item.isActive = true;
            }
          })
        });
      });

    }]);

})();