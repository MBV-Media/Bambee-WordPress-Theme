/**
 * @version 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @license MIT
 */
'use strict';

/**
 * Abstract class representing the Gruntfile.
 * Provides methods to customize Grunt tasks.
 *
 * @since 1.0.0
 * @class
 */
var BambeeGrunt = (function() {

  /**
   * Private properties and methods
   */
  var self, _copyTaskConfig, _cssminTaskConfig, _uglifyTaskConfig, _cwd;

  /**
   * @since 1.0.0
   * @var {void|object} - BambeeGrunt instance
   */
  self = void 0;

  /**
   * Add cwd to file paths.
   *
   * @since 1.0.0
   * @param {string[]} files
   * @param {string} cwd
   * @return {string[]}
   */
  _cwd = function(files, cwd) {
    if (cwd) {
      var i, file;
      for (i = 0; i < files.length; i++) {
        file = files[i];
        file = cwd + file;
        files[i] = file;
      }
    }
    return files;
  };

  /**
   * @since 1.0.0
   * @var {void|object}
   */
  BambeeGrunt.prototype.grunt = void 0;

  /**
   * @since 1.0.0
   * @var {object}
   */
  BambeeGrunt.prototype.appConfig = {};

  /**
   * @since 1.0.0
   * @var {object}
   */
  BambeeGrunt.prototype.taskConfigs = {
    clean: require('./utils/clean-task-config'),
    scsslint: require('./utils/scss-task-config'),
    compass: require('./utils/compass-task-config'),
    coffeelint: require('./utils/coffeelint-task-config'),
    coffee: require('./utils/coffee-task-config'),
    notify: require('./utils/notify-task-config'),
    watch: require('./utils/watch-task-config')
  };

  /**
   * @since 1.0.0
   * @var {string[]}
   */
  BambeeGrunt.prototype.taskList = [
    'clean',
    'copy',
    'scsslint',
    'compass',
    'coffeelint',
    'coffee',
    'cssmin',
    'uglify',
  ];

  /**
   * Set up everything.
   *
   * @since 1.0.0
   * @constructor
   * @param {object} grunt
   * @return {object} - BambeeGrunt instance
   */
  function BambeeGrunt(grunt) {
    self = this;
    self.grunt = grunt;
    self.taskConfigs.appConfig = self.appConfig = require('./utils/app-config');

    // Set up tasks
    self.setUpDefaultCopyTaskConfig();
    self.taskConfigs.copy = _copyTaskConfig;
    self.setUpDefaultCssminTaskConfig();
    self.taskConfigs.cssmin = _cssminTaskConfig;
    self.setUpDefaultUglifyTaskConfig();
    self.taskConfigs.uglify = _uglifyTaskConfig;
    return self.setUpTasks();
  }

  /**
   * Set up the default copy task config.
   *
   * @since 1.0.0
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.setUpDefaultCopyTaskConfig = function() {
    _copyTaskConfig = {
      dist: {
        files: [
          {
            expand: true,
            cwd: self.appConfig.src,
            src: [
              '**/**/*',
              '!**/**/*.{scss,coffee}',
            ],
            dest: self.appConfig.dist + '/',
            filter: 'isFile'
          }
        ]
      }
    };
    return self;
  };

  /**
   * Add a file to the copy task.
   *
   * @since 1.0.0
   * @param {string} src
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.copy = function(src) {
    _copyTaskConfig.dist.files[0].src.push(src);
    return self;
  };

  /**
   * Set up the default cssmin task config.
   *
   * @since 1.0.0
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.setUpDefaultCssminTaskConfig = function() {
    _cssminTaskConfig = {
      dist: {
        options: {
          keepSpecialComments: 0,
          sourceMap: self.grunt.option('devMode')
        },
        files: {}
      }
    };
    self.cssmin([
      'bower_components/normalize-css/normalize.css',
    ], self.appConfig.dist + '/css/vendor.min.css');
    self.cssmin([
      self.appConfig.dist + '/css/main.css',
    ], self.appConfig.dist + '/css/main.min.css');
    return self;
  };

  /**
   * Add files to the cssmin task.
   *
   * @since 1.0.0
   * @param {string[]} src - Source files
   * @param {string} dest - Destination file
   * @param {string} [cwd] - Cwd (optional)
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.cssmin = function(src, dest, cwd) {
    src = _cwd(src, cwd);
    if (dest in _cssminTaskConfig.dist.files) {
      var i, file;
      for (i = 0; i < src.length; i++) {
        file = src[i];
        _cssminTaskConfig.dist.files[dest].push(file);
      }
    } else {
      _cssminTaskConfig.dist.files[dest] = src;
    }
    return self;
  };

  /**
   * Set up the default uglify task config.
   *
   * @since 1.0.0
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.setUpDefaultUglifyTaskConfig = function() {
    _uglifyTaskConfig = {
      dist: {
        options: {
          sourceMap: self.grunt.option('devMode')
        },
        files: {}
      }
    };
    self.uglify([
      'modules/**/**/*.js',
      'services/**/**/*.js',
      'partials/**/**/*.js',
      'main.js',
    ], self.appConfig.dist + '/js/main.min.js', self.appConfig.dist + '/js/');
    self.uglify([
      '<%= appConfig.dist %>/js/vendor/**/**/*.js',
      'bower_components/modernizr/modernizr.js',
      'bower_components/foundation/js/foundation.js',
    ], self.appConfig.dist + '/js/vendor.min.js');
    self.uglify([
      'respond/src/respond.js',
      'html5shiv/dist/html5shiv.js',
    ], self.appConfig.dist + '/js/vendor/ie.min.js', 'bower_components/');
    return self;
  };

  /**
   * Add files to the uglify task.
   *
   * @since 1.0.0
   * @param {string[]} src - Source files
   * @param {string} dest - Destination file
   * @param {string} [cwd] - Cwd (optional)
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.uglify = function(src, dest, cwd) {
    src = _cwd(src, cwd);
    if (dest in _uglifyTaskConfig.dist.files) {
      var i, file;
      for (i = 0; i < src.length; i++) {
        file = src[i];
        _uglifyTaskConfig.dist.files[dest].push(file);
      }
    } else {
      _uglifyTaskConfig.dist.files[dest] = src;
    }
    return self;
  };

  /**
   * Add a new task.
   *
   * @since 1.0.0
   * @param {string} name
   * @param {string} config
   * @return {object|boolean} - BambeeGrunt instance or false if task already exists
   */
  BambeeGrunt.prototype.addTask = function(name, config) {
    if (name in self.taskConfigs) {
      return false;
    }
    self.taskConfigs[name] = config;
    self.taskList.push(name);
    return self;
  };

  /**
   * Set up and register all tasks.
   *
   * @since 1.0.0
   * @return {object} - BambeeGrunt instance
   */
  BambeeGrunt.prototype.setUpTasks = function() {
    require('load-grunt-tasks')(self.grunt, {
      config: 'node_modules/bambee-grunt/package.json'
    });
    require('load-grunt-tasks')(self.grunt);
    self.grunt.initConfig(self.taskConfigs);

    self.grunt.registerTask('default', function() {
      var taskList = self.taskList;
      if (self.grunt.option('devMode') || self.grunt.option('watch')) {
        taskList.push('watch');
      }
      self.grunt.task.run(taskList);
    });
    return self;
  };

  return BambeeGrunt;
})();

module.exports = BambeeGrunt;