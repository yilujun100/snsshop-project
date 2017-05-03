(function() {
    angular.module('app.directives.ui', [])
        //页面切换过度动画
        // .directive('pageLoading',[
        //     function(){
        //         return {
        //             restrict:"A",
        //             scope: {loading:"=",openLoading:"="},
        //             replace:true,
        //             template:'<div ng-show="loading || openLoading" class="app-content-loading"></div>',
        //             link: function(scope, ele, attrs) {
        //                 scope.$on('$routeChangeStart', function(){
        //                     scope.loading = true;
        //                 });
        //                 scope.$on('$routeChangeSuccess', function(){
        //                     scope.loading = false;
        //                 });
        //             }
        //         }

    //     }])
    .directive('msDate', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().date({
                            dateFormat: 'yy-mm-dd',
                            display: 'top',
                            lang: 'zh',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msDateTime', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().datetime({
                            dateFormat: 'yy-mm-dd',
                            display: 'top',
                            lang: 'zh',
                            timeFormat: 'HH:ii',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msTime', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().time({
                            display: 'top',
                            lang: 'zh',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msSelect', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        setTimeout(function() {
                            $(ele).mobiscroll().select({
                                display: 'top',
                                lang: 'zh',
                                multiple: attrs.multiple,
                                placeholder: attrs.placeholder,
                                onSelect: function(value, inst) {
                                    modelCtrl.$setViewValue(inst._tempValue);
                                    scope.$apply();
                                }
                            });
                        }, 10)
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('fixedWindowHeight', [
            function() {
                return {
                    restrict: "A",
                    link: function(scope, ele, attrs) {
                        // console.log(window.screen.availHeight,window.innerHeight);
                        ele.css('min-height', (window.innerHeight - 261) + 'px');
                    }
                }
            }
        ])
        .directive('fixedDetailHeight', [
            function() {
                return {
                    restrict: "A",
                    link: function(scope, ele, attrs) {
                        // console.log(window.screen.availHeight,window.innerHeight);
                        ele.css('min-height', (window.innerHeight - 185) + 'px');
                    }
                }
            }
        ])
        .directive('convertToNumber', function() {
            return {
                require: 'ngModel',
                link: function(scope, element, attrs, ngModel) {
                    ngModel.$parsers.push(function(val) {
                        return val != null ? '' + val : null;
                    });
                    ngModel.$formatters.push(function(val) {
                        return val != null ? '' + val : null;
                    });
                }
            };
        })
        .directive('ngThumb', ['$window', function($window) {
            var helper = {
                support: !!($window.FileReader && $window.CanvasRenderingContext2D),
                isFile: function(item) {
                    return angular.isObject(item) && item instanceof $window.File;
                },
                isImage: function(file) {
                    var type = '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            };

            return {
                restrict: 'A',
                template: '<canvas/>',
                link: function(scope, element, attributes) {
                    if (!helper.support) return;

                    var params = scope.$eval(attributes.ngThumb);

                    if (!helper.isFile(params.file)) return;
                    if (!helper.isImage(params.file)) return;

                    var canvas = element.find('canvas');
                    var reader = new FileReader();

                    reader.onload = onLoadFile;
                    reader.readAsDataURL(params.file);

                    function onLoadFile(event) {
                        var img = new Image();
                        img.onload = onLoadImage;
                        img.src = event.target.result;
                    }

                    function onLoadImage() {
                        var width = params.width || this.width / this.height * params.height;
                        var height = params.height || this.height / this.width * params.width;
                        canvas.attr({ width: width, height: height });
                        canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                    }
                }
            };
        }])
}).call(this);
