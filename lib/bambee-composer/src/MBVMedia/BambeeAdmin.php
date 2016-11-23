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
     */
    public function addActions() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueStyles' ) );
        add_action( 'admin_init', array( $this, 'displaySvgThumbs' ) );
    }

    /**
     *
     */
    public function addFilters() {
        add_filter( 'upload_mimes', array( $this, 'addSvgMediaSupport' ) );
        add_filter( 'edit_comments_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        foreach ( get_post_types() as $postType ) {
            add_filter( 'edit_' . $postType . '_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        }
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
            'coreGoogleMapsTitle' => array(
                'type' => 'description',
                'title' => __( 'Google Maps' ),
                'description' => '',
            ),
            'lat' => array(
                'type' => 'text',
                'title' => __( 'Latitude', TextDomain ),
            ),
            'lng' => array(
                'type' => 'text',
                'title' => __( 'Longitude', TextDomain ),
            ),
            'gmapsApiKey' => array(
                'type' => 'text',
                'title' => __( 'API-Key', TextDomain ),
            ),
            'coreGoogleAnalyticsTitle' => array(
                'type' => 'description',
                'title' => __( 'Google Analytics' ),
                'description' => '',
            ),
            'googleTrackingCode' => array(
                'type' => 'text',
                'title' => __( 'Tracking-Code', TextDomain ),
                'default' => 'UA-XXXXX-X',
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
        wp_enqueue_style( 'custom_css', ThemeUrl . '/css/admin.min.css' );
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
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     *
     */
    public function displaySvgThumbs() {

        ob_start();

        add_action( 'shutdown', array( $this, 'svgThumbsFilter' ), 0 );
        add_filter( 'final_output', array( $this, 'svgFinalOutput' ) );
    }

    /**
     *
     */
    public function svgThumbsFilter() {

        $final = '';
        $ob_levels = count( ob_get_level() );

        for ( $i = 0; $i < $ob_levels; $i++ ) {

            $final .= ob_get_clean();

        }

        echo apply_filters( 'final_output', $final );
    }

    /**
     * @param $content
     * @return mixed
     */
    public function svgFinalOutput( $content ) {

        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
				<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',

            $content
        );

        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',

            $content
        );

        return $content;
    }

    /**
     * @param $option
     * @param int $default
     * @return int
     */
    public function modifyPostPerPageLimit( $option, $default = 20 ) {
        return $this->postPerPageLimit;
    }
}