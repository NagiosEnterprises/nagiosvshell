module.exports = function(config){
  config.set({

    basePath : '../www/',

    files : [
      // Note: un-minified libraries often produce better error messages.
      // Using Bower makes this easy.
      
      'frontend/js/lib/jquery-*.js',
      'frontend/bower_components/angular/angular.js',
      'frontend/bower_components/angular-route/angular-route.js',
      'frontend/js/lib/jquery.cookie*.js',
      'frontend/js/lib/typeahead*.js',
      'frontend/bower_components/underscore/underscore.js',
      'frontend/js/lib/moment*.js',
      'frontend/js/lib/footable*.js',
      'frontend/js/page.js',
      'frontend/js/app.js',
      'frontend/js/controllers.js',
      'frontend/js/directives.js',
      'frontend/js/filters.js',
      'frontend/js/services.js',
      'frontend/bower_components/angular-mocks/angular-mocks.js',
      '../test/unit/mocks/*.js',
      '../test/unit/specs/**/*.js',
    ],

    autoWatch : true,

    frameworks: ['jasmine'],

    browsers : ['PhantomJS'],

    plugins : [
      'karma-chrome-launcher',
      'karma-firefox-launcher',
      'karma-phantomjs-launcher',
      'karma-jasmine',
      'karma-junit-reporter',
      'karma-coverage',
    ],

    reporters: ['progress', 'coverage'],

    preprocessors: {
      'frontend/js/controllers.js': ['coverage'],
      'frontend/js/directives.js': ['coverage'],
      'frontend/js/filters.js': ['coverage'],
      'frontend/js/services.js': ['coverage'],
    },

    coverageReporter: {
      type : 'text',
      dir : '../test/coverage/',
    },

  });
}
