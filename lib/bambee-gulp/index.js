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
    _paths,
    includePaths,
    sassConfig,
    sourcemapsConfig,
    autoprefixerConfig,
    coffeelintReporter,
    postcssReporter,
    postcssScss,
    stylelint,
    spritesmith,
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

    postcssReporter = require('postcss-reporter');
    postcssScss = require('postcss-scss');
    stylelint   = require('stylelint');
    spritesmith   = require('gulp.spritesmith-multi');

    _pkg = _jsonFile.readFileSync('package.json');
    _pkg.name += '-gulp';

    var options = {
      boolean: [
        'dev'
      ],
    };
    _args = _minimist(process.argv.slice(2), options);

    var src = 'src'
    var dist = '../themes/' + _pkg.name;

    _paths = {
      src: {
        copy: [
          src + '/**/**/*',
          '!' + src + '/style.scss',
          '!' + src + '/css/**/*',
          '!' + src + '/js/**/*',
          '!' + src + '/img/**/*',
          '!' + src + '/composer.{json,lock}',
        ],
        scss: {
          main: [
            src + '/css/**/*.scss',
            '!' + src + '/css/admin.scss',
          ],
          admin: [
            src + '/css/admin.scss'
          ],
          vendor: [
            './bower_components/normalize-css/normalize.css',
          ],
        },
        coffee: {
          main: [
            src + '/js/**/*.{js,coffee}',
            '!' + src + '/js/vendor'
          ],
          vendor: [
            //'./src/js/vendor/**/*.{js,coffee}',
            //'./bower_components/modernizr/modernizr.js',
            //'./bower_components/foundation-sites/dist/foundation.js'
          ],
        },
        js: {
          vendor: [
            src + '/js/vendor/**/*.js',
            './bower_components/modernizr/modernizr.js',
            './bower_components/foundation-sites/dist/foundation.js'
          ],
        },
        images: [
          src + '/img/**/*',
          '!' + src + '/img/sprites/**'
        ],
        sprites: src + '/img/sprites/**/*.png',
        spritesRetina: src + '/img/sprites/**/*-retina.png',
      },
      dist: {
        root: dist,
        css: dist + '/css',
        js: dist + '/js',
        img: dist + '/img',
        sprites: dist + '/img/sprites',
      },
    };

    includePaths = [
      'bower_components/bambee-sass',
      'bower_components/foundation-sites/scss',
      require('node-neat').includePaths,
      require('node-bourbon').includePaths
    ];

    sassConfig = {
      outputStyle: 'compressed',
      includePaths: includePaths,
    };

    sourcemapsConfig = {
      loadMaps: true
    };

    autoprefixerConfig = {
      browsers: ['> 5%', 'IE 9']
    };
  };

  /**
   *
   */
  BambeeGulp.prototype.registerTaks = function() {

    var defaultTaskDependencies = [
      'compile:scss:main',
      'compile:scss:vendor',
      'compile:coffee:main',
      'uglify:js:vendor',
      'images',
      'copy',
    ];

    var watchTaskDependencies = defaultTaskDependencies;

    if(_args.dev) {
      defaultTaskDependencies.push('watch');
    }

    _gulp.task('default', defaultTaskDependencies);

    _gulp.task('clean:css:main', self.taskCleanCssMain);
    _gulp.task('clean:css:vendor', self.taskCleanCssVendor);
    _gulp.task('clean:js:main', self.taskCleanJsMain);
    _gulp.task('clean:js:vendor', self.taskCleanJsVendor);
    _gulp.task('clean:images', self.taskCleanImages);
    _gulp.task('clean:copy', self.taskCleanCopy);
    _gulp.task('lint:scss:main', self.taskLintScssMain);
    _gulp.task('lint:coffee:main', self.taskLintCoffeeMain);
    _gulp.task('compile:scss:main', ['clean:css:main', 'lint:scss:main', 'sprites'], self.taskCompileScssMain);
    _gulp.task('compile:scss:vendor', ['clean:css:vendor'], self.taskCompileScssVendor);
    _gulp.task('compile:coffee:main', ['clean:js:main', 'lint:coffee:main'], self.taskCompileCoffeeMain);
    _gulp.task('uglify:js:vendor', ['clean:js:vendor'], self.taskUglifyJsVendor);
    _gulp.task('sprites', ['clean:images'], self.taskSprites);
    _gulp.task('images', ['sprites'], self.taskImages);
    _gulp.task('copy', ['clean:copy'], self.taskCopy);

    _gulp.task('watch', self.taskWatch);
  };

  /**
   *
   * @param styleFile
   */
  BambeeGulp.prototype.addVendorStyle = function(styleFile) {
    _paths.src.scss.vendor.push(styleFile);
  };

  /**
   *
   * @param scriptFile
   */
  BambeeGulp.prototype.addVendorScript = function(scriptFile) {
    _paths.src.js.vendor.push(scriptFile);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCssMain = function() {
    return _del([
      _paths.dist.css + '/{main,admin}.*',
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCssVendor = function() {
    return _del([
      _paths.dist.css + '/vendor.*',
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsMain = function() {
    return _del([
      _paths.dist.js + '/main.*'
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsVendor = function() {
    return _del([
      _paths.dist.js + '/vendor.*'
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanImages = function() {
    return _del([
      _paths.dist.img
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCopy = function() {
    return _del([
      _paths.dist.root + '/**/*',
      '!' + _paths.dist.root + '/css',
      '!' + _paths.dist.root + '/js',
      '!' + _paths.dist.root + '/img',
    ], {
      force: true
    });
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
        throwError: false,
        noPlugin: true,
      })
    ];

    var path =_paths.src.scss.main;
    /* don't lint foundation-settings */
    path.push('!src/css/vendor/**/*.scss');

    return _gulp.src(path)
      .pipe(_plugins.postcss(processors, {syntax: postcssScss}));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskLintCoffeeMain = function() {
    return _gulp.src(_paths.src.coffee.main)
      .pipe(_plugins.coffeelint('./node_modules/bambee-gulp/configLintCoffee.json'))
      .pipe(_plugins.coffeelint.reporter('default'));
    //.pipe(_plugins.coffeelint.reporter('coffeelint-stylish'));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssMain = function() {
    var styleCSS = _gulp.src('src/style.scss')
      .pipe(_plugins.replace('#{pkg(name)}', _pkg.name))
      .pipe(_plugins.replace('#{pkg(description)}', _pkg.description))
      .pipe(_plugins.replace('#{pkg(author)}', _pkg.author))
      .pipe(_plugins.replace('#{pkg(version)}', _pkg.version))
      .pipe(_plugins.replace('#{year()}', new Date().getFullYear()))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_gulp.dest(_paths.dist.root));

    var mainCSS = _gulp.src(_paths.src.scss.main)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.autoprefixer(autoprefixerConfig))
      .pipe(_plugins.concat('main.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist.css))
      .pipe(_plugins.livereload());

    var adminCSS = _gulp.src(_paths.src.scss.admin)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.concat('admin.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist.css));

    return _merge(styleCSS, mainCSS);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssVendor = function() {
    return _gulp.src(_paths.src.scss.vendor)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.sass(sassConfig)
        .on('error', _plugins.sass.logError))
      .pipe(_plugins.autoprefixer(autoprefixerConfig))
      .pipe(_plugins.concat('vendor.min.css'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist.css));
  };

  /**
   * Minify and copy all JavaScript (except vendor scripts)
   * with sourcemaps all the way down
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileCoffeeMain = function() {
    return _gulp.src(_paths.src.coffee.main)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(_plugins.coffee()
        .on('error', _plugins.util.log))
      .pipe(_plugins.jshint())
      .pipe(_plugins.uglify())
      .pipe(_plugins.concat('main.min.js'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist.js))
      .pipe(_plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskUglifyJsVendor = function() {
    return _gulp.src(_paths.src.js.vendor)
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.init(sourcemapsConfig)))
      //.pipe(_plugins.coffee())
      .pipe(_plugins.uglify())
      .pipe(_plugins.concat('vendor.min.js'))
      .pipe(_plugins.if(_args.dev, _plugins.sourcemaps.write('./')))
      .pipe(_gulp.dest(_paths.dist.js));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskSprites = function() {
    return _gulp.src(_paths.src.sprites)
      .pipe(spritesmith({
        imgName: 'sprite.png',
        retinaSrcFilter: [_paths.src.spritesRetina],
        retinaImgName: 'sprite-retina.png',
        cssName: 'sprite.css',
      }))
        .on('error', _plugins.util.log)
      .pipe(_gulp.dest(_paths.dist.sprites))
      /*.pipe(_plugins.livereload())*/;
  }

  /**
   * Copy and optimize all static images
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskImages = function() {
    return _gulp.src(_paths.src.images, {nodir: true})
      // Pass in options to the task
      .pipe(_plugins.imagemin({optimizationLevel: 5}))
      .pipe(_gulp.dest(_paths.dist.img))
      .pipe(_plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCopy = function() {
    return _gulp.src(_paths.src.copy)
      .pipe(_gulp.dest(_paths.dist.root))
      .pipe(_plugins.livereload());
  };

  /**
   * Rerun the task when a file changes
   */
  BambeeGulp.prototype.taskWatch = function() {
    var watcher = [];

    _plugins.livereload.listen();

    watcher.push(_gulp.watch(_paths.src.scss.main, ['compile:scss:main']));
    watcher.push(_gulp.watch(_paths.src.coffee.main, ['compile:coffee:main']));
    watcher.push(_gulp.watch(_paths.src.images, ['images']));
    watcher.push(_gulp.watch(_paths.src.copy, ['copy']));

    watcher.forEach(function(e, i, a) {
      e.on('change', function(event) {
        var file = event.path.substring(event.path.lastIndexOf('\\') + 1);
        console.log('\n\t' + event.type + ': ' + file + '\n');
      });
    });
  };

  return BambeeGulp;
})();

module.exports = BambeeGulp;
