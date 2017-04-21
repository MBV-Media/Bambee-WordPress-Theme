<?php

namespace MBVMedia\Shortcode;


use MBVMedia\Shortcode\Lib\BambeeShortcode;

/**
 * Generate a foundation grid row.
 *
 * @package MBVMedia\lib\shortcode
 * @since 1.0.0
 * @param array $args
 * @param string $content
 * @return string
 *
 * @example
 *  Usage:
 *    [row]Hello World![/row]
 */
class Row extends BambeeShortcode {

    public function __construct() {
        $this->addAttribute( 'class' );
    }

    public function handleShortcode( array $atts = array(), $content = '' ) {
        $class = isset( $atts['class'] ) ? $atts['class'] : '';
        $content = sprintf(
                '<div class="row %s">%s</div>',
                $class,
                $content
        );
        return $content;
    }
}