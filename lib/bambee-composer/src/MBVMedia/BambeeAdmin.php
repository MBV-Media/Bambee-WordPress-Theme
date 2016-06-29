<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use MagicAdminPage\MagicAdminPage;
use MBVMedia\Shortcode\Lib\ShortcodeManager;

/**
 * The class representing the WordPress Admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class BambeeAdmin {

    /**
     * @since 1.4.2
     * @var Bambee
     */
    private $bambee;

    /**
     * @since 1.4.2
     * @var MagicAdminPage
     */
    private $coreDataPage;

    /**
     * @since 1.4.2
     * @var MagicAdminPage
     */
    private $globalDataPage;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( Bambee $bambee ) {
        $this->bambee = $bambee;
    }

    public function addActions() {
        add_action( 'admin_enqueue_scripts', array( $this, '_enqueueCss' ) );
    }

    public function addFilters() {
        add_filter( 'upload_mimes', array( $this, '_addMimeTypes' ) );
    }

    /**
     * Enqueue the CSS.
     *
     * @since 1.0.0
     * @return void
     */
    public function _enqueueCss() {
        wp_enqueue_style( 'custom_css', ThemeUrl . '/css/admin.css' );
    }

    public function _addMimeTypes( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     *
     * @since 1.4.2
     */
    public function setupCoreDataPage() {

        $componentUrl = $this->bambee->getComponentUrl();

        $this->coreDataPage = new MagicAdminPage(
            'core-data',
            __( 'Core data', TextDomain ),
            __( 'Core data', TextDomain ),
            50,
            $componentUrl . '/img/icons/core-data.png'
        );

        $this->coreDataPage->addFields( array(
            'coreDataDescription' => array(
                'type' => 'description',
                'title' => 'Shortcodes',
                'description' => __(
                    'You can use the [coredata]key[coredata]' .
                    ' shortcode to display the core data field inside a post.',
                    TextDomain
                ),
            ),
            'address' => array(
                'type' => 'textarea',
                'title' => __( 'Address', TextDomain ),
            ),
            'email' => array(
                'type' => 'textarea',
                'title' => __( 'E-Mail address', TextDomain ),
            ),
            'phone' => array(
                'type' => 'textarea',
                'title' => __( 'Phone', TextDomain ),
            ),
            'googleTrackingCode' => array(
                'type' => 'text',
                'title' => __( 'Google Tracking-Code', TextDomain ),
                'default' => 'UA-XXXXX-X',
            )
        ) );
    }

    /**
     *
     * @since 1.4.2
     */
    private function setupGlobalDataPage() {

        $componentUrl = $this->bambee->getComponentUrl();

        $this->globalDataPage = new MagicAdminPage(
            'global-data',
            __( 'Global data', TextDomain ),
            __( 'Global data', TextDomain ),
            51,
            $componentUrl . '/img/icons/global-data.png'
        );

        $this->globalDataPage->addField( array(
            'name' => 'globalDataDescription',
            'type' => 'label',
            'title' => 'Shortcodes',
            'description' => __(
                'You can use the [globaldata]key[globaldata] ' .
                ' shortcode to display the global data field inside a post.',
                TextDomain
            ),
        ) );
    }

    /**
     * @since 1.4.2
     * @return Bambee
     */
    public function getBambee() {
        return $this->bambee;
    }

    /**
     * @return MagicAdminPage
     */
    public function getCoreDataPage() {
        return $this->coreDataPage;
    }

    /**
     * @param MagicAdminPage $coreDataPage
     */
    public function setCoreDataPage( MagicAdminPage $coreDataPage ) {
        $this->coreDataPage = $coreDataPage;
    }

    /**
     * @return MagicAdminPage
     */
    public function getGlobalDataPage() {
        return $this->globalDataPage;
    }

    /**
     * @param MagicAdminPage $globalDataPage
     */
    public function setGlobalDataPage( MagicAdminPage $globalDataPage ) {
        $this->globalDataPage = $globalDataPage;
    }
}