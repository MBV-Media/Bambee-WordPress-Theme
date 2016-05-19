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
     * @since 1.0.0
     * @var array
     */
    private $coreDataFieldList;

    /**
     * @since 1.0.0
     * @var array
     */
    private $globalDataFieldList;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( Bambee $bambee ) {

        $this->bambee = $bambee;

        $this->coreDataFieldList = array();

        $this->globalDataFieldList = array();

        $this->setupCoreData();

        $this->setupGlobalData();

        add_action( 'admin_enqueue_scripts', array( $this, '_enqueueCss' ) );
        add_action( 'init', array( $this, '_initActionCallback' ) );
    }

    /**
     * @since 1.4.2
     * @return Bambee
     */
    public function getBambee() {
        return $this->bambee;
    }

    /**
     * @since 1.4.2
     *
     * @param $tag
     * @param array $args   @see MagicAdminPage
     */
    public function addCoreDataField( $tag, array $args ) {
        $this->coreDataFieldList[$tag] = $args;
    }

    /**
     * @since 1.4.2
     *
     * @param $tag
     * @param array $args
     */
    public function addGlobalDataField( $tag, array $args ) {
        $this->globalDataFieldList[$tag] = $args;
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

    /**
     *
     * @since 1.4.2
     */
    private function setupCoreData() {

        $this->addCoreDataField( 'coreDataDescription', array(
            'type' => 'description',
            'title' => 'Shortcodes',
            'description' => __(
                'You can use the [coredata]key[coredata]' .
                ' shortcode to display the core data field inside a post.',
                TextDomain
            ),
        ) );

        $this->addCoreDataField( 'address', array(
            'type' => 'textarea',
            'title' => __( 'Address', TextDomain ),
        ) );

        $this->addCoreDataField( 'email', array(
            'type' => 'textarea',
            'title' => __( 'E-Mail address', TextDomain ),
        ) );

        $this->addCoreDataField( 'phone', array(
            'type' => 'textarea',
            'title' => __( 'Phone', TextDomain ),
        ) );

        $this->addCoreDataField( 'googleTrackingCode', array(
            'type' => 'text',
            'title' => __( 'Google Tracking-Code', TextDomain ),
            'default' => 'UA-XXXXX-X',
        ) );
    }

    /**
     *
     * @since 1.4.2
     */
    private function setupGlobalData() {

        $this->addGlobalDataField( 'globalDataDescription', array(
            'type' => 'label',
            'title' => 'Shortcodes',
            'description' => __(
                'You can use the [globaldata]key[globaldata] '.
                ' shortcode to display the global data field inside a post.',
                TextDomain
            ),
        ) );
    }

    /**
     *
     * @since 1.4.2
     */
    public function _initActionCallback() {

        $componentUrl = $this->bambee->getComponentUrl();

        # Core data page
        $coreDataPage = new MagicAdminPage(
            'core-data',
            __( 'Core data', TextDomain ),
            __( 'Core data', TextDomain ),
            50,
            $componentUrl . '/img/icons/core-data.png'
        );

        $coreDataPage->addFields( $this->coreDataFieldList );

        # Global data page
        if ( count( $this->globalDataFieldList ) > 1 ) {

            $globalDataPage = new MagicAdminPage(
                'global-data',
                __( 'Global data', TextDomain ),
                __( 'Global data', TextDomain ),
                51,
                $componentUrl . '/img/icons/global-data.png'
            );
            $globalDataPage->addFields( $this->globalDataFieldList );
        }
    }
}