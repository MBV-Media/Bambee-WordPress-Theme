(function () {

  var shortcodeList = window.bambeeShortcodeList,
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
        values: shortcodeList,
        fixedWidth: true,
        onselect: function (e) {
          var text = this.text(),
              tag = this.value(),
              selection = ed.selection,
              shortcodeData = getShortcodeData(this),
              dialogBody = getDialogBody(shortcodeData);

          ed.windowManager.open({
            title: text,
            body: dialogBody,
            onsubmit: function (e) {
              var shortcodeAtts = '';

              for (var attrName in shortcodeData.atts) {
                if (shortcodeData.atts.hasOwnProperty(attrName) && e.data[attrName]) {
                  shortcodeAtts += ' ' + attrName + '="' + e.data[attrName] + '"';
                }
              }

              var shortcode = '[' + tag + shortcodeAtts + ']';
              shortcode += selection.getContent();
              shortcode += '[/' + tag + ']';

              ed.execCommand('mceInsertContent', 0, shortcode);
            }
          });
        },
        onPostRender: function () {
          // Select the second item by default
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

  /**
   *
   * @param item
   * @returns {*}
   */
  var getShortcodeData = function (item) {
    for (var i = 0; i < shortcodeList.length; ++i) {
      if (shortcodeList[i].text == item.text()) {
        return shortcodeList[i];
      }
    }
  };

  /**
   *
   * @param shortcodeData
   * @returns {Array}
   */
  var getDialogBody = function (shortcodeData) {
    var dialogBody = [];
    for (var attrName in shortcodeData.atts) {
      if (shortcodeData.atts.hasOwnProperty(attrName)) {
        dialogBody.push({
          type: 'textbox',
          name: attrName,
          label: attrName,
          value: shortcodeData.atts[attrName]
        });
      }
    }
    return dialogBody;
  };

  // Register plugin
  tinymce.PluginManager.add(pluginName, tinymce.plugins[pluginName]);
})();
