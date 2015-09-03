module.exports = {
  dist: {
    options: {
      config: 'config/config.rb'
    },
    dist: [
      '<%= appConfig.src %>/**/**/*.scss',
    ]
  }
};