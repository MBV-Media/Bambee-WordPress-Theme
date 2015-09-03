module.exports = {
  options: {
    force: true
  },
  build: {
    src: [
      '<%= appConfig.dist %>'
    ]
  }
};