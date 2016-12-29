###*
  * Your JS/CoffeeScript goes here
  * Components like custom classes are in components/
  * Third party libraries are in vendor/ or /bower_components/
  *
  * For CoffeeScript style guide please refer to
  * https://github.com/MBV-Media/CoffeeScript-Style-Guide
  *
  * @author MBV-Media
  * @license MIT
###
'use strict'

jQuery ($) ->

  $stickyElementList = null

  ###*
    * Initialize modules/plugins/etc.
    *
    * @since 1.0.0
    * @return {void}
  ###
  init = ->
    $stickyElementList = $ '[data-bambee-sticky]'
    $stickyElementList.each ->
      @.Sticky = new Sticky $(@)
      @.Sticky.checkStickyness()
    $(document).on 'scroll', sticky

  ###
    *
  ###
  sticky = (e) ->
    $stickyElementList.each ->
      @.Sticky.checkStickyness()

  init()

class Sticky

  ###
    *
  ###
  data =
    offset: 'data-sticky-offset'
    toBottom: 'data-stick-to-bottom'
    placeholder: 'data-sticky-placeholder'

  ###
    *
  ###
  constructor: ($element) ->
    @$window = jQuery(window)

    @$element = $element
    @width = $element.outerWidth()
    @height = $element.outerHeight()
    @left = $element.offset().left

    @trigger = $element.offset().top
    @offset = $element.attr data.offset
    @toBottom = $element.attr(data.toBottom) != undefined

    if @offset == undefined
      @offset = 0
    else
      @offset = parseFloat @offset

    if @toBottom
      @trigger += @height + @offset
    else
      @trigger -= @offset

    # debug trigger line
#    $('<div></div>')
#      .css
#        position: 'absolute'
#        top: @trigger + 'px'
#        width: '100%'
#        height: '1px'
#        backgroundColor: 'black'
#      .appendTo 'body'

    @isSticky = no

    @usePlaceholder =  $element.attr(data.placeholder) != undefined
    if @usePlaceholder
      @addPlaceholder()
      @hidePlaceholder()

  ###

  ###
  checkStickyness: ->
    triggerPos = @$window.scrollTop()
    if @toBottom
      triggerPos += @$window.height()

    if @isSticky
      if @toBottom
        if triggerPos > @trigger
          @unPin()
      else
        if triggerPos < @trigger
          @unPin()
    else
      if @toBottom
        if triggerPos <= @trigger
          @pin()
      else
        if triggerPos >= @trigger
          @pin()

  ###

  ###
  pin: ->
    @isSticky = yes

    css =
      position: 'fixed'
      left: @left + 'px'
      width: @width + 'px'
      height: @height + 'px'

    if @toBottom
      css.bottom = @offset + 'px'
    else
      css.top = @offset + 'px'

    @$element.css css

    if @usePlaceholder
      @showPlaceholder()

  ###

  ###
  unPin: ->
    @isSticky = no

    css =
      position: ''
      left: ''
      width: ''
      height: ''
      bottom: ''
      top: ''

    @$element.css css

    if @usePlaceholder
      @hidePlaceholder()

  ###

  ###
  addPlaceholder: ->
    $placecolder = jQuery '<div class="sticky-placeholder"></div>'
    @$placeholder = $placecolder.insertAfter(@$element).css
      width: @width + 'px'
      height: @height + 'px'

  ###

  ###
  hidePlaceholder: ->
    @$placeholder.hide()

  ###

  ###
  showPlaceholder: ->
    @$placeholder.show()
