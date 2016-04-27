<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 11:43
 */

namespace MBVMedia\Shortcode\Lib;


interface Handleable {
    public function handleShortcode( array $atts = array(), $content = '');
}