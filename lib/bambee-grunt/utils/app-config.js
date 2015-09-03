'use strict';

var jsonFile = require('jsonfile'),
  pkg,
  appConfig;

pkg = jsonFile.readFileSync('package.json');
appConfig = {
  src: 'src',
  dist: '../themes/' + pkg.name
};

module.exports = appConfig;