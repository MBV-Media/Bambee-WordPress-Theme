<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 16:17
 */

namespace MBVMedia\lib\shortcode;


use MBVMedia\Lib\BambeeShortcode;

/**
 * Load a core_data field.
 *
 * @package MBVMedia\lib\shortcode
 * @since 1.0.0
 * @param array $args
 * @param string $content
 * @return string
 *
 * @example
 *  Usage:
 *    [coredata]street[/coredata]
 */
class CoreData extends BambeeShortcode {

    public function handleShortcode( array $atts = array(), $content = '' ) {
        global $bambeeWebsite;
        if ( empty( $content ) || empty( $bambeeWebsite->coreData[$content] ) ) {
            return '';
        }
        return nl2br( $bambeeWebsite->coreData[$content] );
    }
}