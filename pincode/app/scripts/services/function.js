(function() {
    'use strict';
    angular.module('pincodeApp.services.function', ['ngCookies'])
    	.factory('dateRangePicker', function() {
    		return {
    			locale: {
                    format: 'YYYY-MM-DD',
    				applyLabel: "确认",
		            cancelLabel: '取消',
		            daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
		            firstDay: 1,
		            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月',
		                '十月', '十一月', '十二月'
		            ]
    			},
    			default: {
    				startDate: moment().subtract(7, 'days'), // 最近七天
    				endDate: moment().subtract(1, 'days') // prevDay
    			},
    			setDate: function(lastDays) {
					var last7Days = moment().subtract(7, 'days'),
						last30Days = moment().subtract(30, 'days');

					if (lastDays === 7) {
						this.default.startDate = last7Days;
					} else if (lastDays === 30) {
						this.default.startDate = last30Days;
					}
					$('#dateRangePicker').val(this.default.startDate.format('YYYY-MM-DD') + ' - ' + this.default.endDate.format('YYYY-MM-DD'));
    			}
    		}
    	})
        .factory('dateTimeRangePicker', ['dateRangePicker', 'mergeJsonObj', function(dateRangePicker, mergeJsonObj) {
            return {
                opts: {    
                    locale: mergeJsonObj.doMerge(dateRangePicker.locale, {format: 'YYYY-MM-DD HH:mm:ss'}), 
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    autoUpdateInput: false
                }
            }
        }])
        .factory('singleDateTimePicker', ['dateRangePicker', 'mergeJsonObj', function(dateRangePicker, mergeJsonObj) {
            return {
                opts: {    
                    locale: mergeJsonObj.doMerge(dateRangePicker.locale, {format: 'YYYY-MM-DD HH:mm:ss'}),            
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    autoUpdateInput: false
                }
            }
        }])
        .factory('mergeJsonObj', function() {
            return {
                doMerge: function(obj1, obj2) {
                    var resultObj = {};

                    for (var attr in obj1) {
                        resultObj[attr] = obj1[attr];
                    }

                    for (var attr in obj2) {
                        resultObj[attr] = obj2[attr];
                    }

                    return resultObj;                    
                }
            }
        });
})();