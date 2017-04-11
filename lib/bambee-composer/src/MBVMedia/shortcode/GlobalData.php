<?php

namespace MBVMedia\Shortcode;


use MBVMedia\BambeeWebsite;
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
        return BambeeWebsite::self()->getGlobalData( $content );
    }
}