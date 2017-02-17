<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use MBVMedia\BambeeAdmin;

/**
 * The class representing the WordPress Admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomAdmin extends BambeeAdmin {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( CustomBambee $bambee ) {
        parent::__construct( $bambee );
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
     *
     * @param CustomBambee $bambee
     */
    public static function run( CustomBambee $bambee ) {

        $bambeeAdmin = new CustomAdmin( $bambee );
        $GLOBALS['bambeeAdmin'] = $bambeeAdmin;

        $bambee->getShortcodeManager()->extendTinyMCE();

        $bambeeAdmin->addActions();
        $bambeeAdmin->addFilters();

        $bambeeAdmin->setupCoreDataPage();

        /* If you set up custom fields to the globalDataPage */
        /* uncomment the following line. */
        //$bambeeAdmin->setupGlobalDataPage();
    }
}