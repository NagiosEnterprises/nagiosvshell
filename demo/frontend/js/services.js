'use strict';

angular.module('vshell2.services', [])

    .value('authors', 'Mike Guthrie and Chris Laskey')

    .factory('async', function($http, $timeout, $interval, paths) {

        var async = {};

        async.cache = {};

        async.intervals = {};

        async.fetch_interval_time = (function(){
            // Get the interval time from the API
            //
            // Since AngularJS services are singletons, executing function
            // immediately triggers it on service instantiation and skips the
            // need for a seperate init function

            var uri = paths.api + 'vshellconfig';

            $http.get(uri).then(function(response){
                var seconds = +response.data.updateinterval,
                    milliseconds;

                if( !seconds ) {
                    seconds = 90;
                    messages.error('invalid UPDATEINTERVAL value in vshell.conf, using default of ' + seconds + ' seconds.');
                }

                milliseconds = seconds * 1000;
                async.interval_time = milliseconds;
            });
        })();

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

        async.set_scope = function(scope, options, data){
            scope[options.name] = data;
        }

        async.is_loading = function(scope, bool){
            var result = bool ? true : false;
            scope['is_loading'] = result;
        }

        async.apply_callback = function(callback, data, status, headers, config){
            if( typeof(callback) == "function" ){
                data = callback(data, status, headers, config);
            }
            return data;
        }

        async.cached = function(scope, options){
            var result = async.get_cache(options.url),
                data = async.apply_callback(options.callback, result.data, result.status, result.headers, result.config);

            async.set_scope(scope, options, data);
        }

        async.get_cache = function(key){
            var cache_key = async.hash(key),
                empty_result = {};

            return async.cache[cache_key] || empty_result;
        }

        async.set_cache = function(key, value){
            var cache_key = async.hash(key);
            async.cache[cache_key] = value;
        }

        async.update_queue = function(scope, options){
            // Update data asynchronously on a time interval set in vshell.conf
            //
            // Named queues allow multiple streams of data to be updated and
            // minimizes the chances of an unseen background piece of data
            // updating endlessly.

            if( options.queue ) {

                // Wait for interval time to be loaded from API
                if( !async.interval_time ){
                    $timeout(function(){
                        async.update_queue(scope, options);
                    }, 100);
                }

                // Cancel any existing intervals in the same queue
                $interval.cancel(async.intervals[options.queue]);

                // Update named interval queue with new interval
                async.intervals[options.queue] = $interval(function() {
                    async.fetch(scope, options);
                }, async.interval_time);

            }
        }

        async.api = function(scope, options){
            options.url = paths.api + options.url + '/index.html';
            options = async.validate(options);
            if( options.cache ){
                async.cached(scope, options);
            }
            async.fetch(scope, options);
            // Temporarily disable update queue to make GitHub Demo debugging easier
            // async.update_queue(scope, options);
        }

        async.fetch = function(scope, options){
            async.is_loading(scope, true);
            $http({ method: options.method, url: options.url })
                .success(function(data, status, headers, config) {
                    var result = {
                        data: data,
                        status: status,
                        headers: headers,
                        config: config
                    };

                    async.is_loading(scope, false);
                    async.set_cache(options.url, result);
                    async.cached(scope, options);
                }).
                error(function(data, status, headers, config) {
                    messages.error('failed to update ' + options.name + ' data from the V-Shell2 API');
                });
        }

        return {
            api: function(scope, options){
                async.api(scope, options);
            }
        };

    })

    .factory('paths', function($http) {

        var add_slashes = function(path){
            var has_leading_slash = (path.substring(0,1) == '/'),
                has_trailing_slash = (path.slice(-1) == '/');

            if( path == '' || path == '/' ){
                return path;
            }

            if( ! has_leading_slash ){ path = '/' + path; }
            if( ! has_trailing_slash ){ path = path + '/'; }

            return path;
        };

        var app = (function(){
            return add_slashes(location.pathname);
        })();

        var api = (function(){
            return app + 'api/';
        })();

        var core_as_promise = (function(){
            // Returns a promise, usable in Controllers. For filters
            // see the nested object hack in the core_for_filters() function.
            //
            // http://stackoverflow.com/a/12513509/657661

            var uri = api + 'vshellconfig';

            return $http.get(uri).then(function(response){
                var path = response.data.coreurl;
                return add_slashes(path);
            });

        })();

        var core_for_filters = (function(){
            // Instead of using a promise, return a nested object.
            // When the value returns, the inner key: value gets updated.
            // Downside is this only works with AngularJS filters and
            // adds an extra call, e.g. paths.core.value.
            //
            // For an alternative returning a promise see core_as_promise()
            // function.
            //
            // https://groups.google.com/forum/#!topic/angular/xbAZY8ZKSd4

            var uri = api + 'vshellconfig',
                results = {
                    value: {}
                };

            $http.get(uri).then(function(response){
                var path = response.data.coreurl;
                results.value = add_slashes(path);
            });

            return results;

        })();

        return {
            app: app,
            api: api,
            core_as_promise: core_as_promise,
            core_for_filters: core_for_filters,
        };

    })

