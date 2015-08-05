<?php namespace Inc;


/* * **********************************
 * Author: shennemann
 * Erstellt am: 20.11.2013 11:49
 * ********************************** */

/**
 * Creates a new AdminPage
 *
 * Shortdocumentation
 *
 * Init:
 * $my_admin_page = new AdminPage(
 * array(
 * 'pageTitle' => 'My own Adminpage',
 * 'menuName' => 'My Adminpage',
 * 'slug' => 'my_adminpage_1',
 * 'location' => 'theme',
 * 'fields' => array()
 * )
 * );
 *
 * Short Description:
 * Location:
 * Where the adminpage will be embed.
 * Default is 'theme'
 * Options: options, dashboard, comments, posts, pages, media,
 * links, management, users, plugins, menu, submenu (needs parent)
 *
 * pageTitle & menuName
 * Title and menuname of the adminpage
 *
 * Slug
 * Required, unique identifier
 *
 * Fields
 * Required. An array with Input-Fields, like this:
 * array(
 * 'background' => array(
 *    'type'=>'textarea', // textarea, input, select (options = array()), checkbox, textbox, editlist
 *    'title'=>'Hintergrund-Inhalt',
 *     'default'=>'[swapper id=background_swapper width=2000 height=858]'),
 * );
 *
 */
class AdminPage {
    /**
     * pageTitle -> Will shown on the adminpage
     *
     * @var string
     */
    private $page_title = 'CustomOptions';

    /**
     * Will shown in the wordpress menu
     *
     * @var string
     */
    private $menu_name = 'CustomOptions';

    /**
     * unique identifier
     *
     * @var string
     */
    private $slug = 'ap_page';

    /**
     * Unique identifier of settings
     *
     * @var string
     */
    private $settings_id = 'ap_settings';

    /**
     * Unique identifier of settings-section
     *
     * @var string
     */
    private $section_id;

    /**
     * Location of the Admin-Page (theme, options, dashboard, menu, submenu, etc)
     *
     * @var string
     */
    private $location = 'theme';

    /**
     * Capability required to access menu page
     *
     * @var string
     */
    private $capability = 'manage_options';

    /**
     * Array with input-fields. Example:
     * array(
     * 'background' => array(
     *    'type'=>'textarea', // textarea, input, select (options = array()), checkbox, textbox, editlist)
     *    'title'=>'Background-Content',
     *     'default'=>'[swapper id=background_swapper width=2000 height=858]'),
     * );
     *
     * @var array
     */
    private $fields = array();

    /**
     * Embed an include (Full-Server-Path requiered
     *
     * @var string
     */
    private $include;

    /**
     * Embed an include to bottom (Full-Server-Path requiered
     *
     * @var string
     */
    private $include_bottom;

    /**
     * Slug of the parent (only need for submenu)
     *
     * @var string
     */
    private $parent_page;


    /**
     * Defines a callback-function
     *
     * @var string|array
     */
    private $callback;


    /**
     * Defines the title of the settings (default: Config)
     *
     * @var string
     */
    private $settings_title = 'Config';

    /**
     * Defines a screenIcon (default: options-general)
     *
     * @var string
     */
    private $screen_icon = 'options-general';

    /**
     * Use a metaBox or not
     *
     * @var boolean
     */
    private $use_meta_box = true;


    /**
     * Use a Title or not
     *
     * @var boolean
     */
    private $use_title = true;

    /**
     * Position of the MetaBox (default: left)
     *
     * @var string
     */
    private $meta_position = 'left';

    /**
     *
     * @var string
     */
    protected $form_action = 'options.php';

    /**
     *
     * @var string
     */
    protected $wrapper_class = '';

    /**
     *
     * @var string
     */
    protected $form_id = '';


