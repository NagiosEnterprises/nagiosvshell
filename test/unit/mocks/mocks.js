'use strict';

// How to write better Jasmine tests with mocks
// http://eclipsesource.com/blogs/2014/03/27/mocks-in-jasmine-tests/

var mockService = function(service, name){
    var keys = _.keys(service),
        name = name || 'mock';

    return keys.length > 0 ? jasmine.createSpyObj(name, keys) : {};
};
