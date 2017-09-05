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

  $window = $(window)
  $document = $(document)

  ###
    * Initialize modules/plugins/etc.
    *
    * @since 1.0.0
    * @return {void}
  ###
  init = ->
    $document.foundation()
    return

  ###
    * Register listeners to all kind of events.
    *
    * @since 2.3.1
    * @return {void}
  ###
  registerEventListeners = ->
    $window.on('load', onWindowLoad)
    $document.on('toggled.zf.responsiveToggle', '.responsive-menu-toggle', toggleBodyScrollBar)
    return

  ###
    * Logs a 'Website loaded.' info text.
    *
    * @since 2.3.1
    * @return {void}
  ###
  onWindowLoad = (event) ->
    console.log('Website loaded.')
    return

  toggleBodyScrollBar = (event) ->
    $(@).toggleClass('active')
    $('body').toggleClass('overflow-hidden')
    $('#responsive-menu').toggleClass('active')

  init()
  registerEventListeners()
