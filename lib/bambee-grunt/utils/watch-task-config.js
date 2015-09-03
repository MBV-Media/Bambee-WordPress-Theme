module.exports = {
  options: {
    livereload: true
  },
  copy: {
    files: [
      '<%= appConfig.src %>/**/**/*',
      '!<%= appConfig.src %>/**/**/*.{scss,coffee}',
    ],
    tasks: [
      'copy',
      'notify:copy',
    ]
  },
  compass: {
    files: [
      '**/**/*.scss',
    ],
    tasks: [
      'scsslint',
      'compass',
    ]
  },
  coffee: {
    files: '<%= coffee.dist.src %>',
    tasks: [
      'coffeelint',
      'coffee',
    ]
  },
  cssmin: {
    files: [
      '<%= appConfig.dist %>/css/**/**/*.css',
      '!<%= appConfig.dist %>/css/**/**/*.min.css',
    ],
    tasks: [
      'cssmin',
      'notify:cssmin',
    ]
  },
  uglify: {
    files: [
      '<%= appConfig.dist %>/js/**/**/*.js',
      '!<%= appConfig.dist %>/js/**/**/*.min.js',
    ],
    tasks: [
      'uglify',
      'notify:uglify',
    ]
  }
};