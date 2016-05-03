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