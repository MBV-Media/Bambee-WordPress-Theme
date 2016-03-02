<?php

namespace MBVMedia\Lib;

/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 11:39
 */
abstract class BambeeShortcode implements Handleable {

    /**
     * @param array $locationInfo
     */
    public static function loadShortcodes( array $locationInfo ) {

        $shortcodeFolder = scandir( $locationInfo['path'] );

        foreach ( $shortcodeFolder as $shortcodeFile ) {
            if ( !is_dir( $locationInfo['path'] . $shortcodeFile ) ) {

                $class = $locationInfo['namespace'] . pathinfo( $shortcodeFile, PATHINFO_FILENAME );

                if ( is_callable( array( $class, 'addShortcode' ) ) ) {
                    $class::addShortcode();
                }
            }
        }
    }

    /**
     *
     */
    public static function addShortcode() {
        $class = get_called_class();

        $shortcode = static::getShortcodeAlias();
        if ( empty( $shortcode ) ) {
            $reflect = new \ReflectionClass( $class );
            $shortcode = strtolower( $reflect->getShortName() );
        }

        add_shortcode( $shortcode, array( $class, 'doShortcode' ) );
    }

    /**
     * @param array $args
     * @param string $content
     * @return mixed
     */
    public static function doShortcode( $args = array(), $content = '' ) {
        $shortcodeObject = new static();
        if ( !is_array( $args ) ) {
            $args = array();
        }
        return $shortcodeObject->handleShortcode( $args, $content );
    }

    /**
     * @return string
     */
    public static function getShortcodeAlias() {
        return '';
    }
}