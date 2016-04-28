<?php

namespace MBVMedia\Shortcode\Lib;

/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 11:39
 */
abstract class BambeeShortcode implements Handleable {

    /**
     * @var array
     */
    private $supportedAtts;

    /**
     * BambeeShortcode constructor.
     */
    public function __construct() {
        $this->supportedAtts = array();
    }

    /**
     * @return array
     */
    public function getSupportedAtts() {
        return $this->supportedAtts;
    }

    /**
     * @param array $supportedAtts
     */
    public function setSupportedAtts( array $supportedAtts ) {
        $this->supportedAtts = $supportedAtts;
    }

    /**
     *
     */
    public static function addShortcode() {

        $tag = static::getShortcodeAlias();

        $class = get_called_class();
        if ( empty( $tag ) ) {
            $tag = self::getUnqualifiedClassName( $class );
        }

        add_shortcode( $tag, array( $class, 'doShortcode' ) );
    }

    /**
     * @param array $atts
     * @param string $content
     * @return mixed
     */
    public static function doShortcode( $atts = array(), $content = '' ) {
        $shortcodeObject = new static();
        $supportedAtts = $shortcodeObject->getSupportedAtts();

        /* TODO: Add shortcode name as argument to shortcode_atts */
        $atts = shortcode_atts( $supportedAtts, $atts );

        return $shortcodeObject->handleShortcode( $atts, $content );
    }

    public static function addTinyMCEPlugin() {
        $shortcodeObject = new static();
        add_action( 'admin_init', array( $shortcodeObject, 'wptuts_buttons' ) );
        add_action( 'admin_head', array( $shortcodeObject, 'jsData' ) );
    }

    public function jsData() {
        $tag = static::getUnqualifiedClassName();
        ?><script type="text/javascript">
            if( typeof window.bambeeShortcodes === 'undefined' ) {
                window.bambeeShortcodes = [];
            }

            bambeeShortcodes.push({
                tag: '<?php echo $tag; ?>',
                plugin: '<?php echo ucfirst( $tag ); ?>'
            });
        </script><?php
    }

    public function wptuts_buttons() {
        add_filter( "mce_external_plugins", array( $this, "wptuts_add_buttons" ) );
        add_filter( 'mce_buttons', array( $this, 'wptuts_register_buttons' ) );
    }

    public function wptuts_add_buttons( $plugin_array ) {
        $tag = static::getUnqualifiedClassName();
        $plugin_array[$tag] = get_template_directory_uri() . '/vendor/mbv-media/bambee-composer/src/MBVMedia/shortcode/lib/wptuts-plugin.php?tag=' . $tag;
//        $plugin_array[$tag] = get_template_directory_uri() . '/vendor/mbv-media/bambee-composer/src/MBVMedia/shortcode/lib/wptuts-plugin.js';
        return $plugin_array;
    }

    function wptuts_register_buttons( $buttons ) {
        $tag = static::getUnqualifiedClassName();
        array_push( $buttons, $tag ); // dropcap', 'recentposts
        return $buttons;
    }

    /**
     * @return string
     */
    public static function getShortcodeAlias() {
        return '';
    }

    /**
     * @return string
     */
    public static function getUnqualifiedClassName( $class = null ) {
        if( $class === null ) {
            $class = get_called_class();
        }
        $reflect = new \ReflectionClass( $class );
        return strtolower( $reflect->getShortName() );
    }
}