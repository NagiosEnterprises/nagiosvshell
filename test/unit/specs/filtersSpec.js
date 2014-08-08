'use strict';

describe('filter', function() {

    var filter;

    beforeEach(function(){
        module('vshell.filters');
    });

    describe('capitalize', function() {

        beforeEach(inject(function($filter) {
            filter = $filter('capitalize');
        }));

        it('Should return an empty string when no arguments sent', function(){
            expect( filter() ).toBe('');
        });

        it('Should return an empty string when sent an empty string', function(){
            expect( filter() ).toBe('');
        });

        it('Should only capitalize the first letter', function(){
            expect( filter('test') ).toBe('Test');
        });

        it('Should only capitalize the first letter of the first word', function(){
            expect( filter('a complete sentence to test') ).toBe('A complete sentence to test');
        });

        it('Should not change already capitalized letters', function(){
            expect( filter('TESt') ).toBe('TESt');
        });

        it('Should change already capitalized letters if second argument is set to True', function(){
            expect( filter('TESt', true) ).toBe('Test');
        });

    });

    describe('count', function() {

        beforeEach(inject(function($filter) {
            filter = $filter('count');
        }));

        it('Should return zero when no arguments sent', function(){
            expect( filter() ).toBe(0);
        });

        it('Should return zero when given a boolean', function(){
            expect( filter(false) ).toBe(0);
            expect( filter(true) ).toBe(0);
        });

        it('Should return zero when given a number', function(){
            expect( filter(313) ).toBe(0);
        });

        it('Should return string length when given a string', function(){
            expect( filter('test string') ).toBe(11);
        });

        it('Should return zero when given an empty array', function(){
            expect( filter([]) ).toBe(0);
        });

        it('Should return array length when given an array', function(){
            var array = ['one', 2, 'three', 'five'];
            expect( filter(array) ).toBe(4);
        });

        it('Should return shallow array length when given a nested array', function(){
            var array = ['one', 2, 'three', ['sub', 'nested', 'array'], 'five'];
            expect( filter(array) ).toBe(5);
        });

        it('Should return zero when given an empty object', function(){
            expect( filter({}) ).toBe(0);
        });

        it('Should return object property count when given an object', function(){
            var object = {'one': 1, 'two': 2};
            expect( filter(object) ).toBe(2);
        });

        it('Should return shallow object property count when given a nested object', function(){
            var object = {'one': 1, 'two': {'nested': 'object'}};
            expect( filter(object) ).toBe(2);
        });

    });

    describe('plural', function() {

        beforeEach(inject(function($filter) {
            filter = $filter('plural');
        }));

        it('Should return s when given a zero length parameter', function(){
            expect( filter('') ).toBe('s');
            expect( filter([]) ).toBe('s');
            expect( filter({}) ).toBe('s');
        });

        it('Should return an empty string when given a 1 length parameter', function(){
            expect( filter('a') ).toBe('');
            expect( filter(['one']) ).toBe('');
            expect( filter({'one': 1}) ).toBe('');
        });

        it('Should return an empty string when given a 1 length parameter', function(){
            expect( filter('ask') ).toBe('s');
            expect( filter(['one', 'two', 'three']) ).toBe('s');
            expect( filter({'one': 1, 'two': 2}) ).toBe('s');
        });

    });

    describe('percent', function() {

        beforeEach(inject(function($filter) {
            filter = $filter('percent');
        }));

        it('Should raise an error when zero arguments given', function(){
            // Wrap error throwing commands in an anonymous function
            // http://stackoverflow.com/a/4144803/657661
            expect( function(){ filter(); } ).toThrow(new Error('Requires numeric input and total arguments'));
        });

        it('Should raise an error when one argument given', function(){
            expect( function(){ filter(1); } ).toThrow(new Error('Requires numeric input and total arguments'));
        });

        it('Should return 0% when zero given as first parameter', function(){
            expect( filter(0, 10) ).toBe('0%');
        });

        it('Should raise an error when zero given as second parameter', function(){
            expect( function(){ filter(1, 0); } ).toThrow(new Error('Requires numeric input and total arguments'));
        });

        it('Should return 1 rounded decimal place when no third parameter given', function(){
            expect( filter(1, 9) ).toBe('11.1%');
        });

        it('Should return correct percentage when string values are given', function(){
            expect( filter('1', '9') ).toBe('11.1%');
        });

        it('Should return specific decimal place number when third parameter given', function(){
            expect( filter(1, 9, 5) ).toBe('11.11111%');
            expect( filter(1, 9, 0) ).toBe('11%');
        });

        it('Should return no decimal places if answer is 0% or 100%', function(){
            expect( filter(0, 10, 2) ).toBe('0%');
            expect( filter(10, 10, 5) ).toBe('100%');
        });

    });

});
