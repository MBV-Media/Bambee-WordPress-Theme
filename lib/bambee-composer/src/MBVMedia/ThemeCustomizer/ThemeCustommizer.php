<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


class ThemeCustommizer {

    const SECTION_ID = 'bambee_comment';
    const CONTROL_ID = self::SECTION_ID . '_textbox';
    const SETTING_ID = self::CONTROL_ID . '_position';

    private $panelList;
    private $sectionList;

    public function __construct() {
        $this->panelList = array();
        $this->sectionList = array();
    }

    public function addPanel( Panel $panel ) {
        $this->panelList[$panel->getId()] = $panel;
    }

    public function addSection( Section $section ) {
        $this->sectionList[$section->getId()] = $section;
    }

    public function register() {
        add_action( 'customize_register', array( $this, 'actionCustomizeRegister' ) );
    }

    public function actionCustomizeRegister( $wp_customize ) {

        $wpCustomize = $wp_customize;

        foreach ( $this->panelList as $panel ) {
            $panel->register( $wpCustomize );
        }

        foreach ( $this->sectionList as $section ) {
            $section->register( $wpCustomize );
        }
    }
}