<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


class Control extends ThemeCustommizerElement {

    /**
     * @var Setting
     */
    private $setting;

    public function __construct( $id, array $args, Setting $setting = null ) {
        parent::__construct( $id, $args );
        $this->setSetting( $setting );
    }

    public function getSetting() {
        return $this->setting;
    }

    public function setSetting( Setting $setting ) {
        $this->setArg( 'settings', $setting->getId() );
        $this->setting = $setting;
    }

    public function register( \WP_Customize_Manager $wpCustomize ) {
        $this->setting->register( $wpCustomize );
        $wpCustomize->add_control( $this->getId(), $this->getArgs() );
    }
}