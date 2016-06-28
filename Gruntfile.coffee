module.exports = (grunt) ->
  BambeeGrunt = require 'bambee-grunt'
  bambeeGrunt = new BambeeGrunt grunt

  # Add bower_components to vendor.min.js
  bambeeGrunt.uglify ([
    # 'package/path/to/jsFile.js'
  ]), bambeeGrunt.appConfig.dist + '/js/vendor.min.js', 'bower_components/'

  # Add bower_components to vendor.min.css
  bambeeGrunt.cssmin ([
    # 'package/path/to/cssOrScssFile.css'
  ]), bambeeGrunt.appConfig.dist + '/css/vendor.min.css', 'bower_components/'

  # Copy non css/js files. E.g. fonts
  bambeeGrunt.copy([
      # 'foundation-icon-fonts/*.{eot,svg,ttf,woff}',
      bambeeGrunt.appConfig.dist + '/fonts/', 'bower_components/'
  ])