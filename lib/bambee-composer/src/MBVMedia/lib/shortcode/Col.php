<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 16:43
 */

namespace MBVMedia\lib\shortcode;


use MBVMedia\Lib\BambeeShortcode;

/**
 * Generate a foundation grid column.
 *
 * @package MBVMedia\lib\shortcode
 * @since 1.0.0
 * @param array $args
 * @param string $content
 * @return string
 *
 * @example
 *  Usage:
 *    [col small=12 medium=6 large=4]Hello World![/col]
 */
class Col extends BambeeShortcode {

    public function __construct() {
        $this->setSupportedAtts( array(
                'small' => '',
                'medium' => '',
                'large' => '',
                'class' => '',
        ) );
    }

    public function handleShortcode( array $atts = array(), $content = '' ) {
        $class = 'column ';

        if ( !empty( $atts['small'] ) ) {
            $class .= ' small-' . $atts['small'];
        }
        if ( !empty( $atts['medium'] ) ) {
            $class .= ' medium-' . $atts['medium'];
        }
        if ( !empty( $atts['large'] ) ) {
            $class .= ' large-' . $atts['large'];
        }

        if ( !empty( $atts['class'] ) ) {
            $class .= ' ' . $atts['class'];
        }

        $content = sprintf(
                '<div class="%s">%s</div>',
                $class,
                $content
        );

        return apply_filters( 'the_content', $content );
    }
}