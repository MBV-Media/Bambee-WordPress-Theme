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

    private $interval;

    public function __construct( $template, $cookieName, $selectorOnClick, $selectorContainer, $interval = 86400 ) {
        $this->cookieName = $cookieName;
        $this->interval = $interval;
        parent::__construct(  $template, $cookieName, $selectorOnClick, $selectorContainer );
    }

    public function hide() {
        Cookie::write( $this->cookieName, true, $this->interval );
    }

    public function hidden() {
        return Cookie::read( $this->cookieName ) == true;
    }
}