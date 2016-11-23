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
    args,
    jsonFile,

    gulp,
    plugins,
    minimist,
    merge,
    del,
    pkg,
    paths,

    coffeelintReporter,
    postcssReporter,
    postcssScss,
    stylelint,

    includePaths,
    sassConfig,
    sourcemapsConfig,
    autoprefixerConfig;

  /**
   *
   * @param gulp
   * @constructor
   */
  function BambeeGulp(_gulp) {

    self = this;

    gulp = _gulp;

    jsonFile = require('jsonfile');
    minimist = require('minimist');
    plugins = require('gulp-load-plugins')();
    merge = require('merge-stream');
    del = require('del');

    postcssReporter = require('postcss-reporter');
    postcssScss = require('postcss-scss');
    stylelint   = require('stylelint');

    pkg = jsonFile.readFileSync('package.json');

    var options = {
      boolean: [
        'dev'
      ],
    };
    args = minimist(process.argv.slice(2), options);

    var src = 'src';
    var dist = '../themes/' + pkg.name;

    paths = {
      src: {
        copy: [
          src + '/**/**/*',
          '!' + src + '/.sprites-cache',
          '!' + src + '/style.scss',
          '!' + src + '/css/**/*',
          '!' + src + '/js/**/*',
          '!' + src + '/img/**/*',
          '!' + src + '/composer.{json,lock}',
        ],
        scss: {
          main: [
            src + '/css/**/*.scss',
            src + '/.sprites-cache/*.css',
            '!' + src + '/css/admin.scss',
          ],
          admin: [
            src + '/css/admin.scss'
          ]
        },
        coffee: {
          main: [
            src + '/js/**/*.{js,coffee}',
            '!' + src + '/js/vendor'
          ],
        },
        js: {
          vendor: jsonFile.readFileSync('src/js/vendor.js.json')
        },
        images: [
          src + '/img/**/*',
          src + '/.sprites-cache/*.png',
          '!' + src + '/img/sprites/**/*'
        ],
        sprites: [
          src + '/img/sprites/**/*.png',
          '!' + src + '/img/sprites/**/*-retina.png',
        ],
        spritesRetina: [
          src + '/img/sprites/**/*-retina.png',
          //'!' + src + '/img/sprites/**/*-retina.png',
        ],
      },
      dist: {
        root: dist,
        css: dist + '/css',
        js: dist + '/js',
        img: dist + '/img',
        sprites: src + '/.sprites-cache',
      },
    };

    //_paths = _jsonFile.readFileSync('package.json');

    includePaths = [
      'bower_components',
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
      'compile:coffee:main',
      'uglify:js:vendor',
      'images',
      'copy',
    ];

    var watchTaskDependencies = defaultTaskDependencies;

    if(args.watch) {
      defaultTaskDependencies.push('watch');
    }

    gulp.task('default', defaultTaskDependencies);

    gulp.task('clean:sprites', self.taskCleanSprites);
    gulp.task('clean:images', self.taskCleanImages);
    gulp.task('clean:css:main', self.taskCleanCssMain);
    gulp.task('clean:js:main', self.taskCleanJsMain);
    gulp.task('clean:js:vendor', self.taskCleanJsVendor);
    gulp.task('clean:copy', self.taskCleanCopy);
    gulp.task('lint:scss:main', self.taskLintScssMain);
    gulp.task('lint:coffee:main', self.taskLintCoffeeMain);
    gulp.task('sprites', ['clean:sprites'], self.taskSprites);
    gulp.task('images', ['clean:images', 'sprites'], self.taskImages);
    gulp.task('compile:scss:main', ['clean:css:main', 'lint:scss:main', 'sprites'], self.taskCompileScssMain);
    gulp.task('compile:coffee:main', ['clean:js:main', 'lint:coffee:main'], self.taskCompileCoffeeMain);
    gulp.task('uglify:js:vendor', ['clean:js:vendor'], self.taskUglifyJsVendor);
    gulp.task('copy', ['clean:copy'], self.taskCopy);

    gulp.task('watch:compile:scss:main', ['clean:css:main', 'lint:scss:main'], self.taskCompileScssMain);
    gulp.task('watch:images', ['clean:images'], self.taskImages);
    gulp.task('watch', [
      'compile:scss:main',
      'compile:coffee:main',
      'uglify:js:vendor',
      'images',
      'copy'
    ], self.taskWatch);

    gulp.task('reload', ['copy'], self.reload);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCssMain = function() {
    return del([
      paths.dist.css + '/{main,admin}.*',
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsMain = function() {
    return del([
      paths.dist.js + '/main.*'
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsVendor = function() {
    return del([
      paths.dist.js + '/vendor.*'
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanSprites = function() {
    return del([
      paths.dist.sprites
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanImages = function() {
    return del([
      paths.dist.img + '/*.*'
    ], {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCopy = function() {
    return del([
      paths.dist.root + '/**/*',
      '!' + paths.dist.root + '/style.css',
      '!' + paths.dist.css,
      '!' + paths.dist.css + '/**/*',
      '!' + paths.dist.js,
      '!' + paths.dist.js + '/**/*',
      '!' + paths.dist.img,
      '!' + paths.dist.img + '/**/*',
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
    var stylelintConfig = jsonFile.readFileSync('node_modules/bambee-gulp/configLintScss.json');

    var processors = [
      stylelint(stylelintConfig),
      postcssReporter({
        clearMessages: true,
        throwError: false,
        noPlugin: true,
      })
    ];

    var path = JSON.parse(JSON.stringify(paths.src.scss.main));
    /* don't lint generated sprite css files */
    path[1] = '!' + path[1];
    /* don't lint foundation-settings */
    path.push('!src/css/vendor/**/*.scss');

    return gulp.src(path)
      .pipe(plugins.postcss(processors, {syntax: postcssScss}));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskLintCoffeeMain = function() {
    return gulp.src(paths.src.coffee.main)
      .pipe(plugins.coffeelint('./node_modules/bambee-gulp/configLintCoffee.json'))
      .pipe(plugins.coffeelint.reporter('default'));
    //.pipe(_plugins.coffeelint.reporter('coffeelint-stylish'));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssMain = function() {
    var styleCSS = gulp.src('src/style.scss')
      .pipe(plugins.replace('#{pkg(name)}', pkg.name))
      .pipe(plugins.replace('#{pkg(description)}', pkg.description))
      .pipe(plugins.replace('#{pkg(author)}', pkg.author))
      .pipe(plugins.replace('#{pkg(version)}', pkg.version))
      .pipe(plugins.replace('#{year()}', new Date().getFullYear()))
      .pipe(plugins.sassBulkImport())
      .pipe(plugins.sass(sassConfig)
        .on('error', plugins.sass.logError))
      .pipe(gulp.dest(paths.dist.root))
      .pipe(plugins.livereload());

    var mainCSS = gulp.src(paths.src.scss.main)
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.sass(sassConfig)
        .on('error', plugins.sass.logError))
      .pipe(plugins.autoprefixer(autoprefixerConfig))
      .pipe(plugins.concat('main.min.css'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.css))
      .pipe(plugins.livereload());

    var adminCSS = gulp.src(paths.src.scss.admin)
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.sass(sassConfig)
        .on('error', plugins.sass.logError))
      .pipe(plugins.concat('admin.min.css'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.css))
      .pipe(plugins.livereload());

    return merge(styleCSS, mainCSS);
  };

  /**
   * Minify and copy all JavaScript (except vendor scripts)
   * with sourcemaps all the way down
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileCoffeeMain = function() {
    return gulp.src(paths.src.coffee.main)
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.coffee()
        .on('error', plugins.util.log))
      .pipe(plugins.jshint())
      .pipe(plugins.uglify())
      .pipe(plugins.concat('main.min.js'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.js))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskUglifyJsVendor = function() {
    return gulp.src(jsonFile.readFileSync('src/js/vendor.js.json'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      //.pipe(_plugins.coffee())
      .pipe(plugins.uglify())
      .pipe(plugins.concat('vendor.min.js'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.js))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskSprites = function() {

    var spritesmithMultiOptions = {
      //imgName: 'sprite.png',
      //cssName: 'sprite.css',
      spritesmith: function(options) {
        options.imgPath = '../img/' + options.imgName
      }
    };

    if(args.retina) {
      spritesmithMultiOptions.retinaSrcFilter = [paths.src.spritesRetina];
      spritesmithMultiOptions.retinaImgName = 'sprite-retina.png';
    }

    return gulp.src(paths.src.sprites)
      .pipe(plugins.spritesmithMulti(spritesmithMultiOptions))
      .on('error', plugins.util.log)
      .pipe(gulp.dest(paths.dist.sprites));
  };

  /**
   * Copy and optimize all static images
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskImages = function() {
    return gulp.src(paths.src.images, {nodir: true})
    // Pass in options to the task
      .pipe(plugins.imagemin({optimizationLevel: 5}))
      .pipe(gulp.dest(paths.dist.img))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCopy = function() {
    return gulp.src(paths.src.copy, {nodir: true})
      .pipe(gulp.dest(paths.dist.root));
  };

  /**
   * Rerun the task when a file changes
   */
  BambeeGulp.prototype.taskWatch = function() {
    var watcher = [];

    plugins.livereload.listen();

    watcher.push(gulp.watch(paths.src.scss.main, ['watch:compile:scss:main']));
    watcher.push(gulp.watch(paths.src.coffee.main, ['compile:coffee:main']));
    watcher.push(gulp.watch('src/js/vendor.js.json', ['uglify:js:vendor']));
    watcher.push(gulp.watch(paths.src.images, ['watch:images']));
    watcher.push(gulp.watch(paths.src.copy, ['copy', 'reload']));

    watcher.forEach(function(e, i, a) {
      e.on('change', self.onChangeCallback);
    });
  };

  BambeeGulp.prototype.reload = function() {
    plugins.livereload.reload('index.php');
  }

  BambeeGulp.prototype.onChangeCallback = function(event) {
    var now = new Date(),
      time = now.toTimeString().substr(0,8);
    console.log('\n' + plugins.util.colors.blue(time) + ' ' + event.type + ':\n\t' + event.path + '\n');
  };

  return BambeeGulp;
})();

module.exports = BambeeGulp;
