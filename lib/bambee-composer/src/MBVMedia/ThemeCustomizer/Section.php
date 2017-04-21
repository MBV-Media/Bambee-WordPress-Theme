<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


class Section extends ThemeCustommizerElement {

    private $controlList;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );
        $this->controlList = array();
    }

    public function addControl( Control $control ) {
        $control->setArg( 'section', $this->getId() );
        $this->controlList[$control->getId()] = $control;
    }

    public function register( \WP_Customize_Manager $wpCustomize ) {
        $wpCustomize->add_section( $this->getId(), $this->getArgs() );

        foreach( $this->controlList as $control ) {
            $control->register( $wpCustomize );
        }
    }
}