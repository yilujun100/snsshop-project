(function() {
    'use strict';
    angular.module('szOaApp', [
            'ngAnimate',
            'ngResource',
            'ngDialog',
            'restangular',
            'ui.router',
            'ui.select',
            'ui.tree',
            'daterangepicker',
            'szOaApp.controller.main',
            'approval.resource',
            'conference.resource',
            'workorder.resource',
            'process.controller',
            'conference.controller',
            'workorder.controller',
            'changeLeftBar',
            'app.directives.ui',
            'portal.controller',
            'km.resource',
            'km.controller',
            'app.controllers.auth',
            'app.services.function',
            'portal.resource',
            'bbs.resource',
            'bbs.controller',
            'angularFileUpload'

        ])
        .config(
            function($urlRouterProvider, $stateProvider, RestangularProvider) {
                $urlRouterProvider.otherwise("/index/portal");

                RestangularProvider.setErrorInterceptor(function(response, deferred, responseHandler) {
                    if (response.status === 302) {
                        location.href = '#login';
                    }
                });

                $stateProvider
                    .state('login', {
                        url: '/login',
                        views: {
                            'indexView': {
                                templateUrl: 'views/login.html',
                                controller: 'auth1Controller'
                            }
                        },
                        data: {
                            pageTitle: '登陆'
                        }
                    })
                    .state('index', {
                        url: '/index',
                        views: {
                            'indexView': {
                                templateUrl: 'views/main.html',
                                controller: function($scope, $state, auth, ftUrl) {
                                    var exp = 7*24*60*60*1000;
                                    if (!localStorage['curTime'] || new Date().getTime() - localStorage['curTime'] > exp) {
                                        window.location.href = 'https://qy.weixin.qq.com/cgi-bin/loginpage?corp_id=wx6b6b870bdac0911d&usertype=member&redirect_uri=' + encodeURIComponent(ftUrl + '/pc/#/login?currentUrl=index.portal');
                                    }
                                    if (!localStorage['token'] || !localStorage['user']) {
                                        window.location.href = 'https://qy.weixin.qq.com/cgi-bin/loginpage?corp_id=wx6b6b870bdac0911d&usertype=member&redirect_uri=' + encodeURIComponent(ftUrl + '/pc/#/login?currentUrl=index.portal');
                                    }
                                }
                            },
                            'topbar@index': {
                                templateUrl: 'views/public/topbar.html',
                                controller: function($scope, $timeout, ftUrl, msgStatisApi,logout, msgData) {
                                    var wxLoginUrl = 'https://qy.weixin.qq.com/cgi-bin/loginpage?corp_id=wx6b6b870bdac0911d&usertype=member&redirect_uri=' + encodeURIComponent(ftUrl + '/pc/#/login?currentUrl=index.portal');
                                    $scope.wxLoginFn = function(){
                                        localStorage.clear();
                                        logout.doRequest().success(function(data){
                                            window.location.href = wxLoginUrl;
                                        });    
                                    };
                                    
                                    msgData.getMsgInfo().then(function(res) {
                                        $scope.msgNumber = res.data;
                                    });
                                }
                            },
                            'home@index': {
                                templateUrl: 'views/home.html'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/portal/leftbar.html',
                                controller: 'leftbarCtrl'
                            },
                            'footer@index': {
                                templateUrl: 'views/public/footer.html'
                            }
                        },
                        data: {
                            pageTitle: '门户首页'
                        }
                    })


                    // 流程
                    .state('index.process', {
                        url: '/process',
                        views: {
                            'home@index': {
                                templateUrl: 'views/process/index.html',
                                controller: 'processCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/process/process-leftBar.html',
                                controller: 'processleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '流程'
                        }
                    })
                    // 审批
                    .state('index.process.approve', {
                        url: '/approve',
                        views: {
                            'home@index': {
                                templateUrl: 'views/process/approve/approve.html',
                                // controller: 'approveCtrl'
                            },
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/apply-type.html',
                                controller: 'apApplyTypeCtrl'
                            }
                        }
                    })
                    .state('index.process.approve.manage', {
                        url: '/manage',
                        views: {
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/manage.html',
                                controller: 'approveManageCtrl'
                            }
                        }

                    })
                    .state('index.process.approve.record', {
                        url: '/record',
                        views: {
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/record.html',
                                controller: 'approveRecordCtrl'
                            }
                        }
                    })
                    .state('index.process.approve.apply', {
                        url: '/apply',
                        views: {
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                // templateUrl: 'views/process/approve/apply.html',
                                // controller: 'approveApplyCtrl'
                                templateUrl: 'views/process/approve/apply-type.html',
                                controller: 'apApplyTypeCtrl'
                            }
                        }
                    })
                    .state('index.process.approve.apply.form', {
                        url: '/:flow_id',
                        params:{'flow_name':null},
                        views: {
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/apply.html',
                                controller: 'approveApplyFormCtrl'
                            }
                        }
                    })
                    .state('index.process.approve.detail', {
                        url: '/detail/:detail_id',
                        views: {
                            'top-part@index.process.approve': {
                                template: '<div style="margin-top:10px;"><div>',
                                // controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/detail.html',
                                controller: 'approveDetailCtrl'
                            }
                        }
                    })
                    .state('index.process.approve.update', {
                        url: '/ap_update/:record_id',
                        views: {
                            'top-part@index.process.approve': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'apTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.approve': {
                                templateUrl: 'views/process/approve/update.html',
                                controller: 'approveUpdateCtrl'
                            }
                        }
                    })
                    // 请假
                    .state('index.process.leave', {
                        url: '/leave',
                        views: {
                            'home@index': {
                                templateUrl: 'views/process/leave/leave.html'
                                    // controller: 'approveCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'top-part@index.process.leave': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'leaveTopBarCtrl'
                            },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/apply.html',
                                controller: 'leaveApplyCtrl'
                            }
                        }
                    })
                    .state('index.process.leave.manage', {
                        url: '/manage',
                        views: {
                            'top-part@index.process.leave': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/manage.html',
                                controller: 'leaveManageCtrl'
                            }
                        }

                    })
                    .state('index.process.leave.record', {
                        url: '/record',
                        views: {
                            'top-part@index.process.leave': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/record.html',
                                controller: 'leaveRecordCtrl'
                            }
                        }
                    })
                    .state('index.process.leave.apply', {
                        url: '/apply',
                        views: {
                            'top-part@index.process.leave': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/apply.html',
                                controller: 'leaveApplyCtrl'
                            }
                        }
                    })
                    .state('index.process.leave.update', {
                        url: '/update/:record_id',
                        views: {
                            'top-part@index.process.leave': {
                                templateUrl: 'views/process/top-part.html',
                                controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/update.html',
                                controller: 'leaveUpdateCtrl'
                            }
                        }
                    })
                    .state('index.process.leave.detail', {
                        url: '/detail/:detail_id',
                        views: {
                            'top-part@index.process.leave': {
                                template: '<div style="margin-top:10px;"><div>',
                                // controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.process.leave': {
                                templateUrl: 'views/process/leave/detail.html',
                                controller: 'leaveDetailCtrl'
                            }
                        }
                    })
                    // 考勤
                    .state('index.process.checkIn', {
                        url: '/checkIn',
                        views: {
                            'home@index': {
                                templateUrl: 'views/process/checkin/checkin.html',
                                controller: 'checkInCtrl'
                            },
                        }
                    })
                    // 门户
                    .state('index.portal', {
                        url: '/portal',
                        views: {
                            'home@index': {
                                templateUrl: 'views/home.html',
                                controller: 'portalIndexCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/portal/leftbar.html',
                                controller: 'leftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '门户首页'
                        }
                    })
                    .state('index.portal.columnList', {
                        url: '/columnList/:columnId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/column_list.html',
                                controller: 'columnListCtrl'
                            }
                        },
                        data: {
                            pageTitle: '栏目列表'
                        }
                    })
                    .state('index.portal.columnArticle', {
                        url: '/columnArticle/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/column_article.html',
                                controller: 'columnArticleCtrl'
                            }
                        },
                        data: {
                            pageTitle: '栏目文章'
                        }
                    })
                    .state('index.portal.companyNews', {
                        url: '/companyNews',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/company_news.html',
                                controller: 'companyNewsCtrl'
                            }
                        },
                        data: {
                            pageTitle: '企业大事记'
                        }
                    })
                    .state('index.portal.article', {
                        url: '/article/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/article.html',
                                controller: 'companyNewsDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '企业大事记'
                        }
                    })
                    .state('index.portal.noticeList', {
                        url: '/noticeList',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/notice.html',
                                controller: 'noticeListCtrl'
                            }
                        },
                        data: {
                            pageTitle: '最新公告'
                        }
                    })
                    .state('index.portal.noticeDetail', {
                        url: '/noticeDetail/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/notice_detail.html',
                                controller: 'noticeDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '最新公告'
                        }
                    })
                    .state('index.portal.systemList', {
                        url: '/systemList',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/system.html',
                                controller: 'systemListCtrl'
                            }
                        },
                        data: {
                            pageTitle: '公司制度'
                        }
                    })
                    .state('index.portal.systemDetail', {
                        url: '/systemDetail/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/system_detail.html',
                                controller: 'systemDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '公司制度'
                        }
                    })
                    .state('index.portal.newPartner', {
                        url: '/newPartner',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/new_partner.html',
                                controller: 'newPartnerListCtrl'
                            }
                        }
                    })
                    .state('index.portal.newPartner.detail', {
                        url: '/detail/:userid',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/new_partner_detail.html',
                                controller: 'newPartnerDetailCtrl'
                            }
                        }
                    })
                    .state('index.personalCenter', {
                        url: '/personalCenter',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/personal_center.html',
                                controller: 'personalCenterCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/portal/personal_center_leftbar.html',
                                controller: 'leftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '个人中心'
                        }
                    })
                    .state('index.personalCenter.infoBasic', {
                        url: '/infoBasic',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/personal_center.html',
                                controller: 'infoBasicCtrl'
                            }
                        },
                        data: {
                            pageTitle: '基本信息'
                        }
                    })
                    .state('index.personalCenter.overtimeOff', {
                        url: '/overtimeOff',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/overtime_off.html',
                                controller: 'overtimeOffCtrl'
                            }
                        },
                        data: {
                            pageTitle: '加班调休信息'
                        }
                    })
                    .state('index.personalCenter.msg', {
                        url: '/message',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/message.html',
                                controller: 'msgCtrl'
                            }
                        },
                        data: {
                            pageTitle: '消息中心'
                        }
                    })
                    .state('index.personalCenter.msg.detail', {
                        url: '/detail/:msgId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/msg_detail.html',
                                controller: 'msgDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '消息中心'
                        }
                    })
                    .state('index.personalCenter.myCollect', {
                        url: '/myCollect',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/my_collect.html',
                                controller: 'personMyCollectCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我收藏的'
                        }
                    })
                    .state('index.personalCenter.myComment', {
                        url: '/myComment',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/my_comment.html',
                                controller: 'personMyCommentCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我评论的'
                        }
                    })
                    .state('index.personalCenter.myPublish', {
                        url: '/myPublish',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/my_publish.html',
                                controller: 'personMyPublishCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我发布的'
                        }
                    })
                    .state('index.personalCenter.myPraise', {
                        url: '/myPraise',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/my_praise.html',
                                controller: 'personMyPraiseCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我赞过的'
                        }
                    })
                    .state('index.personalCenter.score', {
                        url: '/score',
                        views: {
                            'home@index': {
                                templateUrl: 'views/portal/score_detail.html',
                                controller: 'scoreDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '积分详情'
                        }
                    })
                    // 知识库
                    .state('index.knowledge', {
                        url: '/km',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_lib.html',
                                controller: 'kmCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/knowledge/leftbar.html',
                                controller: 'leftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '知识库'
                        }

                    })
                    .state('index.knowledge.detail', {
                        url: '/detail/:articleid',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_detail.html',
                                controller: 'articleDetailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '文章详情'
                        }

                    })
                    .state('index.knowledge.comment', {
                        url: '/detail/:articleid/comment',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_detail_comment.html',
                                controller: 'articleCommentCtrl'
                            }
                        },
                        data: {
                            pageTitle: '文章评论'
                        }
                    })
                    .state('index.knowledge.search', {
                        url: '/search/:keyword',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/search_result.html',
                                controller: 'searchKmCtrl'
                            }
                        },
                        data: {
                            pageTitle: '查询结果'
                        }
                    })
                    .state('index.knowledge.searchTagResult', {
                        url: '/searchTagResult/:tagId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/search_result_tag.html',
                                controller: 'searchTagCtrl'
                            }
                        },
                        data: {
                            pageTitle: '查询结果'
                        }
                    })
                    .state('index.knowledge.articleRecommend', {
                        url: '/articleRecommend',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/article_recommend.html',
                                controller: 'articleRecommendCtrl'
                            }
                        },
                        data: {
                            pageTitle: '推荐文章'
                        }
                    })
                    .state('index.knowledge.myAdmire', {
                        url: '/myAdmire',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/my_admire.html',
                                controller: 'myAdmireCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我赞过的文章'
                        }
                    })
                    .state('index.knowledge.myComment', {
                        url: '/myComment',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/my_comment.html',
                                controller: 'myCommentKmCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我评论的文章'
                        }
                    })
                    .state('index.knowledge.myCollect', {
                        url: '/myCollect',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/my_collect.html',
                                controller: 'myCollectKmCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我收藏的文章'
                        }
                    })
                    .state('index.knowledge.train', {
                        url: '/train',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/train.html',
                                controller: 'trainCtrl'
                            }
                        },
                        data: {
                            pageTitle: '培训中心'
                        }
                    })
                    .state('index.knowledge.share', {
                        url: '/share',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/share.html',
                                controller: 'shareCtrl'
                            }
                        },
                        data: {
                            pageTitle: '干货分享'
                        }
                    })
                    .state('index.knowledge.standard', {
                        url: '/standard',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/standard.html',
                                controller: 'standardCtrl'
                            }
                        },
                        data: {
                            pageTitle: '规范'
                        }
                    })
                    .state('index.knowledge.query', {
                        url: '/query',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_query.html',
                                controller: 'kmQueryCtrl'
                            }
                        },
                        data: {
                            pageTitle: '知识查询'
                        }
                    })
                    .state('index.knowledge.query.result', {
                        url: '/:keyword',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_query.html',
                                controller: 'kmQueryCtrl'
                            }
                        },
                        data: {
                            pageTitle: '查询结果'
                        }
                    })
                    .state('index.knowledge.publish', {
                        url: '/publish',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_publish.html',
                                controller: 'kmPublishCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我要发文'
                        }
                    })
                    .state('index.knowledge.edit', {
                        url: '/edit/:articleId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/knowledge/km_publish_edit.html',
                                controller: 'kmPublishEditCtrl'
                            }
                        },
                        data: {
                            pageTitle: '文章编辑'
                        }
                    })
                    // 会议室
                    .state('index.conference', {
                        url: '/conference',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/apply.html',
                                controller: 'conferenceApplyCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    .state('index.conference.apply', {
                        url: '/apply',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/apply.html',
                                controller: 'conferenceApplyCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    .state('index.conference.detail', {
                        url: '/detail/:detail_id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/detail.html',
                                controller: 'conferenceDetailCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    .state('index.conference.update', {
                        url: '/update/:update_id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/update.html',
                                controller: 'conferenceUpdateCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    .state('index.conference.list', {
                        url: '/list',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/list.html',
                                controller: 'conferenceListCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    .state('index.conference.own', {
                        url: '/own',
                        views: {
                            'home@index': {
                                templateUrl: 'views/conference/own.html',
                                controller: 'conferenceOwnCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/conference/room-leftBar.html',
                                controller: 'conferenceleftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '会议室'
                        }
                    })
                    // bbs
                    .state('index.bbs', {
                        url: '/bbs',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/bbs_index.html',
                                controller: 'bbsCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/bbs/leftbar.html',
                                controller: 'leftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '论坛'
                        }
                    })
                    /*.state('index.bbsIndex', {
                        url: '/bbsIndex',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/bbs_index.html',
                                controller: 'bbsCtrl'
                            },
                            'leftbar@index': {
                                templateUrl: 'views/bbs/leftbar.html',
                                controller: 'leftbarCtrl'
                            }
                        },
                        data: {
                            pageTitle: '论坛'
                        }
                    })*/
                    .state('index.bbs.circle', {
                        url: '/circle/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/circle.html',
                                controller: 'circleCtrl'
                            }
                        },
                        data: {
                            pageTitle: '圈子首页'
                        }
                    })
                    .state('index.bbs.circleRecommend', {
                        url: '/circleRecommend/:id',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/circle_recommend.html',
                                controller: 'circleRecommCtrl'
                            }
                        }
                    })
                    .state('index.bbs.detail', {
                        url: '/detail/:articleId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/detail.html',
                                controller: 'detailCtrl'
                            }
                        },
                        data: {
                            pageTitle: '帖子详情'
                        }
                    })
                    .state('index.bbs.allCircles', {
                        url: '/allCircles',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/all_circles.html',
                                controller: 'allCirclesCtrl'
                            }
                        },
                        data: {
                            pageTitle: '所有圈子'
                        }
                    })
                    .state('index.bbs.post', {
                        url: '/post',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/post.html',
                                controller: 'postCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我要发帖'
                        }
                    })
                    .state('index.bbs.postEdit', {
                        url: '/postEdit/:postId',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/post_edit.html',
                                controller: 'postEditCtrl'
                            }
                        },
                        data: {
                            pageTitle: '编辑帖子'
                        }
                    })
                    .state('index.bbs.myCircle', {
                        url: '/myCircle',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/my_circle.html',
                                controller: 'myCircleCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我的圈子'
                        }
                    })
                    .state('index.bbs.myPost', {
                        url: '/myPost',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/my_post.html',
                                controller: 'myPostCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我的帖子'
                        }
                    })
                    .state('index.bbs.myReply', {
                        url: '/myReply',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/my_reply.html',
                                controller: 'myReplyCtrl'
                            }
                        },
                        data: {
                            pageTitle: '我的回帖'
                        }
                    })
                    .state('index.bbs.myCollect', {
                        url: '/myCollect',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/my_collect.html',
                                controller: 'myCollectCtrl'
                            }
                        },
                        data: {
                            pageTitle: '帖子收藏'
                        }
                    })
                    .state('index.bbs.postRecommend', {
                        url: '/postRecommend',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/post_recommend.html',
                                controller: 'postRecommCtrl'
                            }
                        },
                        data: {
                            pageTitle: '帖子收藏'
                        }
                    })
                    .state('index.bbs.manage', {
                        url: '/manage',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/manage.html',
                                controller: 'manageCtrl'
                            }
                        },
                        data: {
                            pageTitle: '圈子管理'
                        }
                    })
                    .state('index.bbs.search', {
                        url: '/search/:keyword',
                        views: {
                            'home@index': {
                                templateUrl: 'views/bbs/search_result.html',
                                controller: 'searchCtrl'
                            }
                        },
                        data: {
                            pageTitle: '搜索结果'
                        }
                    })
                    //工单流程
                    .state('index.workorder', {
                        url: '/workorder',
                        views: {
                            'home@index': {
                                templateUrl: 'views/workorder/index.html',
                            },
                            'leftbar@index': {
                                templateUrl: 'views/workorder/leftBar.html',
                                controller: 'workorderleftbarCtrl'
                            },
                            'top-part@index.workorder': {
                                templateUrl: 'views/workorder/top-part.html',
                                controller: 'workorderTopBarCtrl'
                            },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/apply.html',
                                controller: 'workorderApplyCtrl'
                            }
                        },
                        data: {
                            pageTitle: '工单'
                        }
                    })
                    .state('index.workorder.manage', {
                        url: '/manage',
                        views: {
                            'top-part@index.workorder': {
                                templateUrl: 'views/workorder/top-part.html',
                                controller: 'workorderTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/manage.html',
                                controller: 'workorderManageCtrl'
                            }
                        }

                    })
                    .state('index.workorder.record', {
                        url: '/record',
                        views: {
                            'top-part@index.workorder': {
                                templateUrl: 'views/workorder/top-part.html',
                                controller: 'workorderTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/record.html',
                                controller: 'workorderRecordCtrl'
                            }
                        }
                    })
                    .state('index.workorder.apply', {
                        url: '/apply',
                        views: {
                            'top-part@index.workorder': {
                                templateUrl: 'views/workorder/top-part.html',
                                controller: 'workorderTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/apply.html',
                                controller: 'workorderApplyCtrl'
                            }
                        }
                    }) 
                    .state('index.workorder.update', {
                        url: '/update/:record_id',
                        views: {
                            'top-part@index.workorder': {
                                templateUrl: 'views/workorder/top-part.html',
                                controller: 'workorderTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/update.html',
                                controller: 'workorderUpdateCtrl'
                            }
                        }
                    })
                    .state('index.workorder.detail', {
                        url: '/detail/:detail_id',
                        views: {
                            'top-part@index.workorder': {
                                template: '<div style="margin-top:15px;"><div>',
                                // controller: 'leaveTopBarCtrl'
                            },
                            // 'leftbar@index': {
                            //     templateUrl: 'views/process/process-leftBar.html',
                            // },
                            'bottom-part@index.workorder': {
                                templateUrl: 'views/workorder/detail.html',
                                controller: 'workorderDetailCtrl'
                            }
                        }
                    });
        })
        .run([
            '$rootScope', '$window',
            function($rootScope, $window) {

                $rootScope.$on('$stateChangeStart', function(event, toState) {
                    $rootScope.$state = toState;
                    $window.document.title = toState.data.pageTitle;
                });
            }
        ]);
})();
