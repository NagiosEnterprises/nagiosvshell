'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bump: {
            options: {
                files: ['package.json'],
                updateConfigs: [],
                commit: true,
                commitMessage: 'Release v%VERSION%',
                commitFiles: ['package.json'],
                createTag: true,
                tagName: 'v%VERSION%',
                tagMessage: 'Version %VERSION%',
                push: false,
                pushTo: 'upstream',
                gitDescribeOptions: '--tags --always --abbrev=1 --dirty=-d'
            }
        }, 
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
    grunt.loadNpmTasks('grunt-bump');
    grunt.loadNpmTasks('grunt-jsbeautifier');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    
    // Register grunt tasks
    grunt.registerTask('default', ['jsbeautifier', 'jshint']);
    grunt.registerTask('test', ['jsbeautifier', 'jshint']);

};
