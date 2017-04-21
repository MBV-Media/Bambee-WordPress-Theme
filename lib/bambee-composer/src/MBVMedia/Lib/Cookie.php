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
        /*
         * Dont't use filter_input in this place.
         * It will always be null if the cookie was set in this process.
         */
        return isset( $_COOKIE[$name] ) ? $_COOKIE[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param int $expire
     */
    public static function write( $name, $value, $expire = 86400, $path = '/' ) {
        $_COOKIE[$name] = $value;
        setcookie( $name, $value, time() + $expire, $path );
    }
}