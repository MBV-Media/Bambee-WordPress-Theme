<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 13:14
 */

namespace Lib\Shortcode;


use Lib\CustomShortcode;

class Example extends CustomShortcode {

    public function __construct() {
        $this->setSupportedAtts( array(
                'foo' => 'bar'
        ) );
    }

    public function handleShortcode( array $atts = array(), $content = '' ) {
        return 'This is an example with the default attribute: ' . $atts['foo'];
    }

    public static function getShortcodeAlias() {
        return 'ex-ample';
    }
}