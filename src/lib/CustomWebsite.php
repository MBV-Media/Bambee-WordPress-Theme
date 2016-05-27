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


    /**
     * This is where the magic begins.
     *
     * @since 1.4.2
     *
     * @param CustomBambee $bambee
     */
    public static function run( CustomBambee $bambee ) {
        global $bambeeWebsite;

        $bambeeWebsite = new CustomWebsite( $bambee );

        $bambee->getShortcodeManager()->addShortcodes();

        $bambeeWebsite->addActions();

        # Enqueue additional scripts
        $bambeeWebsite->addScript( 'comment-reply', false );
        $bambeeWebsite->addScript( 'vendor', ThemeUrl . '/js/vendor.min.js', array( 'jquery' ), false, true );
        $bambeeWebsite->addScript( 'main', ThemeUrl . '/js/main.min.js', array( 'jquery' ), false, true );

        # Enqueue additional styles
        $bambeeWebsite->addStyle( 'theme', get_bloginfo( 'stylesheet_url' ) );
        $bambeeWebsite->addStyle( 'vendor', ThemeUrl . '/css/vendor.min.css' );
        $bambeeWebsite->addStyle( 'main', ThemeUrl . '/css/main.min.css' );
    }
}