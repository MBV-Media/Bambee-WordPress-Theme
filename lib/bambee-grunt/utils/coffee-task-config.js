module.exports = {
  dist: {
    expand: true,
    cwd: '<%= appConfig.src %>/js',
    src: [ '**/**/*.coffee' ],
    dest: '<%= appConfig.dist %>/js/',
    ext: '.js'
  }
};