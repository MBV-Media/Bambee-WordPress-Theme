/**
 * @version 1.0.0
 * @author HT <h.terhoeven@rto.de>
 * @license MIT
 */
'use strict';

var jsonFile    = require('jsonfile');
var sass        = require('gulp-sass');
var coffee      = require('gulp-coffee');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');
var imagemin    = require('gulp-imagemin');
var sourcemaps  = require('gulp-sourcemaps');
var del         = require('del');

/**
 * Abstract class representing the gulptfile.
 * Provides methods to customize gulp tasks.
 *
 * @since 1.0.0
 * @class
 */
var BambeeGulp = (function() {

  var self,
    _gulp,
    jsonFile,
  //plugins,
    _pkg,
    _src,
    _dist,
    _paths,
    _vendorStyleList;

  /**
   *
   * @param gulp
   * @constructor
   */
  function BambeeGulp(gulp) {

    self = this;

    _gulp = gulp;

    jsonFile = require('jsonfile');
    //plugins = require('gulp-load-plugins')();

    _pkg = jsonFile.readFileSync('package.json');

    _src = './src';
    _dist = '../themes/' + _pkg.name + '-gulp';

    _paths = {
      src: './src',
      scss: {
        main: [
          './src/css/**/*.scss',
          '!./src/css/admin.scss',
          '!./src/css/vendor/**/*.scss'
        ],
        admin: [
          './src/css/main.scss'
        ],
        vendor: [
          './src/css/vendor/**/*.scss',
        ]
      },
      coffee: {
        main: [
          './src/js/**/*.coffee',
          '!./src/js/vendor/**/*.coffee'
        ],
        vendor: [
          './src/js/vendor/**/*.coffee',
          './bower_components/modernizr/modernizr.js',
          './bower_components/foundation-sites/dist/foundation.js'
        ]
      },
      images: './src/img/**/*',
      dest: '../themes/' + _pkg.name + '-gulp'
    };
  };

  /**
   *
   */
  BambeeGulp.prototype.registerTaks = function() {
    _gulp.task('default', ['copy', 'compile:scss', 'compile:vendorStyles', 'compile:coffee', 'images']);
    _gulp.task('clean', self.taskClean);
    _gulp.task('copy', ['clean'], self.taskCopy);
    _gulp.task('compile:scss', ['clean'], self.taskCompileScss);
    _gulp.task('compile:vendorStyles', ['clean'], self.taskCompileVendorStyles);
    _gulp.task('compile:coffee', ['clean'], self.taskCompileCoffee);
    _gulp.task('images', ['clean'], self.taskImages);
    _gulp.task('watch', self.taskWatch);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskClean = function() {
    return del([_dist], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCopy = function() {
    return _gulp.src([
        _paths.src + '/**/**/*',
        '!**/**/*.{scss,coffee}',
        '!composer.{json,lock}',
      ])
      .pipe(_gulp.dest(_paths.dest));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScss = function() {
    return _gulp.src(_paths.scss.main)
      .pipe(sourcemaps.init({
        loadMaps: true
      }))
      .pipe(sass({
        outputStyle: 'compressed',
        lineNumbers: true,
        //sourcemap: true,
        includePaths: [
          'bower_components/bambee-sass',
          'bower_components/foundation-sites/scss',
          require('node-neat').includePaths,
          require('node-bourbon').includePaths
        ]
      }).on('error', sass.logError))
      .pipe(_gulp.dest(_paths.dest + '/css'))
      .pipe(concat('main.min.css'))
      .pipe(sourcemaps.write('./'))
      .pipe(_gulp.dest(_paths.dest + '/css'))
      /*.pipe(livereload())*/;
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileVendorStyles = function() {
    console.log(_paths.scss.vendor);
    return _gulp.src(_paths.scss.vendor)
      .pipe(sourcemaps.init({
        loadMaps: true
      }))
      .pipe(sass({
        outputStyle: 'compressed',
        lineNumbers: true,
      }).on('error', sass.logError))
      .pipe(_gulp.dest(_paths.dest + '/css'))
      .pipe(concat('vendor.min.css'))
      .pipe(sourcemaps.write('./'))
      .pipe(_gulp.dest(_paths.dest + '/css'))
  };

  /**
   * Minify and copy all JavaScript (except vendor scripts)
   * with sourcemaps all the way down
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileCoffee = function() {
    // Minify and copy all JavaScript (except vendor scripts)
    // with sourcemaps all the way down
    return _gulp.src(_paths.coffee.main)
      .pipe(sourcemaps.init({
        loadMaps: true
      }))
      .pipe(coffee())
      .pipe(_gulp.dest(_paths.dest + '/js'))
      .pipe(uglify())
      .pipe(concat('main.min.js'))
      .pipe(sourcemaps.write('./'))
      .pipe(_gulp.dest(_paths.dest + '/js'));
  };

  /**
   * Copy and optimize all static images
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskImages = function() {
    return _gulp.src(_paths.images)
      // Pass in options to the task
      .pipe(imagemin({optimizationLevel: 5}))
      .pipe(_gulp.dest(_paths.dest + '/img'));
  };

  /**
   * Rerun the task when a file changes
   */
  BambeeGulp.prototype.taskWatch = function() {
    _gulp.watch(paths.scss.main, ['compile:scss']);
    _gulp.watch(paths.coffee.main, ['compile:coffee,main']);
    _gulp.watch(paths.images, ['images']);
  };

  return BambeeGulp;
})();

module.exports = BambeeGulp;
