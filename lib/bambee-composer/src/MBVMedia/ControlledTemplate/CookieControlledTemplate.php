<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ControlledTemplate;


use MBVMedia\Lib\Cookie;

class CookieControlledTemplate extends ControlledTemplate {

    /**
     * @var string
     */
    private $cookieName;

    public function __construct( $template, $cookieName, $selectorOnClick, $selectorContainer ) {
        $this->cookieName = $cookieName;
        parent::__construct(  $template, $cookieName, $selectorOnClick, $selectorContainer );
    }

    public function hide() {
        Cookie::write( $this->cookieName, true );
    }

    public function hidden() {
        return Cookie::read( $this->cookieName ) == true;
    }
}