'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            src: {
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
    grunt.loadNpmTasks('grunt-contrib-jshint');
    
    // Default task
    grunt.registerTask('default', ['jshint']);

    // Test task
    grunt.registerTask('test', ['jshint']);

};
