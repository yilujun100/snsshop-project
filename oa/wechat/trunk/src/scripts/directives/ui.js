(function() {
    angular.module('app.directives.ui', [])
        //页面切换过度动画
        .directive('pageLoading', [
            function() {
                return {
                    restrict: "A",
                    scope: { loading: "=", openLoading: "=" },
                    replace: true,
                    template: '<div ng-show="loading || openLoading" class="app-content-loading"></div>',
                    link: function(scope, ele, attrs) {
                        scope.$on('$routeChangeStart', function() {
                            scope.loading = true;
                        });
                        scope.$on('$routeChangeSuccess', function() {
                            scope.loading = false;
                        });
                    }
                }

            }
        ])
        .directive('msDate', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().date({
                            dateFormat: 'yy-mm-dd',
                            display: 'bottom',
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
                            display: 'bottom',
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
        .directive('msTime', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().time({
                            display: 'bottom',
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
                                display: 'bottom',
                                lang: 'zh',
                                multiple: attrs.multiple,
                                placeholder: attrs.placeholder,
                                onSelect: function(value, inst) {
                                    if (attrs.item) {
                                        var selectItem = _.find(angular.fromJson(attrs.item), function(child) {
                                            return child.name == value
                                        });
                                        modelCtrl.$setViewValue(selectItem.id);
                                        scope.$apply();
                                        scope.createOrder.form_child_id = selectItem.id;
                                    } else {
                                        modelCtrl.$setViewValue(inst._tempValue);
                                        scope.$apply();
                                    }
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
}).call(this);
