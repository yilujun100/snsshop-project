(function() {
	'use strict';
	angular.module('pincodeApp.controller.leftbar', [])
		.controller('leftbarCtrl', leftbarCtrl);

	function leftbarCtrl($rootScope) {
		$rootScope.menu = [
			{
				name: '活动',
				isOpen: true,
				iconClass: 'fa-gift',
				isActive: false,
				submenu: [
					{
						name: '活动码策略',
						url: '/activityList',
						isActive: false
					},
					{
						name: '扫码记录',
						url: '/activityRecord',
						isActive: false
					}
				]
			},
			{
				name: '防伪',
				isOpen: true,
				iconClass: 'fa-qrcode',
				isActive: false,
				submenu: [
					{
						name: '防伪码策略',
						url: '/securityList',
						isActive: false
					},
					{
						name: '扫码记录',
						url: '/securityRecord',
						isActive: false
					}
				]
			},
			{
				name: '溯源',
				isOpen: true,
				iconClass: 'fa-qrcode',
				isActive: false,
				submenu: [
					{
						name: '溯源码策略',
						url: '/rootsList',
						isActive: false
					},
					{
						name: '扫码记录',
						url: '/rootsRecord',
						isActive: false
					}
				]
			},
			{
				name: '数据统计',
				isOpen: true,
				iconClass: 'fa-line-chart',
				isActive: false,
				submenu: [
					{
						name: '扫码概况',
						url: '/codeCheck',
						isActive: false
					},
					{
						name: '活动码统计',
						url: '/statisActivity',
						isActive: false
					},
					{
						name: '防伪码统计',
						url: '/statisSecurity',
						isActive: false
					},
					{
						name: '溯源码统计',
						url: '/statisRoots',
						isActive: false
					}
				]
			},
			{
				name: '素材库',
				isOpen: true,
				iconClass: 'fa-inbox',
				isActive: false,
				submenu: [
					{
						name: '活动模板',
						url: '/actiTemplate',
						isActive: false
					},
					{
						name: '溯源模板',
						url: '/rootsTemplate',
						isActive: false
					}
				]
			}
		];
		/*var currentPath = $location.path();
		angular.forEach($scope.menu, function(item) {
			angular.forEach(item.submenu, function(itemInner) {
				if (itemInner.url === currentPath) {
					itemInner.isActive = true;
					item.isActive = true;
				}
			})
		});*/
	}

	leftbarCtrl.$inject = ['$rootScope'];
})();