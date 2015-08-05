module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    clean:
      options:
        force: yes
      build:
        src: [
          '../themes/<%= pkg.name %>'
        ]
    copy:
      dist:
        files: [
          expand: yes
          cwd: 'src/'
          src: [
            '**/**/*'
            '!**/**/*.{scss,coffee}'
          ]
          dest: '../themes/<%= pkg.name %>/'
          filter: 'isFile'
        ]
    scsslint:
      options:
        config: 'config/.scss-lint.yml'
      dist: [
        'src/**/**/*.scss'
      ]
    compass:
      dist:
        options:
          config: 'config/config.rb'
    coffeelint:
      options:
        configFile: 'config/coffeelint.json'
      src: ['src/js/**/**/*.coffee']
    coffee:
      dist:
        expand: yes
        cwd: 'src/js'
        src: ['**/**/*.coffee']
        dest: '../themes/<%= pkg.name %>/js/'
        ext: '.js'
    cssmin:
      dist:
        options:
          keepSpecialComments: 0
          sourceMap: grunt.option 'devMode'
        files:
          '../themes/<%= pkg.name %>/css/vendor.min.css': [
            'bower_components/normalize-css/normalize.css'
          ]
          '../themes/<%= pkg.name %>/css/main.min.css': [
            '../themes/<%= pkg.name %>/css/main.css'
          ]
    uglify:
      dist:
        options:
          sourceMap: grunt.option 'devMode'
        files:
          '../themes/<%= pkg.name %>/js/main.min.js': [
            '../themes/<%= pkg.name %>/js/modules/**/**/*.js'
            '../themes/<%= pkg.name %>/js/services/**/**/*.js'
            '../themes/<%= pkg.name %>/js/partials/**/**/*.js'
            '../themes/<%= pkg.name %>/js/main.js'
          ]
          '../themes/<%= pkg.name %>/js/vendor.min.js': [
            '../themes/<%= pkg.name %>/js/vendor/**/**/*.js'
            'bower_components/modernizr/modernizr.js'
            'bower_components/foundation/js/foundation.js'
          ]
          '../themes/<%= pkg.name %>/js/vendor/ie.min.js': [
            'bower_components/respond/src/respond.js'
            'bower_components/html5shiv/dist/html5shiv.js'
          ]
    watch:
      options:
        livereload: yes
      copy:
        files: [
          'src/**/**/*'
          '!src/**/**/*.{scss,coffee}'
        ]
        tasks: [
          'copy'
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
          '../themes/<%= pkg.name %>/css/**/**/*.css'
          '!../themes/<%= pkg.name %>/css/**/**/*.min.css'
        ]
        tasks: [
          'cssmin'
        ]
      uglify:
        files: [
          '../themes/<%= pkg.name %>/js/**/**/*.js'
          '!../themes/<%= pkg.name %>/js/**/**/*.min.js'
        ]
        tasks: [
          'uglify'
        ]


  # Load Npm tasks
  grunt.loadNpmTasks 'grunt-contrib-clean'
  grunt.loadNpmTasks 'grunt-contrib-copy'
  grunt.loadNpmTasks 'grunt-scss-lint'
  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-coffeelint'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-watch'

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
    if grunt.option('watch')
      taskList.push 'watch'
    grunt.task.run taskList