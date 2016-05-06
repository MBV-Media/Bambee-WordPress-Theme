(function ($) {
  "use strict";

  var shortcodeList = new ShortcodeList(),
      pluginName = 'ShortcodeSelector';

  tinymce.create('tinymce.plugins.' + pluginName, {
    /**
     * Initializes the plugin, this will be executed after the plugin has been created.
     * This call is done before the editor instance has finished it's initialization so use the onInit event
     * of the editor instance to intercept that event.
     *
     * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
     * @param {string} url Absolute URL to where the plugin is located.
     */
    init: function (ed, url) {
      ed.addButton(pluginName, {
        type: 'listbox',
        text: '[Shortcodes]',
        icon: false,
        tooltip: '[Shortcodes]',
        values: shortcodeList.getTinyMceListboxValues(),
        fixedWidth: true,
        onselect: function (e) {
          var item = this,
              shortcode = shortcodeList.getShortcode(item);

          ed.windowManager.open({
            title: shortcode.getStartTag(),
            body: shortcode.getTinyMceDialogBody(),
            onsubmit: function (e) {
              var shortcodeString = '';
              shortcodeString += shortcode.getStartTag(true);
              shortcodeString += ed.selection.getContent();
              shortcodeString += shortcode.getEndTag();

              ed.execCommand('mceInsertContent', 0, shortcodeString);
            }
          });
        },
        onPostRender: function () {
          this.value('');
        }
      });
    },

    /**
     * Creates control instances based in the incomming name. This method is normally not
     * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
     * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
     * method can be used to create those.
     *
     * @param {String} n Name of the control to create.
     * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
     * @return {tinymce.ui.Control} New control instance or null if no control was created.
     */
    createControl: function (n, cm) {
      return null;
    },

    /**
     * Returns information about the plugin as a name/value array.
     * The current keys are longname, author, authorurl, infourl and version.
     *
     * @return {Object} Name/value array containing information about the plugin.
     */
    getInfo: function () {
      return {
        longname: 'Shortcode selector',
        author: 'RTO GmbH',
        authorurl: 'http://rto.de',
        infourl: 'http://rto.de',
        version: "0.1"
      };
    }
  });

  // Register plugin
  tinymce.PluginManager.add(pluginName, tinymce.plugins[pluginName]);

  /**
   *
   * @constructor
   */
  function ShortcodeList() {

    /**
     *
     * @type {ShortcodeList}
     */
    var self = this;

    /**
     *
     * @type {Array}
     */
    var shortcodeList = [];

    /**
     *
     */
    var init = function () {
      for (var i = 0; i < window.bambeeShortcodeList.length; ++i) {
        var shortcodeData = window.bambeeShortcodeList[i];
        var shortcode = new Shortcode(
            shortcodeData.tag,
            shortcodeData.descr,
            shortcodeData.atts
        );
        addShortcode(shortcode);
      }
    };

    /**
     *
     * @param shortcode
     */
    var addShortcode = function (shortcode) {
      shortcodeList.push(shortcode);
    };

    /**
     *
     * @param item
     * @returns {*}
     */
    self.getShortcode = function (item) {
      if (typeof item === 'number') {
        return shortcodeList[item];
      }
      else {
        for (var i = 0; i < shortcodeList.length; ++i) {
          if (shortcodeList[i].getTagName() == item.value()) {
            return shortcodeList[i];
          }
        }
      }
    };

    /**
     *
     * @returns {Array}
     */
    self.getTinyMceListboxValues = function () {
      var listboxValues = [];
      for (var i = 0; i < shortcodeList.length; ++i) {
        var shortcode = shortcodeList[i];
        listboxValues.push(shortcode.getTinyMceListboxValue());
      }
      return listboxValues;
    };

    init();
  }

  /**
   *
   * @param tagName
   * @param descr
   * @param atts
   * @constructor
   */
  function Shortcode(tagName, descr, atts) {

    /**
     *
     * @type {Shortcode}
     */
    var self = this;

    /**
     *
     */
    var init = function () {
      var atts = self.getAtts();
      for (var i = 0; i < atts.length; ++i) {
        atts[i].value = atts[i].default;
      }
    };

    /**
     *
     * @returns {string}
     */
    this.getStartTag = function (addAtts) {
      var startTag = '[' + tagName;

      if (typeof addAtts !== 'undefined' && addAtts === true) {
        var atts = self.getAtts();
        for (var i = 0; i < atts.length; ++i) {
          if (atts[i].value !== '') {
            startTag += ' ' + atts[i].name + '="' + atts[i].value + '"';
          }
        }
      }

      startTag += ']';
      return startTag;
    };

    /**
     *
     * @returns {string}
     */
    this.getEndTag = function () {
      return '[/' + tagName + ']';
    };

    /**
     *
     * @returns {*}
     */
    this.getTagName = function () {
      return tagName;
    };

    /**
     *
     * @returns {*}
     */
    this.getDescr = function () {
      return descr;
    };

    /**
     *
     * @returns {*}
     */
    this.getAtts = function () {
      return atts;
    };

    /**
     *
     * @param name
     * @param value
     */
    this.setAttribute = function (name, value) {
      var atts = self.getAtts();

      for (var i = 0; i < atts.length; ++i) {
        if (atts[i].name == name) {
          atts[i].value = value
          return;
        }
      }
    };

    /**
     *
     * @returns {{text, value}}
     */
    this.getTinyMceListboxValue = function () {
      return {
        text: self.getStartTag(),
        value: self.getTagName()
      };
    };

    /**
     *
     * @returns {*[]}
     */
    this.getTinyMceDialogBody = function () {

      init();

      var dialogBody = [{
        type: 'container',
        name: 'descr',
        html: self.getDescr()
      }];

      var shortcodeAtts = self.getAtts();

      if(shortcodeAtts.length) {
        dialogBody.push({
          type: 'container',
          name: 'descr',
          html: '<b>Unterstütze Attribute:</b>'
        });
      }

      for (var i = 0; i < shortcodeAtts.length; ++i) {
        dialogBody.push({
          type: 'textbox',
          name: shortcodeAtts[i].name,
          label: shortcodeAtts[i].name,
          value: shortcodeAtts[i].default,
          onKeyUp: function (e) {
            var name = $(this.$el).closest('.mce-container-body').find('.mce-label').text(),
                value = $(this.$el).val();
            self.setAttribute(name, value);

            $(this.$el)
                .closest('.mce-container')
                .nextAll('.mce-container.mce-last')
                .find('.mce-container-body')
                .html(self.getStartTag(true) + self.getEndTag());
          }
        });
      }

      dialogBody.push({
        type: 'container',
        name: 'shortoce',
        html: self.getStartTag(true) + self.getEndTag()
      });

      return dialogBody;
    };

    init();
  }

})(jQuery);
