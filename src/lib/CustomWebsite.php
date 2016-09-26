<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use MagicAdminPage\MagicAdminPage;
use MBVMedia\BambeeWebsite;

/**
 * The class representing the website (user frontend).
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomWebsite extends BambeeWebsite {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( CustomBambee $bambee ) {
        parent::__construct( $bambee );
    }

    public function addActions() {
        parent::addActions();
    }

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

        $bambeeWebsite = new CustomWebsite( $bambee );
        $GLOBALS['bambeeWebsite'] = $bambeeWebsite;

        $bambee->getShortcodeManager()->addShortcodes();

        $bambeeWebsite->addActions();
        $bambeeWebsite->addFilters();
        $bambeeWebsite->addScripts();
        $bambeeWebsite->addStyles();
    }
}