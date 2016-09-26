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
abstract class BambeeAdmin extends BambeeBase {

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
     * @var integer
     */
    private $postPerPageLimit;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( Bambee $bambee ) {
        $this->bambee = $bambee;
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
            ),
            'gmapsApiKey' => array(
                'type' => 'text',
                'title' => __( 'Google Maps API-Key', TextDomain ),
            ),
        ) );

        $this->postPerPageLimit = 50;
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

    /**
     * @param integer $postPerPageLitmit
     */
    public function setPostPerPageLimit( $postPerPageLitmit ) {
        $this->postPerPageLimit = $postPerPageLitmit;
    }


    /**
     * Action-hook callbacks
     */

    /**
     * Enqueue the CSS.
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueueStyles() {
        wp_enqueue_style( 'custom_css', ThemeUrl . '/css/admin.css' );
    }


    /**
     * Filter-hook callbacks
     */

    /**
     * @param $mimes
     * @return mixed
     */
    public function addSvgMediaSupport( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }


    public function modifyPostPerPageLimit( $option, $default = 20 ) {
        return $this->postPerPageLimit;
    }
}