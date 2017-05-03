(function() {
	'use strict';
	angular.module('pincodeApp.controller.statisActivity', [])
		.controller('statisActivityCtrl', statisActivityCtrl);

	function statisActivityCtrl($scope, dateRangePicker) {
		
		// dateRange
		$scope.opts = {
			locale: dateRangePicker.locale
		};
		$scope.date = dateRangePicker.default;
		$scope.setDateRange = function(lastDays) {
			dateRangePicker.setDate(lastDays);
		};

		// chart
		$scope.chartSeries = [
			{"name": "扫码数", "data": [1, 2, 4, 7, 3]},
			{"name": "用户数", "data": [3, 1, 5, 5, 2]},
			{"name": "粉丝数", "data": [5, 2, 2, 3, 5]},
			{"name": "中奖数", "data": [1, 1, 2, 3, 2]}
		];
		$scope.chartConfig = {
			chart: {
				type: 'line',
				width: angular.element('.chart').width(),
				height: 400
			},
			title: {
				text: null
			},			
            xAxis: {
                tickLength: 0,
                categories: ['00:00', '04:00', '08:00', '12:00', '16:00'],
                lineColor: '#999',                              
                labels: {
                    style: {
                        fontFamily: 'Microsoft Yahei', 
                        fontSize: '14px', 
                        color: '#686b6c'
                    }
                }
            },
            yAxis: {
                title: {
                    text: null
                },
                tickPositions: [0, 5, 10],                              
                labels: {
                    style: {
                        fontFamily: 'Microsoft Yahei', 
                        fontSize: '14px', 
                        color: '#686b6c'
                    }
                }
            },
            legend: {
            	itemStyle: {
            		fontSize: '13px',
            		color: '#686b6c',
            		fontFamily: 'Microsoft Yahei',
            		fontWeight: 'normal'
            	}
            },
			series: $scope.chartSeries,
			tooltip: {
				shared: true,
				crosshairs: {
					width: 1,
					color: '#ccc'
				},
				borderWidth: 0,
				borderColor: 'transparent',
				borderRadius: 0,
				backgroundColor: 'rgba(0,0,0,.6)',
				shadow: false,
				style: {
					color: '#fff'
				}
			},
            credits: {
                enabled: false
            }
		};
		$scope.reflow = function () {
		    $scope.$broadcast('highchartsng.reflow');
	  	};
	}

	statisActivityCtrl.$inject = ['$scope', 'dateRangePicker'];
})();