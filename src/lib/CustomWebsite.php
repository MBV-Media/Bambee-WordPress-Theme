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
    protected function __construct( CustomBambee $bambee ) {
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

        $bambeeWebsite = new CustomWebsite( $bambee );
        $GLOBALS['bambeeWebsite'] = $bambeeWebsite;

        $bambee->getShortcodeManager()->addShortcodes();

        $bambeeWebsite->addActions();
        $bambeeWebsite->addFilters();
        $bambeeWebsite->addScripts();
        $bambeeWebsite->addStyles();
    }

    /**
     * @return CustomWebsite
     */
    public static function self() {
        return parent::self();
    }
}