    /**
     * Args: fields, pageTitle, menuName, slug, location, parentPage, include, settingsId
     * Required: fields, slug
     *
     * @param array $args
     *
     * @return object
     */
    public function __construct( $args ) {
        if ( empty( $args )
                || ( empty( $args['slug'] ) && empty( $args['id'] ) )
                || ( empty( $args['fields'] ) && empty( $args['include'] ) )
        ) {
            exit( 'Class Adminpage: Required Args missing' );
        }

        $this->fields = ( !empty( $args['fields'] ) ? $args['fields'] : array() );
        $this->include = ( !empty( $args['include'] ) ? $args['include'] : array() );
        $this->include_bottom = ( !empty( $args['include_bottom'] ) ? $args['include_bottom'] : array() );
        $this->callback = ( !empty( $args['callback'] ) ? $args['callback'] : null );

        if ( empty( $args['slug'] ) ) {
            $args['slug'] = $args['id'] . '_page';

            if ( empty( $args['settings_id'] ) ) {
                $args['settings_id'] = $args['id'];
            }
        }

        $this->slug = $args['slug'];

        if ( !empty( $args['pageTitle'] ) ) {
            $this->page_title = $args['pageTitle'];
        }
        if ( !empty( $args['page_title'] ) ) {
            $this->page_title = $args['page_title'];
        }
        if ( !empty( $args['menuName'] ) ) {
            $this->menu_name = $args['menuName'];
        }
        if ( !empty( $args['menu_name'] ) ) {
            $this->menu_name = $args['menu_name'];
        }
        if ( !empty( $args['location'] ) ) {
            $this->location = $args['location'];
        }

        if ( !empty( $args['capability'] ) ) {
            $this->capability = $args['capability'];
        }

        if ( !empty( $args['settingsId'] ) || !empty( $args['settings_id'] ) ) {
            if ( !empty( $args['settings_id'] ) ) {
                $this->settings_id = $args['settings_id'];
            } else {
                $this->settings_id = $args['settingsId'];
            }
        } else {
            $this->settings_id = $args['slug'];
        }
        if ( !empty( $args['sectionId'] ) || !empty( $args['section_id'] ) ) {
            if ( !empty( $args['section_id'] ) ) {
                $this->section_id = $args['section_id'];
            } else {
                $this->section_id = $args['sectionId'];
            }
            $this->section_id = $args['sectionId'];
        } else {
            $this->section_id = $this->settings_id . '_section';
        }
        if ( !empty( $args['parent_page'] ) ) {
            $this->parent_page = $args['parent_page'];
        }
        if ( !empty( $args['parentPage'] ) ) {
            $this->parent_page = $args['parentPage'];
        }
        if ( !empty( $args['settingsTitle'] ) || !empty( $args['settings_title'] ) ) {
            if ( !empty( $args['settings_title'] ) ) {
                $this->settings_title = $args['settings_title'];
            } else {
                $this->settings_title = $args['settingsTitle'];
            }
        } else {
            $this->settings_title = $this->page_title . ' - Config';
        }
        if ( !empty( $args['screenIcon'] ) ) {
            $this->screen_icon = $args['screenIcon'];
        }
        if ( !empty( $args['screen_icon'] ) ) {
            $this->screen_icon = $args['screen_icon'];
        }
        if ( isset( $args['useMetaBox'] ) ) {
            $this->use_meta_box = $args['useMetaBox'];
        }
        if ( isset( $args['use_meta_box'] ) ) {
            $this->use_meta_box = $args['use_meta_box'];
        }
        if ( isset( $args['useTitle'] ) ) {
            $this->use_title = $args['useTitle'];
        }
        if ( isset( $args['use_title'] ) ) {
            $this->use_title = $args['use_title'];
        }
        if ( !empty( $args['metaPosition'] ) ) {
            $this->meta_position = $args['metaPosition'];
        }
        if ( !empty( $args['meta_position'] ) ) {
            $this->meta_position = $args['meta_position'];
        }

        if ( !empty( $args['wrapper_class'] ) ) {
            $this->wrapper_class = $args['wrapper_class'];
        }

        if ( did_action( 'admin_menu' ) > 0 ) {
            $this->ap_register_settings();
            $this->ap_add_menu();
        } else {
            add_action( 'admin_init', array( $this, 'ap_register_settings' ) );
            add_action( 'admin_menu', array( $this, 'ap_add_menu' ) );
        }

        if ( !empty( $args['form_action'] ) ) {
            $this->form_action = $args['form_action'];
        }

        if ( isset( $args['form_id'] ) ) {
            $this->form_id = $args['form_id'];
        }

        // Register scripts/styles
        add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );

