(function() {
    'use strict';
    angular.module('app.constants.function-param', [])
        .constant('conditionTypes',{
            "equal":'EQUAL',
            "notEqual":'NOTEQUAL',
            "null":'NULL',
            "like":'LIKE',
            "leftLike":'LLIKE',
            "rightLike":'RLIKE',
            "in":'IN',
            "notIn":'NOTIN',
            "min":'MIN',
            "max":'MAX'
        })
        .constant('genders',{
            "0":'',
            "1":'男',
            "2":'女'
        })
}).call(this);
