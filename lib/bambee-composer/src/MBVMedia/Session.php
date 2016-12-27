<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia;


class Session {

    /**
     * @var int
     */
    private static $lifetime = 86400;

    /**
     * @return int
     */
    public static function getLifetime() {
        return self::$lifetime;
    }

    /**
     * @param $lifetime
     */
    public static function setLifetime( $lifetime ) {
        self::$lifetime = $lifetime;
    }

    /**
     *
     */
    public static function start() {
        if( session_status() == PHP_SESSION_NONE ) {
            session_set_cookie_params( self::$lifetime );
            session_start();
        }
    }

    /**
     * @param $var
     * @return null
     */
    public static function getVar( $var ) {
        if( isset( $_SESSION[$var] ) ) {
            return $_SESSION[$var];
        }

        return null;
    }

    /**
     * @param $var
     * @param $value
     */
    public static function setVar( $var, $value ) {
        $_SESSION[$var] = $value;
    }
}