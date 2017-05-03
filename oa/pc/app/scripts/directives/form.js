angular.module('form.directive', [])
    .directive('convertToNumber', [function() {

            return {
                require: "ngModel",
                restrict: "A",
                link: function(scope, ele, attrs, modelCtrl) {
                	console.log(111);
                    setTimeout("$(ele).fancySelect()", 0);
                    modelCtrl.$setViewValue(attrs.vlaue);
                    scope.$apply();
                }
                // scope.$on('$routeChangeStart', function(e) {
                //     $(ele).mobiscroll('destroy');
                // })
            
        }
    }])




