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

    public function addActions() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueStyles' ) );
    }

    public function addFilters() {
        add_filter( 'upload_mimes', array( $this, 'addSvgMediaSupport' ) );
        add_filter( 'edit_comments_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        foreach ( get_post_types() as $postType ) {
            add_filter( 'edit_' . $postType . '_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        }
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

        $bambeeWebsite = new CustomAdmin( $bambee );

        $bambeeWebsite->addActions();
        $bambeeWebsite->addFilters();

        $bambeeWebsite->setupCoreDataPage();

        /* If you set up custom fields to the globalDataPage */
        /* uncomment the following line. */
        //$bambeeWebsite->setGlobalDataPage();

        $bambee->getShortcodeManager()->extendTinyMCE();
    }
}