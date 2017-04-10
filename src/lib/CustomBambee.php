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
    protected function __construct() {
        parent::__construct();
    }

    /**
     * Add custom actions to this method or override predefined actions
     */
    public function addActions() {
        parent::addActions();
    }

    /**
     * Add custom filters to this method or override predefined actions
     */
    public function addFilters() {
        parent::addFilters();
    }

    /**
     * This is where the magic begins.
     *
     * @since 1.4.2
     */
    public static function run() {

        $bambee = new CustomBambee();
        $GLOBALS['bambee'] = $bambee;

        $bambee->addMenu( 'header-menu', __( 'Header Menu' ) );
        $bambee->addMenu( 'footer-menu', __( 'Footer Menu' ) );

        $bambee->addActions();
        $bambee->addFilters();

        if ( is_admin() ) {
            CustomAdmin::run( $bambee );
        } else {
            CustomWebsite::run( $bambee );
        }
    }

    /**
     * @return CustomBambee
     */
    public static function self() {
        return parent::self();
    }
}