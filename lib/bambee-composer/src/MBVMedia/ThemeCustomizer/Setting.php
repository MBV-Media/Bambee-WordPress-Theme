<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


class Setting extends ThemeCustommizerElement {

    public function register( \WP_Customize_Manager $wpCustomize ) {
        $wpCustomize->add_setting( $this->getId(), $this->getArgs() );
    }
}