'use strict';

angular.module('vshell2.directives', [])

    .directive('footable', function(){
        // Code below is from Daniel Stucki. For more information see his
        // excellent StackOverflow answer:
        // http://stackoverflow.com/a/21057869/657661

        return function(scope, element) {
            var footableObject;

            if (scope.$last && ! $('.footable').hasClass('footable-loaded')) {
                $('.footable').footable();
            };

            footableObject = $('.footable').data('footable');

            if (footableObject !== undefined) {
                footableObject.appendRow($(element));
            };
        };

    })
