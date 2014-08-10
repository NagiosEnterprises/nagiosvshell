'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jsbeautifier: {
            app: {
                options: {
                    config: ".jsbeautifierrc"
                },
                src: [
                    'www/frontend/js/app.js',
                    'www/frontend/js/controllers.js',
                    'www/frontend/js/directives.js',
                    'www/frontend/js/filters.js',
                    'www/frontend/js/page.js',
                    'www/frontend/js/services.js'
                ]
            }
        },
        jshint: {
            app: {
                options: {
                    jshintrc: '.jshintrc'
                },
                src: [
                    'www/frontend/js/app.js',
                    'www/frontend/js/controllers.js',
                    'www/frontend/js/directives.js',
                    'www/frontend/js/filters.js',
                    'www/frontend/js/page.js',
                    'www/frontend/js/services.js'
                ]
            }
        }
    });

    // Load grunt tasks
    grunt.loadNpmTasks('grunt-jsbeautifier');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    
    // Default task
    grunt.registerTask('default', ['jsbeautifier', 'jshint']);

    // Test task
    grunt.registerTask('test', ['jshint']);

};
