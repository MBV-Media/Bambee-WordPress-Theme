<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\Lib;


class Session {

    /**
     * @param int $lifetime
     */
    public static function start( $lifetime = 0 ) {
        if( session_status() == PHP_SESSION_NONE ) {
            session_set_cookie_params( $lifetime );
            session_start();
        }
    }

    /**
     * @param $var
     * @return mixed|null
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