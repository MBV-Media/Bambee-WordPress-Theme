<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use MagicAdminPage\MagicAdminPage;

/**
 * The class representing the WordPress Admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class BambeeAdmin {

    /**
     * @since 1.0.0
     * @var array
     */
    protected $coreDataFields = array();

    /**
     * @since 1.0.0
     * @var array
     */
    protected $globalDataFields = array();

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        $componentUrl = Bambee::getComponentUrl();

        # Core data page
        $coreDataPage = new MagicAdminPage(
            'core-data',
            __( 'Core data', TextDomain ),
            __( 'Core data', TextDomain ),
            50,
            $componentUrl . '/img/icons/core-data.png'
        );
        $coreDataFields = array_merge(
            array(
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
            ),
            $this->coreDataFields
        );
        $coreDataPage->addFields( $coreDataFields );

        # Global data page
        if ( !empty( $this->globalDataFields ) ) {
            $globalDataPage = new MagicAdminPage(
                'global-data',
                __( 'Global data', TextDomain ),
                __( 'Global data', TextDomain ),
                51,
                $componentUrl . '/img/icons/global-data.png'
            );
            $globalDataFields = array_merge(
                array(
                    'globalDataDescription' => array(
                        'type' => 'label',
                        'title' => 'Shortcodes',
                        'description' => __(
                            'You can use the [globaldata]key[globaldata]' .
                            ' shortcode to display the global data field inside a post.',
                            TextDomain
                        ),
                    ),
                ),
                $this->globalDataFields
            );
            $globalDataPage->addFields( $globalDataFields );
        }

        add_action( 'admin_enqueue_scripts', array( $this, '_enqueueCss' ) );
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
}