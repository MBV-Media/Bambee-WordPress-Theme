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
    notifyConfig,
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
    plugins = require('gulp-load-plugins')();
    merge = require('merge-stream');
    del = require('del');

    postcssReporter = require('postcss-reporter');
    postcssScss = require('postcss-scss');
    stylelint   = require('stylelint');

    pkg = jsonFile.readFileSync('package.json');

    args = plugins.util.env;

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
          '!' + src + '/composer.{json,lock}'
        ],
        scss: {
          main: [
            src + '/.sprites-cache/**/*.css',
            src + '/css/**/*.scss',
            '!' + src + '/css/admin.scss'
          ],
          admin: [
            src + '/css/admin.scss'
          ]
        },
        coffee: {
          main: [
            src + '/js/**/*.{js,coffee}',
            '!' + src + '/js/vendor/**/*'
          ]
        },
        images: [
          src + '/img/**/*',
          src + '/.sprites-cache/*.png',
          '!' + src + '/img/sprites/**/*'
        ],
        sprites: [
          src + '/img/sprites/**/*.png',
          '!' + src + '/img/sprites/**/*-retina.png'
        ],
        spritesRetina: [
          src + '/img/sprites/**/*-retina.png'
        ]
      },
      dist: {
        root: dist,
        css: dist + '/css',
        js: dist + '/js',
        img: dist + '/img',
        sprites: src + '/.sprites-cache'
      }
    };

    includePaths = [
      'bower_components',
      'bower_components/foundation-sites/scss',
      require('node-neat').includePaths,
      require('node-bourbon').includePaths
    ];

    sassConfig = {
      outputStyle: 'compressed',
      includePaths: includePaths
    };

    sourcemapsConfig = {
      loadMaps: true
    };

    autoprefixerConfig = {
      browsers: ['> 5%', 'IE 9']
    };

    notifyConfig = {
      title: 'Created',
      message: '<%= file.path %>'
    };
  }

  /**
   *
   */
  BambeeGulp.prototype.registerTaks = function() {

    var defaultTaskDependencies = [
      'compile:scss:admin',
      'compile:scss:main',
      'compile:scss:style',
      'compile:coffee:main',
      'uglify:js:vendor',
      'images',
      'copy'
    ];

    var watchTaskDependencies = defaultTaskDependencies;

    if(args.watch) {
      defaultTaskDependencies.push('watch');
    }

    gulp.task('default', defaultTaskDependencies);

    gulp.task('clean:sprites', self.taskCleanSprites);
    gulp.task('clean:images', self.taskCleanImages);
    gulp.task('clean:css:admin', self.taskCleanCssAdmin);
    gulp.task('clean:css:main', self.taskCleanCssMain);
    gulp.task('clean:css:style', self.taskCleanCssStyle);
    gulp.task('clean:js:main', self.taskCleanJsMain);
    gulp.task('clean:js:vendor', self.taskCleanJsVendor);
    gulp.task('clean:copy', self.taskCleanCopy);
    gulp.task('lint:scss:main', self.taskLintScssMain);
    gulp.task('lint:coffee:main', self.taskLintCoffeeMain);
    gulp.task('sprites', ['clean:sprites'], self.taskSprites);
    gulp.task('images', ['clean:images', 'sprites'], self.taskImages);
    gulp.task('compile:scss:admin', ['clean:css:admin'/*, 'lint:scss:main'*/], self.taskCompileScssAdmin);
    gulp.task('compile:scss:main', ['clean:css:main', 'lint:scss:main', 'sprites'], self.taskCompileScssMain);
    gulp.task('compile:scss:style', ['clean:css:style'], self.taskCompileScssStyle);
    gulp.task('compile:coffee:main', ['clean:js:main', 'lint:coffee:main'], self.taskCompileCoffeeMain);
    gulp.task('uglify:js:vendor', ['clean:js:vendor'], self.taskUglifyJsVendor);
    gulp.task('copy', ['clean:copy'], self.taskCopy);

    gulp.task('watch:compile:scss:admin', ['clean:css:admin'/*, 'lint:scss:main'*/], self.taskCompileScssAdmin);
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
    return self.taskClean([
      paths.dist.css + '/main.*'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCssAdmin = function() {
    return self.taskClean([
      paths.dist.css + '/admin.*'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCssStyle = function() {
    return self.taskClean([
      paths.dist.root + '/style.scss'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsMain = function() {
    return self.taskClean([
      paths.dist.js + '/main.*'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanJsVendor = function() {
    return self.taskClean([
      paths.dist.js + '/vendor.*'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanSprites = function() {
    return self.taskClean([
      paths.dist.sprites
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanImages = function() {
    return self.taskClean([
      paths.dist.img + '/**/*'
    ]);
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCleanCopy = function() {
    return self.taskClean([
      paths.dist.root + '/**/*',
      '!' + paths.dist.root + '/style.css',
      '!' + paths.dist.css,
      '!' + paths.dist.css + '/**/*',
      '!' + paths.dist.js,
      '!' + paths.dist.js + '/**/*',
      '!' + paths.dist.img,
      '!' + paths.dist.img + '/**/*'
    ]);
  };

  /**
   *
   * @param files
   * @returns {*}
   */
  BambeeGulp.prototype.taskClean = function(files) {
    return del(files, {
      force: true
    });
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskLintScssMain = function() {

    // Stylelint config rules
    var stylelintConfig = jsonFile.readFileSync('node_modules/bambee-gulp/config/lintScss.json');

    var processors = [
      stylelint(stylelintConfig),
      postcssReporter({
        clearMessages: true,
        throwError: false,
        noPlugin: true
      })
    ];

    var path = self.cloneObject(paths.src.scss.main);
    path.push('!src/css/vendor/**/*.scss');
    path.push('!src/.sprites-cache/**/*.css');

    return gulp.src(path)
      .pipe(plugins.postcss(processors, {syntax: postcssScss}));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskLintCoffeeMain = function() {
    return gulp.src(paths.src.coffee.main)
      .pipe(plugins.coffeelint('./node_modules/bambee-gulp/config/lintCoffee.json'))
      .pipe(plugins.coffeelint.reporter('default'));
    //.pipe(_plugins.coffeelint.reporter('coffeelint-stylish'));
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssMain = function() {
    return self.taskCompileScss(paths.src.scss.main, 'main.min.css');
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssAdmin = function() {
    return self.taskCompileScss(paths.src.scss.admin, 'admin.min.css');
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScssStyle = function() {
    return gulp.src('src/style.scss')
      .pipe(plugins.replace('#{pkg(name)}', pkg.name))
      .pipe(plugins.replace('#{pkg(description)}', pkg.description))
      .pipe(plugins.replace('#{pkg(author)}', pkg.author))
      .pipe(plugins.replace('#{pkg(version)}', pkg.version))
      .pipe(plugins.replace('#{year()}', new Date().getFullYear()))
      .pipe(plugins.sassBulkImport())
      .pipe(plugins.sass(sassConfig)
        .on('error', plugins.sass.logError))
      .pipe(plugins.notify(notifyConfig))
      .pipe(gulp.dest(paths.dist.root))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @param src
   * @param outFileName
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileScss = function(src, outFileName) {
    return gulp.src(src)
      .pipe(plugins.sassBulkImport())
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.sass(sassConfig)
        .on('error', plugins.sass.logError))
      .pipe(plugins.autoprefixer(autoprefixerConfig))
      .pipe(plugins.concat(outFileName))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.css))
      .pipe(plugins.notify(notifyConfig))
      .pipe(plugins.livereload());
  };

  /**
   * Minify and copy all JavaScript (except vendor scripts)
   * with sourcemaps all the way down
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskCompileCoffeeMain = function() {
    return gulp.src(paths.src.coffee.main)
      .pipe(plugins.plumber(self.errorSilent))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.coffee())
      .pipe(plugins.jshint())
      .pipe(plugins.uglify())
      .pipe(plugins.concat('main.min.js'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.js))
      .pipe(plugins.notify(notifyConfig))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskUglifyJsVendor = function() {
    var src = self.getSrcFromJson('src/js/vendor.js.json');
    return gulp.src(src)
      .pipe(plugins.if(args.dev, plugins.sourcemaps.init(sourcemapsConfig)))
      .pipe(plugins.uglify()
        .on('error', plugins.util.log))
      .pipe(plugins.concat('vendor.min.js'))
      .pipe(plugins.if(args.dev, plugins.sourcemaps.write('./')))
      .pipe(gulp.dest(paths.dist.js))
      .pipe(plugins.notify(notifyConfig))
      .pipe(plugins.livereload());
  };

  /**
   *
   * @returns {*}
   */
  BambeeGulp.prototype.taskSprites = function() {

    var spritesmithMultiOptions = {
      spritesmith: function(options) {
        options.imgPath = '../img/' + options.imgName
      }
    };

    if(args.retina) {
      spritesmithMultiOptions.retinaSrcFilter = '*-retina.png';
      // spritesmithMultiOptions.retinaImgName = 'sprite-retina.png';
    }

    return gulp.src(paths.src.sprites)
      .pipe(plugins.spritesmithMulti(spritesmithMultiOptions))
      .on('error', plugins.util.log)
      .pipe(gulp.dest(paths.dist.sprites))
      /*.pipe(plugins.notify(notifyConfig))*/;
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
      .pipe(plugins.notify(notifyConfig))
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
    watcher.push(gulp.watch(paths.src.scss.admin, ['watch:compile:scss:admin']));
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
  };

  BambeeGulp.prototype.onChangeCallback = function(event) {
    var now = new Date(),
      time = now.toTimeString().substr(0,8);
    console.log('\n[' + plugins.util.colors.blue(time) + '] ' + event.type + ':\n\t' + event.path + '\n');
  };

  /**
   *
   */
  BambeeGulp.prototype.errorSilent = function() {};

  /**
   * Add cwd to file paths.
   *
   * @since 1.0.0
   * @param {string[]} files
   * @param {string} cwd
   * @return {string[]}
   */
  BambeeGulp.prototype.cwd = function(files, cwd) {
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
   *
   * @param jsonSrcFile
   * @returns {Array}
   */
  BambeeGulp.prototype.getSrcFromJson = function(jsonSrcFile) {
    var sources = jsonFile.readFileSync(jsonSrcFile);
    var src = [];

    for(var i = 0; i < sources.length; ++i) {
      src = src.concat(self.cwd(sources[i].files, sources[i].cwd));
    }

    return src;
  };

  /**
   * Clone an object
   *
   * @param object
   */
  BambeeGulp.prototype.cloneObject = function(object) {
    return JSON.parse(JSON.stringify(object));
  };

  return BambeeGulp;
})();

module.exports = BambeeGulp;
