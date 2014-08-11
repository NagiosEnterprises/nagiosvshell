'use strict';

describe('service', function() {

    var http, paths, service;

    var test_url = function(path) {
        return '/context.html' + path;
    };

    beforeEach(function() {
        module('vshell.services');
        inject(function($httpBackend) {
            http = $httpBackend;
        });
    });

    describe('authors', function() {

        beforeEach(inject(function(authors) {
            service = authors;
        }));

        it('Should be defined', function() {
            expect(service).toBeDefined();
        });

    });

    describe('paths', function() {

        beforeEach(function() {
            inject(function(_paths_) {
                http.when('GET', test_url('/api/vshellconfig'))
                    .respond({
                        'coreurl': '\/test-coreurl-value'
                    });

                paths = _paths_;
            });
        });

        afterEach(function() {
            http.verifyNoOutstandingExpectation();
            http.verifyNoOutstandingRequest();
        });

        it('Should make $http calls when service is created', function() {
            http.expectGET(test_url('/api/vshellconfig'));
            http.flush();
        });

        it('Should return app path', function() {
            expect(paths.app).toBe(test_url('/'));
            http.flush();
        });

        it('Should return api path', function() {
            expect(paths.api).toBe(test_url('/api/'));
            http.flush();
        });

        it('Should return an empty api appendix since it is not a demo', function() {
            expect(paths.api_appendix).toBe('');
            http.flush();
        });

        it('Should return coreurl value inside a promise', function() {
            paths.core_as_promise.then(function(value) {
                expect(value).toBe('/test-coreurl-value/');
            });
            http.flush();
        });

        it('Should have a (faked) synchronous method for filters', function() {
            expect(paths.core_for_filters).toBeDefined();
            http.flush();
        });

    });

    describe('async', function() {

        beforeEach(function() {
            inject(function($httpBackend, async, _paths_) {
                http = $httpBackend;

                http.when('GET', test_url('/api/vshellconfig'))
                    .respond({});

                service = async;
                paths = _paths_;
            });
        });

        it('Should make $http call', function() {
            http.expectGET(test_url('/api/vshellconfig'));
            http.flush();
        });

    });

});