        return $this;
    }

    /**
     * Register scripts/styles
     */
    public function enqueue_admin_scripts() {
        // Style
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'wp-color-picker' );

        // Scripts
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'wp-color-picker' );
    }

    /**
     * Creates the adminpage
     */
    public function ap_add_menu() {
        switch ( $this->location ) {
            case 'submenu':
                if ( !empty( $this->parent_page ) ) {
                    add_submenu_page( $this->parent_page, $this->page_title, $this->menu_name, $this->capability, $this->slug, array( $this, 'ap_option_view' ) );
                } else {
                    throw new \Exception( 'AdminPage: Parent is not set.' );
                }
                break;
            default:
                $function = 'add_' . $this->location . '_page';
                if ( function_exists( $function ) ) {
                    $function( $this->page_title, $this->menu_name, $this->capability, $this->slug, array( $this, 'ap_option_view' ) );
                } else {
                    throw new \Exception( 'AdminPage: Function add_' . $this->location . '_page not found.' );
                }
        }
    }

    /**
     * Register the settings
     */
    public function ap_register_settings() {
        register_setting( $this->settings_id, $this->settings_id, array( $this, 'plugin_options_validate' ) );
        add_settings_section( $this->section_id, '', array( $this, 'ap_section_config' ), $this->slug );
    }

    /**
     * Creates the fields in a loop
     *
     * @param $args
     */
    public function ap_section_config( $args ) {
        foreach ( $this->fields as $key => $options ) {
            //$options_array = array('id'=>$key, 'type'=>$options['type'], 'options'=>(!empty($options['options']) ? $options['options'] : null), 'multilang' => !empty($options['multilang']), 'include' => (!empty($options['include']) ? $options['include'] : null));
            $options_array = $options;
            $options_array['id'] = $key;

            if ( !empty( $options['default'] ) ) {
                $options_array['default'] = $options['default'];
            }
            if ( empty( $options['title'] ) ) {
                $options['title'] = '';
            }
            add_settings_field( $key, $options['title'], array( $this, 'ap_set_field' ), $this->slug, $this->section_id, $options_array );
        }
    }

    /**
     *  Adds form to a metabox
     */
    public function ap_option_view() {
        ?>
        <div class="wrap<?= ( !empty( $this->wrapper_class ) ? ' ' . $this->wrapper_class : '' ) ?>">
            <?php if ( $this->use_title ) { ?>
                <?php screen_icon( $this->screen_icon ); ?>
                <h2><?= $this->page_title ?></h2>
                <br/>
            <?php } ?>
            <?php if ( !empty( $this->use_meta_box ) ) { ?>
                <div class="metabox-holder">
                    <?php
                    add_meta_box( $this->slug . '_metabox', $this->settings_title, array( $this, 'ap_do_option_view' ), $this->slug, $this->meta_position );
                    do_meta_boxes( $this->slug, 'left', $this );
                    ?>
                </div>
            <?php } else { ?>
                <?php ap_do_option_view(); ?>
            <?php } ?>
        </div>
    <?php
    }

    /**
     *  Returns the form
     */
    public function ap_do_option_view() {
        ?>
        <form method="post"
                action="<?= $this->form_action ?>"<?= ( !empty( $this->form_id ) ? ' id="' . $this->form_id . '"' : '' ) ?>>
            <?php
            if ( !empty( $this->include ) && file_exists( $this->include ) ) {
                include( $this->include );
            }
            if ( !empty( $this->callback ) ) {
                if ( is_array( $this->callback ) ) {
                    $this->callback[0]->$this->callback[1]();
                } else {
                    $this->callback();
                }
            }
            if ( !empty( $this->fields ) ) {
                do_action( 'ap_pre_fields' );
                ?>
                <?php settings_fields( $this->settings_id ); ?>
                <div class="sections_inside">
                    <?php do_settings_sections( $this->slug ); ?>
                </div>
                <?php
                do_action( 'ap_post_fields' );
            }

            if ( !empty( $this->include_bottom ) && file_exists( $this->include_bottom ) ) {
                include( $this->include_bottom );
            }
            ?>
            <?php submit_button(); ?>
        </form>

        <script type="text/javascript">
            /**
             * Adds a new item to Editlist
             *
             * @param {type} id
             * @returns {undefined}
             */
            function addEditlistItem(id) {
                var hiddenField = jQuery('#editlist_' + id),
                        selectField = jQuery('#editlist_select_' + id),
                        insertField = jQuery('#editlist_insert_' + id);
                // Check if exists
                var sidebarExists = false;
                for (var i = 0; i < selectField.children().length; i += 1) {
                    if (selectField.children()[i].value == insertField.val()) {
                        sidebarExists = true;
                        break;
                    }
                }
                if (!sidebarExists && insertField.val() != '') {
                    selectField.append('<option value="' + insertField.val() + '">' + insertField.val() + '</option>');
                }

                // fill hidden field
                hiddenField.val('');
                var newValue = [];
                for (var i = 0; i < selectField.children().length; i += 1) {
                    newValue[i] = selectField.children()[i].value;
                }
                hiddenField.val(newValue.join(','));
                insertField.val('');
            }

            /**
             * Remove an item from Editlist
             *
             * @param {type} id
             * @returns {undefined}
             */
            function removeEditlistItem(id) {
                var hiddenField = jQuery('#editlist_' + id),
                        selectField = jQuery('#editlist_select_' + id);

                for (var i = selectField.children().length - 1; i >= 0; i -= 1) {
                    if (selectField.children()[i].selected) {
                        selectField.children()[i].remove();
                    }
                }


                // fill hidden field
                hiddenField.val('');
                var newValue = [];
                for (var i = 0; i < selectField.children().length; i += 1) {
                    newValue[i] = selectField.children()[i].value;
                }
                hiddenField.val(newValue.join(','));
            }

            /**
             * Toggles the current language of an input
             *
             * @param {type} language
             * @returns {undefined}
             */
            function toggleLanguage(language) {
                var openElements = jQuery('.ap_input.' + language + '.open');
                if (openElements.length > 0) {
                    openElements.removeClass('open');
                } else {
                    jQuery('.ap_input.' + language).addClass('open');
                }
            }
            /**
             * Hides all Languages
             *
             * @returns {undefined}
             */
            function closeAllLanguage() {
                jQuery('.ap_input.').removeClass('open');
            }

            /**
             * Onload
             */
            jQuery(function () {
                // Init colorpicker
                jQuery('.picker_field').wpColorPicker();

                // init upload
                initUpload();
            });

            function initUpload() {
                jQuery('.image_selector .upload_image_button').click(function (clickEvent) {
                    window.fieldLabel = jQuery(clickEvent.currentTarget).closest('.image_selector');
                    targetfield = window.fieldLabel.find('.upload_image');
                    tb_show('', 'media-upload.php?type=image&TB_iframe=true');

                    return false;
                });
                window.send_to_editor = function (html) {
                    var imgurl = jQuery('img', html).attr('src');
                    jQuery(targetfield).val(imgurl);
                    tb_remove();
                };
            }


        </script>
        <style type="text/css">
            .language_image_label {
                display: inline-block;
                border: 3px solid transparent;
                border-bottom: 0px none;
                background-repeat: no-repeat;
                width: 16px;
                height: 11px;
                cursor: pointer;
            }

            .language_label {
                display: inline-block;
                margin-top: 3px;
                border: 1px solid #dfdfdf;
                background-color: #ececec;
                cursor: pointer;
            }

            .ap_input {
                display: none;
            }

            .ap_input.open {
                display: inline-block;
            }
        </style>
    <?php
    }


    /**
     * Creates the html for input-fields
     *
     * @param type $args
     */
    public function ap_set_field( $args ) {
        $options = get_option( $this->settings_id );
        $flag_dir = '';
        if ( class_exists( 'Polylang' ) && !empty( $args['multilang'] ) ) {
            // Use Polylang for translate
            global $polylang;
            $polylangs = $polylang->get_languages_list();
            foreach ( $polylangs as $poly ) {
                $languages[] = $poly->description;
            }
            $flag_dir = WP_PLUGIN_DIR . '/polylang/flags';

        } else if ( class_exists( 'SitePress' ) && !empty( $GLOBALS['sitepress'] ) && !empty( $args['multilang'] ) ) {
            // Use Sitepress/WPML for translate
            $languages = array();
            foreach ( $GLOBALS['sitepress']->get_active_languages() as $value ) {
                $languages[] = str_replace( '-', '_', $value['tag'] );
            }
            $flag_dir = WP_PLUGIN_DIR . '/sitepress-multilingual-cms/res/flags';

        } else if ( empty( $args['multilang'] ) ) {
            $languages = array( 'all' );
        } else {
            $languages = array( WPLANG );
        }

        if ( empty( $options[$args['id']] ) ) {
            $options[$args['id']] = array();
        }
        $closed = false;

        // Loop for all languages
        foreach ( $languages as $lang ) {
            // Prevent notices and set array-keys
            if ( !is_array( $options[$args['id']] ) ) {
                $options[$args['id']] = array( $lang => $options[$args['id']] );
            }
            if ( empty( $options[$args['id']][$lang] ) ) {
                $options[$args['id']][$lang] = '';
            }

            // Show Language-Icon
            $lang_split = explode( '_', $lang );
            $lang_short = array_shift( $lang_split );
            if ( !empty( $args['multilang'] ) ) {
                $onclick = 'onclick="toggleLanguage(\'' . $lang . '\');"';
                $flag_url = '';

                // get flag-url
                if ( file_exists( $flag_dir . '/' . $lang . '.png' ) ) {
                    $flag_url = str_replace( WP_PLUGIN_DIR, WP_PLUGIN_URL, $flag_dir ) . '/' . $lang . '.png';
                } elseif ( file_exists( $flag_dir . '/' . $lang_short . '.png' ) ) {
                    $flag_url = str_replace( WP_PLUGIN_DIR, WP_PLUGIN_URL, $flag_dir ) . '/' . $lang_short . '.png';
                }

                if ( !empty( $flag_url ) ) {
                    echo '<span ' . $onclick . ' title="' . $lang . '" class="language_image_label" style="background-image:url(' . $flag_url . ');"></span>';
                } else {
                    echo '<span ' . $onclick . ' class="language_label">' . $lang_short . ': </span>';
                }
            }

            // set classes and id
            $class = 'ap_input ' . $lang . ' ' . $args['type'];
            if ( !empty( $args['class'] ) ) {
                $class .= ' ' . $args['class'];
            }
            if ( empty( $closed ) ) {
                $class .= ' open';
            }
            if ( count( $languages ) > 1 ) {
                $class .= ' language_field';
            }
            $id = $args['id'] . '_' . $lang;

            // set default value
            $default = ( !empty( $args['default'] ) ? $args['default'] : '' );
            if ( !empty( $default ) && is_array( $default ) ) {
                if ( isset( $default[$lang] ) ) {
                    $default = $default[$lang];
                } elseif ( isset( $default[WPLANG] ) ) {
                    $default = $default[WPLANG];
                } else {
                    $default = '';
                }
            }

            if ( $args['type'] !== 'checkbox' && !isset( $options[$args['id']][$lang] ) && !empty( $default ) ) {
                $options[$args['id']][$lang] = $default;
            }

            if ( !empty( $args['description'] ) ) {
                echo $args['description'];
            }

            $name = $this->settings_id . '[' . $args['id'] . '][' . $lang . ']"';
            $value = $options[$args['id']][$lang];

            switch ( $args['type'] ) {
                case 'label':
                    break;

                case 'textarea':
                    echo '<textarea class="' . $class . '" style="width:100%;height:200px;" id="' . $id . '" name="' . $name . '">' . $value . '</textarea>';
                    break;

                case 'wysiwyg':
                    $settings = array(
                            'wpautop' => !empty( $args['autop'] ) ? $args['autop'] : false,
                            'textarea_name' => $name,
                            'textarea_rows' => !empty( $args['rows'] ) ? $args['rows'] : 15,
                    );

                    $content = str_replace( PHP_EOL . PHP_EOL, PHP_EOL . "&nbsp;" . PHP_EOL, $value );

                    if ( empty( $args['autop'] ) ) {
                        $content = nl2br( $content );
                    }
                    echo '<div class="' . $class . '">';
                    wp_editor( $content, $id, $settings );
                    echo '</div>';
                    break;

                case 'select':
                    $multiple = ( !empty( $args['multiple'] ) ? ' multiple="mulitple"' : '' );
                    $current_value = $value;
                    $height = '';
                    if ( empty( $args['height'] ) && !empty( $multiple ) ) {
                        $height = 'height:85px;';
                    } else if ( !empty( $args['height'] ) ) {
                        $height = 'height:' . $args['height'] . 'px';
                        $height = str_replace( 'pxpx', 'px', $height );
                    }

                    echo '<select style="' . $height . '" class="' . $class . '" id="' . $id . '" name="' . $name . ( !empty( $multiple ) ? '[]' : '' ) . '"' . $multiple . '>';
                    foreach ( $args['options'] as $key => $option ) {
                        if ( is_numeric( $key ) && empty( $args['use_key'] ) ) {
                            $key = $option;
                        }
                        $selected = ( $key == $current_value || ( is_array( $current_value ) && in_array( $key, $current_value ) ) ? ' selected="selected"' : '' );
                        echo '<option value="' . $key . '"' . $selected . '>' . $option . '</option>';
                    }
                    echo '</select>';
                    break;

                case 'checkbox':
                    echo '
                    <span id="' . $id . '_container" class="checkbox_container">
                        <input class="' . $class . '" id="' . $id . '"' . ( !empty( $options[$args['id']][$lang] ) ? ' checked="checked"' : '' ) . ' name="' . $name . '" type="checkbox" value="1" />
                        <label for="' . $id . '" id="' . $id . '_label" class="checkbox_label"></label>
                    </span>';
                    break;

                case 'textbox':
                    echo( !empty( $default ) ? $default : '' );
                    break;

                case 'editlist':
                    if ( empty( $options[$args['id']][$lang] ) ) {
                        $list = array();
                        $options[$args['id']][$lang] = '';
                    } else {
                        $list = explode( ',', $options[$args['id']][$lang] );
                    }
                    echo '<input type="text" name="insert_' . $args['id'] . '" id="editlist_insert_' . $id . '"/>';
                    echo '<input type="button" name="insert_button_' . $args['id'] . '" value="Insert" class="button" onclick="addEditlistItem(\'' . $id . '\');" /><br />';
                    echo '<select class="' . $class . '" style="min-width:150px;" id="editlist_select_' . $id . '" name="' . $name . '" multiple="mulitple">';
                    foreach ( $list as $item ) {
                        echo "<option value='" . $item . "'>" . $item . "</option>";
                    }
                    echo '</select><br />';
                    echo '<input type="button" name="insert_button" value="Remove selected" class="button" onclick="removeEditlistItem(\'' . $id . '\');" />';
                    echo '<input type="hidden" name="' . $name . '" value="' . $options[$args['id']][$lang] . '" id="editlist_' . $id . '"/>';
                    break;

                case 'color':
                case 'colorpicker':
                    echo '<div class="color_selector">
                        <input type="text" class="picker_field" id="' . $id . '" name="' . $name . '" value="' . $options[$args['id']][$lang] . '" data-default-color="' . $value . '" />
                    </div>';
                    break;

                case 'image':
                case 'imageselect':
                    echo '
                    <label class="image_selector" for="upload_image_' . $args['id'] . '">
                        <input class="upload_image" id="upload_image_' . $args['id'] . '" type="text" size="36" name="' . $name . '" value="' . $value . '" />
                        <input class="upload_image_button" type="button" value="' . __( 'Choose Image' ) . '" />
                    </label>';

                    break;

                case 'include':
                    if ( !empty( $args['include'] ) && file_exists( $args['include'] ) ) {
                        include( $args['include'] );
                    } else {
                        throw new \Exception( 'AdminPage: Include not found or not set.' );
                    }
                    break;

                case 'hidden':
                case 'input':
                case 'text':
                default:
                    if ( !isset( $options[$args['id']][$lang] ) && !empty( $default ) ) {
                        $options[$args['id']][$lang] = $default;
                    }

                    echo '<input class="' . $class . '" id="' . $id . '" name="' . $name . '" size="40" type="' . ( $args['type'] === 'hidden' ? 'hidden' : 'text' ) . '" value=\'' . $options[$args['id']][$lang] . '\' />';
                    break;
            }
            $closed = true;
        }
    }

    /**
     * Returns options
     *
     * @param string $option
     * @param string $language
     * @return array
     */
    public static function ap_get_option( $option, $language = null ) {
        if ( empty( $language ) ) {
            $language = get_locale();
        }
        $output = array();
        $options = get_option( $option );
        if ( !empty( $options ) ) {
            foreach ( $options as $key => $value ) {
                if ( !is_array( $value ) ) {
                    $output[$key] = $value;
                } else {
                    if ( isset( $value['all'] ) ) {
                        $output[$key] = $value['all'];
                    }
                    if ( isset( $value[$language] ) ) {
                        $output[$key] = $value[$language];
                    }
                }
            }
        }
        return $output;
    }

    public function plugin_options_validate( $input ) {
        return $input;
    }
}