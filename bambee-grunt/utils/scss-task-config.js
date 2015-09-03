module.exports = {
  options: {
    config: 'config/.scss-lint.yml'
  },
  dist: [
    '<%= appConfig.src %>/**/**/*.scss',
  ]
};