<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Inc;


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
        # Core data page
        $coreDataPage = new MagicAdminPage(
            'core-data',
            __( 'Core data', TextDomain ),
            __( 'Core data', TextDomain ),
            50,
            get_template_directory_uri() . '/includes/img/icons/core-data.png'
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
                get_template_directory_uri() . '/includes/img/icons/global-data.png'
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
        wp_enqueue_style( 'custom_css', get_template_directory_uri() . '/css/admin/admin.css' );
    }
}