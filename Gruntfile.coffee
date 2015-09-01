module.exports = (grunt) ->
  # Load grunt tasks automatically
  require('load-grunt-tasks')(grunt)

  pkg = grunt.file.readJSON 'package.json'
  appConfig =
    src: 'src'
    dist: '../themes/' + pkg.name

  grunt.initConfig
    appConfig: appConfig

    clean:
      options:
        force: yes
      build:
        src: [
          '<%= appConfig.dist %>'
        ]

    copy:
      dist:
        files: [
          expand: yes
          cwd: '<%= appConfig.src %>/'
          src: [
            '**/**/*'
            '!**/**/*.{scss,coffee}'
          ]
          dest: '<%= appConfig.dist %>/'
          filter: 'isFile'
        ]

    scsslint:
      options:
        config: 'config/.scss-lint.yml'
      dist: [
        '<%= appConfig.src %>/**/**/*.scss'
      ]

    compass:
      dist:
        options:
          config: 'config/config.rb'

    coffeelint:
      options:
        configFile: 'config/coffeelint.json'
      src: ['<%= appConfig.src %>/js/**/**/*.coffee']

    coffee:
      dist:
        expand: yes
        cwd: '<%= appConfig.src %>/js'
        src: ['**/**/*.coffee']
        dest: '<%= appConfig.dist %>/js/'
        ext: '.js'

    cssmin:
      dist:
        options:
          keepSpecialComments: 0
          sourceMap: grunt.option 'devMode'
        files:
          '<%= appConfig.dist %>/css/vendor.min.css': [
            'bower_components/normalize-css/normalize.css'
          ]
          '<%= appConfig.dist %>/css/main.min.css': [
            '<%= appConfig.dist %>/css/main.css'
          ]

    uglify:
      dist:
        options:
          sourceMap: grunt.option 'devMode'
        files:
          '<%= appConfig.dist %>/js/main.min.js': [
            '<%= appConfig.dist %>/js/modules/**/**/*.js'
            '<%= appConfig.dist %>/js/services/**/**/*.js'
            '<%= appConfig.dist %>/js/partials/**/**/*.js'
            '<%= appConfig.dist %>/js/main.js'
          ]
          '<%= appConfig.dist %>/js/vendor.min.js': [
            '<%= appConfig.dist %>/js/vendor/**/**/*.js'
            'bower_components/modernizr/modernizr.js'
            'bower_components/foundation/js/foundation.js'
          ]
          '<%= appConfig.dist %>/js/vendor/ie.min.js': [
            'bower_components/respond/src/respond.js'
            'bower_components/html5shiv/dist/html5shiv.js'
          ]

    notify:
      copy:
        options:
          message: 'Copy task finished running.'
      cssmin:
        options:
          message: 'Compass and cssmin task finished running.'
      uglify:
        options:
          message: 'CoffeeScript and uglify task finished running.'

    watch:
      options:
        livereload: yes
      copy:
        files: [
          '<%= appConfig.src %>/**/**/*'
          '!<%= appConfig.src %>/**/**/*.{scss,coffee}'
        ]
        tasks: [
          'copy'
          'notify:copy'
        ]
      compass:
        files: [
          '**/**/*.scss'
        ]
        tasks: [
          'scsslint'
          'compass'
        ]
      coffee:
        files: '<%= coffee.dist.src %>'
        tasks: [
          'coffeelint'
          'coffee'
        ]
      cssmin:
        files: [
          '<%= appConfig.dist %>/css/**/**/*.css'
          '!<%= appConfig.dist %>/css/**/**/*.min.css'
        ]
        tasks: [
          'cssmin'
          'notify:cssmin'
        ]
      uglify:
        files: [
          '<%= appConfig.dist %>/js/**/**/*.js'
          '!<%= appConfig.dist %>/js/**/**/*.min.js'
        ]
        tasks: [
          'uglify'
          'notify:uglify'
        ]

  # Register tasks
  grunt.registerTask 'default', ->
    taskList = [
      'clean'
      'copy'
      'scsslint'
      'compass'
      'coffeelint'
      'coffee'
      'cssmin'
      'uglify'
    ]
    if grunt.option('devMode') or grunt.option('watch')
      taskList.push 'watch'
    grunt.task.run taskList