<?php

namespace MBVMedia\Shortcode\Lib;


interface Handleable {
    public function handleShortcode( array $atts = array(), $content = '');
}