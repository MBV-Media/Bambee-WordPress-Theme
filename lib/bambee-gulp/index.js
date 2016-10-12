/**
 * @version 1.0.0
 * @author HT <h.terhoeven@rto.de>
 * @license MIT
 */
'use strict';



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
    _args,
    _jsonFile,
    _minimist,
    _plugins,
    _merge,
    _del,
    _pkg,
    _dist,
    _paths,
    includePaths,
    sassConfig,
    sourcemapsConfig,
    postcssReporter,
    postcssScss,
    stylelint,
    _vendorStyleList;

  /**
   *
   * @param gulp
   * @constructor
   */
  function BambeeGulp(gulp) {

    self = this;

    _gulp = gulp;

    _jsonFile = require('jsonfile');
    _minimist = require('minimist');
    _plugins = require('gulp-load-plugins')();
    _merge = require('merge-stream');
    _del = require('del');

    postcssReporter    = require('postcss-reporter');
    postcssScss = require('postcss-scss');
    stylelint   = require('stylelint');

    _pkg = _jsonFile.readFileSync('package.json');
    _pkg.name += '-gulp';

    var options = {
      boolean: [
        'dev'
      ],
    };
    _args = _minimist(process.argv.slice(2), options);

    _dist = '../themes/' + _pkg.name;

    _paths = {
      src: './src',
      scss: {
        main: [
          './src/css/**/*.scss',
          '!./src/css/admin.scss',
        ],
        admin: [
          './src/css/admin.scss'
        ],
        vendor: [
          './bower_components/normalize-css/normalize.css',
        ],
      },
      coffee: {
        main: [
          './src/js/**/*.{js,coffee}',
          '!./src/js/vendor'
        ],
        vendor: [
          //'./src/js/vendor/**/*.{js,coffee}',
          //'./bower_components/modernizr/modernizr.js',
          //'./bower_components/foundation-sites/dist/foundation.js'
        ],
      },
      js: {
        vendor: [
          './src/js/vendor/**/*.js',
          './bower_components/modernizr/modernizr.js',
          './bower_components/foundation-sites/dist/foundation.js'
        ],
      },
      images: './src/img/**/*',
      dist: '../themes/' + _pkg.name,
    };

    includePaths = [
      'bower_components/bambee-sass',
      'bower_components/foundation-sites/scss',
      require('node-neat').includePaths,
      require('node-bourbon').includePaths
    ];

    sassConfig = {
      outputStyle: 'compressed',
      lineNumbers: true,
      includePaths: includePaths,
    };

    sourcemapsConfig = {
      loadMaps: true
    };
  };

  /**
   *
   */
  BambeeGulp.prototype.registerTaks = function() {
    _gulp.task('default', [
      'copy',
      'compile:scss:main',
      'compile:scss:vendor',
      'compile:coffee:main',
      'uglify:js:vendor',
      /*'images',*/
      /*'watch',*/
    ]);
    _gulp.task('clean', self.taskClean);
    _gulp.task('copy', ['clean'], self.taskCopy);
    _gulp.task('lint:scss:main', self.taskLintScssMain);
    _gulp.task('compile:scss:main', ['clean', 'lint:scss:main'], self.taskCompileScssMain);
    _gulp.task('compile:scss:vendor', ['clean'], self.taskCompileScssVendor);
    _gulp.task('compile:coffee:main', ['clean'], self.taskCompileCoffeeMain);
    _gulp.task('uglify:js:vendor', ['clean'], self.taskUglifyJsVendor);
    _gulp.task('images', ['clean'], self.taskImages);
    _gulp.task('watch', self.taskWatch);
  };

  /**
   *
   * @param styleFile
   */
  BambeeGulp.prototype.addVendorStyle = function(styleFile) {
    _paths.scss.vendor.push(styleFile);
  };

  /**
   *
   * @param scriptFile
   */
  BambeeGulp.prototype.addVendorScript = function(scriptFile) {
    _paths.js.vendor.push(scriptFile);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskClean = function() {
    return _del([_dist], {
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
      .pipe(_gulp.dest(_paths.dist));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskLintScssMain = function() {

    // Stylelint config rules
    var stylelintConfig = _jsonFile.readFileSync('node_modules/bambee-gulp/configLintScss.json');

    var processors = [
      stylelint(stylelintConfig),
      postcssReporter({
        clearMessages: true,
        throwError: true
      })
    ];

    var path =_paths.scss.main;
    path.push('!./src/css/vendor/**/*.scss');

    return _gulp.src(path)
      .pipe(_plugins.postcss(processors, {syntax: postcssScss})
        .on('error', function(error) {}));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssMain = function() {

    var styleCSS = _gulp.src('./src/style.scss')
      .pipe(_plugins.replace('#{pkg(name)}', _pkg.name))
      .pipe(_plugins.replace('#{pkg(description)}', _pkg.description))
      .pipe(_plugins.replace('#{pkg(author)}', _pkg.author))
      .pipe(_plugins.replace('#{pkg(version)}', _pkg.version))
      .pipe(_plugins.replace('#{year()}', new Date().getFullYear()))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_gulp.dest(_paths.dist));

    var mainCSS = _gulp.src(_paths.scss.main)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.concat('main.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist + '/css'));

    var adminCSS = _gulp.src(_paths.scss.admin)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.concat('admin.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist + '/css'));

    return _merge(styleCSS, mainCSS);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssVendor = function() {
    return _gulp.src(_paths.scss.vendor)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.concat('vendor.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist + '/css'));
  };

  /**
   * Minify and copy all JavaScript (except vendor scripts)
   * with sourcemaps all the way down
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileCoffeeMain = function() {
    return _gulp.src(_paths.coffee.main)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.coffee()
        .on('error', _plugins.util.log))
      .pipe(_plugins.jshint())
      .pipe(_plugins.uglify())
      .pipe(_plugins.concat('main.min.js'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist + '/js'));
  };

  BambeeGulp.prototype.taskUglifyJsVendor = function() {
    return _gulp.src(_paths.js.vendor)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      //.pipe(_plugins.coffee())
      .pipe(_plugins.uglify())
      .pipe(_plugins.concat('vendor.min.js'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist + '/js'));
  };

  /**
   * Copy and optimize all static images
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskImages = function() {
    return _gulp.src(_paths.images)
      // Pass in options to the task
      .pipe(_plugins.imagemin({optimizationLevel: 5}))
      .pipe(_gulp.dest(_paths.dist + '/img'));
  };

  /**
   * Rerun the task when a file changes
   */
  BambeeGulp.prototype.taskWatch = function() {
    _gulp.watch(_paths.scss.main, ['compile:scss']);
    _gulp.watch(_paths.coffee.main, ['compile:coffee.main']);
    _gulp.watch(_paths.images, ['images']);
    _gulp.watch([_paths.src + '/**/**/*',
      '!**/**/*.{scss,coffee}',
      '!composer.{json,lock}'], ['copy']);
  };

  return BambeeGulp;
})();

module.exports = BambeeGulp;
