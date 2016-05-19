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
     * @since 1.4.2
     */
    public static function run() {
        global $bambee;

        $bambee = new CustomBambee();

        if ( is_admin() ) {
            CustomAdmin::run( $bambee );
        } else {
            CustomWebsite::run( $bambee );
        }
    }
}