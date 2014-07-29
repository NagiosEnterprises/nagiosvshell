'use strict';

angular.module('vshell2.services', [])

    .value('authors', 'Mike Guthrie and Chris Laskey')

    .factory('async', function($http) {

        var async = {};

        // Variables

        async.cache = {};

        async.paths = {};

        async.paths.app = (function() {
            var path = location.pathname,
                has_leading_slash = (path.substring(0,1) == '/'),
                has_trailing_slash = (path.slice(-1) == '/');

            if( path == '' || path == '/' ){
                return path;
            }

            if( ! has_leading_slash ){ path = '/' + path; }
            if( ! has_trailing_slash ){ path = path + '/'; }

            return path;
        })();

        async.paths.api = (function() {
            return async.paths.app + 'api/';
        })();

        // Implementation Methods

        async.validate = function(options){
            options.method = options.method || 'GET';
            return options;
        }

        async.hash = function(input){
            // Quick hash algorithm from Java
            // http://stackoverflow.com/a/7616484/657661
            var hash = 0, i, chr, len;
            if (input.length == 0) { return hash; }
            for (i = 0, len = input.length; i < len; i++) {
                chr   = input.charCodeAt(i);
                hash  = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
            }
            return hash;
        }

        async.update = function(scope, options, data){
            scope[options.name] = data;
            console.log('update', options.name, data);
        }

        async.apply_callback = function(callback, data, status, headers, config){
            if( typeof(callback) == "function" ){
                data = callback(data, status, headers, config);
            }
            return data;
        }

        async.cached = function(scope, options){
            // Return cached data immediately while new data
            // is fetched. Apply any callbacks on cached data
            // before returning.
            var result = async.get_cache(options.url);
            var data = async.apply_callback(options.callback, result.data, result.status, result.headers, result.config);
            async.update(scope, options, data);
        }

        async.get_cache = function(key){
            var cache_key = async.hash(key),
                empty_result = {
                    data: [],
                    status: [],
                    headers: [],
                    config: []
                };
            // console.log('async cache get', key, cache_key, async.cache[cache_key]);
            return async.cache[cache_key] || empty_result;
        }

        async.set_cache = function(key, value){
            var cache_key = async.hash(key);
            async.cache[cache_key] = value;
            // console.log('async cache set', key, cache_key, value);
            // console.log('async cache size', async.cache);
        }

        async.api = function(scope, options){
            options.url = async.paths.api + options.url;
            options = async.validate(options);
            async.cached(scope, options);
            async.fetch(scope, options);
        }

        async.fetch = function(scope, options){
            $http({ method: options.method, url: options.url })
                .success(function(data, status, headers, config) {
                    var result = {
                        data: data,
                        status: status,
                        headers: headers,
                        config: config
                    };

                    async.set_cache(options.url, result);
                    data = async.apply_callback(options.callback, data, status, headers, config);
                    async.update(scope, options, data);
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to load ' + options.name + ' data from the V-Shell2 API');
                });
        }

        // Public interface

        return {
            api: function(scope, options){
                async.api(scope, options);
            }
        }
    })

    // TODO: deprecated, now part of the async instance
    .factory('vshell_uri', function() {
        var path = location.pathname,
            has_leading_slash = (path.substring(0,1) == '/'),
            has_trailing_slash = (path.slice(-1) == '/');

        if( path == '' || path == '/' ){
            return path;
        }

        if( ! has_leading_slash ){ path = '/' + path; }
        if( ! has_trailing_slash ){ path = path + '/'; }

        return path;
    })
