<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 16:50
 */

namespace MBVMedia\lib\shortcode;


use MBVMedia\Lib\BambeeShortcode;

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
        $this->setSupportedAtts( array(
                'class' => ''
        ) );
    }

    public function handleShortcode( array $atts = array(), $content = '' ) {
        $class = isset( $atts['class'] ) ? $atts['class'] : '';
        $content = sprintf(
                '<div class="row %s">%s</div>',
                $class,
                $content
        );
        return apply_filters( 'the_content', $content );
    }
}