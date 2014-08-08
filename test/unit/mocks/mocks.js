'use strict';

// How to write better Jasmine tests with mocks
// http://eclipsesource.com/blogs/2014/03/27/mocks-in-jasmine-tests/

var mockService = function(service, name){
    var keys = _.keys(service),
        name = name || 'mock';

    return keys.length > 0 ? jasmine.createSpyObj(name, keys) : {};
};

// var createMock = function(keys, object, name) {
//     var name = name || 'mock';

//     if( object ){
//         var object_keys = _.keys(object);
//         if( ! _.isEqual(keys, object_keys) ){
//             return false;
//         }
//     }

//     return keys.length > 0 ? jasmine.createSpyObj(name, keys) : {};
// };

// var mockServiceAsync = function(async){
//     var keys = ['api'],
//         mock = createMock(keys, async, 'mock service async');

//     if( ! mock ){
//         jasmine.log('Failed to create mock object, interface does not match');
//         console.log('Failed to create mock object, interface does not match');
//     }

//     return mock;
// };

describe('mocks', function(){

    beforeEach(function(){
        module('vshell.services');
    });

    // it('Should have been called', function() {
    //     var async = mockServiceAsync();
        
    //     async.api();

    //     expect( async.api ).toHaveBeenCalled();
    // });

});
