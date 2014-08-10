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
                    'www/frontend/js/*.js',
                ]
            },
            tests: {
                options: {
                    config: ".jsbeautifierrc"
                },
                src: [
                    'test/unit/specs/*.js',
                ]
            }
        },
        jshint: {
            app: {
                options: {
                    jshintrc: '.jshintrc'
                },
                src: [
                    'www/frontend/js/*.js',
                ]
            },
            tests: {
                options: {
                    jshintrc: '.jshintrc'
                },
                src: [
                    'test/unit/specs/*.js',
                ]
            }
        }
    });

    // Load grunt tasks
    grunt.loadNpmTasks('grunt-jsbeautifier');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    
    // Register grunt tasks
    grunt.registerTask('default', ['jsbeautifier', 'jshint']);
    grunt.registerTask('test', ['jsbeautifier', 'jshint']);

};
