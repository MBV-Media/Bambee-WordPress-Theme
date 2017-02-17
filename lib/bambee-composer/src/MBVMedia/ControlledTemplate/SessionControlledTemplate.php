<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ControlledTemplate;


use MBVMedia\Lib\Session;

class SessionControlledTemplate extends ControlledTemplate {

    /**
     * @var string
     */
    private $sessionVar;

    public function __construct( $template, $sessionVar, $selectorOnClick, $selectorContainer ) {
        $this->sessionVar = $sessionVar;
        parent::__construct(  $template, $sessionVar, $selectorOnClick, $selectorContainer );
    }

    /**
     *
     */
    public function addActions() {
        add_action( 'init', array( 'MBVMedia\Lib\Session', 'start' ) );
        parent::addActions();
    }

    /**
     *
     */
    public function hide() {
        Session::setVar( $this->sessionVar, true );
    }

    /**
     * @return bool
     */
    public function hidden() {
        return Session::getVar( $this->sessionVar ) === true;
    }
}