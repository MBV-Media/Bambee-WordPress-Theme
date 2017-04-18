<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


class Panel extends ThemeCustommizerElement {

    private $sectionList;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );
        $this->sectionList = array();
    }

    public function addSection( Section $section ) {
        $section->setArg( 'panel', $this->getId() );
        $this->sectionList[$section->getId()] = $section;
    }

    public function register( \WP_Customize_Manager $wpCustomize ) {

        foreach( $this->sectionList as $section ) {
            $section->register( $wpCustomize );
        }
        $wpCustomize->add_panel( $this->getId(), $this->getArgs() );
    }
}