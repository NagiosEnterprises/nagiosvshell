'use strict';

angular.module('vshell2.services', [])

    .value('authors', 'Mike Guthrie and Chris Laskey')

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
