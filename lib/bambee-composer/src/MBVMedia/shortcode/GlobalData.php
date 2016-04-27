<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 16:27
 */

namespace MBVMedia\Shortcode;


use MBVMedia\Shortcode\Lib\BambeeShortcode;

/**
 * Load a global_data field.
 *
 * @package MBVMedia\lib\shortcode
 * @since 1.0.0
 * @param array $args
 * @param string $content
 * @return string
 *
 * @example
 *  Usage:
 *    [globaldata]key[/globaldata]
 */
class GlobalData extends BambeeShortcode {

    public function handleShortcode( array $atts = array(), $content = '' ) {
        global $bambeeWebsite;
        if ( empty( $content ) || empty( $bambeeWebsite->globalData[$content] ) ) {
            return '';
        }

        return $bambeeWebsite->globalData[$content];
    }
}