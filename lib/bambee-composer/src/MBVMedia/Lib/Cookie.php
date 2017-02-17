<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\Lib;


class Cookie {

    /**
     * @param string $name
     * @return mixed
     */
    public static function read( $name ) {
        return filter_input( INPUT_COOKIE, $name );
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param int $expire
     */
    public static function write( $name, $value, $expire = 86400, $path = '/' ) {
        setcookie( $name, $value, time() + $expire, $path );
    }
}