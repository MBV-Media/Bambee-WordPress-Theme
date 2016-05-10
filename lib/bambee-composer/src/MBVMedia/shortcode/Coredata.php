<?php

namespace MBVMedia\Shortcode;


use MBVMedia\Shortcode\Lib\BambeeShortcode;

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
        return nl2br( $bambeeWebsite->getCoreData( $content ) );
    }
}