###*
  * Your JS/CoffeeScript goes here
  * Components like custom classes are in components/
  * Third party libraries are in vendor/ or /bower_components/
  *
  * For CoffeeScript style guide please refer to
  * https://github.com/MBV-Media/CoffeeScript-Style-Guide
  *
  * @since 1.0.0
  * @author R4c00n <marcel.kempf93@gmail.com>
  * @license MIT
###
'use strict'

jQuery ($) ->

  ###*
    * Initialize modules/plugins/etc.
    *
    * @since 1.0.0
    * @return {void}
  ###
  init = ->
    $(document).foundation()

  init()
