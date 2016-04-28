(function () {

  for (var i = 0; i < bambeeShortcodes.length; ++i) {

    var shortcodeData = bambeeShortcodes[i];

    console.log(shortcodeData);

    tinymce.create('tinymce.plugins.' + shortcodeData.plugin, {
      /**
       * Initializes the plugin, this will be executed after the plugin has been created.
       * This call is done before the editor instance has finished it's initialization so use the onInit event
       * of the editor instance to intercept that event.
       *
       * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
       * @param {string} url Absolute URL to where the plugin is located.
       */
      init: function (ed, url) {
        ed.addButton(shortcodeData.tag, {
          title: '[' + shortcodeData.tag + ']',
          cmd: shortcodeData.tag,
          text: '[' + shortcodeData.tag + ']'
          //image : url + '/dropcap.jpg'
          //image: 'http://www.wedesoft.de/test/test.png'
          //icon: 'wp_more'
        });

        ed.addCommand(shortcodeData.tag, function () {
          var selected_text = ed.selection.getContent();
          var return_text = '';
          return_text = '[' + shortcodeData.tag + ']' + selected_text + '[/' + shortcodeData.tag + ']';
          ed.execCommand('mceInsertContent', 0, return_text);
        });

//      ed.addCommand('showrecent', function() {
//        var number = prompt("How many posts you want to show ? "),
//            shortcode;
//        if (number !== null) {
//          number = parseInt(number);
//          if (number > 0 && number <= 20) {
//            shortcode = '[recent-post number="' + number + '"/]';
//            ed.execCommand('mceInsertContent', 0, shortcode);
//          }
//          else {
//            alert("The number value is invalid. It should be from 0 to 20.");
//          }
//        }
//      });
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
          longname: 'Wptuts Buttons',
          author: 'Lee',
          authorurl: 'http://wp.tutsplus.com/author/leepham',
          infourl: 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
          version: "0.1"
        };
      }
    });

    // Register plugin
    tinymce.PluginManager.add(shortcodeData.tag, tinymce.plugins[shortcodeData.plugin]);
  }
})();
