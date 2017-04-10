<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia;


abstract class Singleton {

    private static $instance = null;

    public static function self() {
        if( null === self::$instance ) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}