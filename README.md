# Getting started
If you haven't used [Grunt](http://gruntjs.com/) before, be sure to check out the [Getting Started](http://gruntjs.com/getting-started) guide, as it explains how to create a [Gruntfile](http://gruntjs.com/sample-gruntfile) as well as install and use Grunt plugins. Once you're familiar with that process, you can start developing with this theme.

# Requirements
- [gem bundler](http://bundler.io/)
- [npm bower](http://bower.io/)
- [npm grunt](http://gruntjs.com/)
- [composer](https://getcomposer.org/)

# Install dependencies
You can either run
```
  bundle install
  npm install
  bower install
  composer install
```
or let yeoman do all the work for you (full documentation [here](https://github.com/MBV-Media/generator-bambee))
```
  yo bambee
```

# Developing
## Watch mode
Watches every change being made and run belonging task.
```
  grunt --watch
```
## Developement mode
Create source maps for minified `css` and `js` files
```
  grunt --devMode
```

# Customizing
## Changing theme name
To **change theme name**, edit `name` property in `package.json` . This name will be used for themes folder and `Theme Name` in `style.css`.
```
  {
    "name": "bambee",
    ...
  }
```

# Grunt tasks
- [clean](https://github.com/gruntjs/grunt-contrib-clean): Cleans the compiled themes folder.
- [copy](https://github.com/gruntjs/grunt-contrib-copy): Copies all files, wich have not to be compiled.
- [scsslint](https://github.com/ahmednuaman/grunt-scss-lint): Lints your `.scss` files before compiling them. Configuration found in `config/.scss-lint.yml`. [1]
- [compass](https://github.com/gruntjs/grunt-contrib-compass): Compiles your `.scss` files. Configuration found in `config/config.rb`.
- [coffeelint](https://github.com/vojtajina/grunt-coffeelint): Lints your `.coffee` files before compiling them. Configuration found in `config/coffeelint.yml`. [2]
- [coffee](https://github.com/gruntjs/grunt-contrib-coffee): Compiles your `.coffee` files.
- [cssmin](https://github.com/gruntjs/grunt-contrib-cssmin): Minifies and merges `.css` files.
- [uglify](https://github.com/gruntjs/grunt-contrib-uglify): Minifies and merges `.js` files.
- [watch](https://github.com/gruntjs/grunt-contrib-watch): Watches for changes in your files and executes tasks.

[1] [Sass style guide](http://sass-guidelin.es/)
[2] [CoffeeScript style guide](https://github.com/polarmobile/coffeescript-style-guide)

# Autoloaded Namespaces (PSR-4)
- The `includes/` directory is loaded into the `Inc` namespace
- The `lib/` directory is loaded into the `Lib` namespace