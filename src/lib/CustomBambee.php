<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use MBVMedia\Bambee;

/**
 * The class representing both website (user frontend) and WordPress admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomBambee extends Bambee {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    private function addActions() {
        add_action( 'after_setup_theme', array( $this, 'loadThemeTextdomain' ) );
        add_action( 'after_setup_theme', array( $this, 'addThemeSupportPostThumbnails' ) );
        add_action( 'after_setup_theme', array( $this, 'addThemeSupportCustomLogo' ) );
        add_action( 'after_setup_theme', array( $this, 'addThemeSupportCustomHeader' ) );
        add_action( 'after_setup_theme', array( $this, 'addPostTypeSupportExcerpt' ) );
        add_action( 'after_setup_theme', array( $this, 'registerMenus' ) );
        add_action( 'after_setup_theme', array( $this, 'registerPostTypes' ) );
    }

    /**
     * This is where the magic begins.
     *
     * @since 1.4.2
     */
    public static function run() {
        global $bambee;

        $bambee = new CustomBambee();

        $bambee->addMenu( 'header-menu', __( 'Header Menu' ) );
        $bambee->addMenu( 'footer-menu', __( 'Footer Menu' ) );

        $bambee->addActions();

        if ( is_admin() ) {
            CustomAdmin::run( $bambee );
        } else {
            CustomWebsite::run( $bambee );
        }
    }
}