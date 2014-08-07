module.exports = function(config){
  config.set({

    basePath : '../www/',

    files : [
      'frontend/js/lib/jquery-*.js',
      'frontend/js/lib/angular-1*.js',
      'frontend/js/lib/angular-route.1*.js',
      'frontend/js/lib/jquery.cookie*.js',
      'frontend/js/lib/typeahead*.js',
      'frontend/js/lib/underscore*.js',
      'frontend/js/lib/moment*.js',
      'frontend/js/lib/footable*.js',
      'frontend/js/page.js',
      'frontend/js/app.js',
      'frontend/js/controllers.js',
      'frontend/js/directives.js',
      'frontend/js/filters.js',
      'frontend/js/services.js',
      'frontend/bower_components/angular-mocks/angular-mocks.js',
      '../test/unit/**/*.js'
    ],

    autoWatch : true,

    frameworks: ['jasmine'],

    browsers : ['Firefox'],

    plugins : [
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-jasmine',
            'karma-junit-reporter'
            ],

    junitReporter : {
      outputFile: 'test_out/unit.xml',
      suite: 'unit'
    }

  });
}
