<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Inc;


use Inc\AdminPage;

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
        $coreDataFields = array_merge(
                array(
                        'coreDataDescription' => array(
                                'type' => 'label',
                                'title' => 'Shortcodes',
                                'description' => __(
                                        'You can use the [coredata]key[coredata]' .
                                        ' shortcode to display the core data field inside a post.',
                                        TextDomain
                                )
                        ),
                        'address' => array(
                                'type' => 'textarea',
                                'title' => __( 'Address', TextDomain ),
                                'default' => ''
                        ),
                        'email' => array(
                                'type' => 'textarea',
                                'title' => __( 'E-Mail address', TextDomain ),
                                'default' => ''
                        ),
                        'phone' => array(
                                'type' => 'textarea',
                                'title' => __( 'Phone', TextDomain ),
                                'default' => ''
                        ),
                ),
                $this->coreDataFields
        );
        if ( !empty( $coreDataFields ) ) {
            $coreDataPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $coreDataFields,
                    'id' => 'core-data',
                    'pageTitle' => __( 'Core data', TextDomain ),
                    'menuName' => __( 'Core data', TextDomain ),
                    'position' => 50,
                    'icon' => get_template_directory_uri() . '/includes/img/icons/core-data.png',
            ) );
        }

        # Global data page
        if ( !empty( $this->globalDataFields ) ) {
            $globalDataFields = array_merge(
                    array(
                            'globalDataDescription' => array(
                                    'type' => 'label',
                                    'title' => 'Shortcodes',
                                    'description' => __(
                                            'You can use the [globaldata]key[globaldata]' .
                                            ' shortcode to display the global data field inside a post.',
                                            TextDomain
                                    )
                            ),
                    ),
                    $this->globalDataFields
            );
            $globalFieldPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $globalDataFields,
                    'id' => 'global-data',
                    'pageTitle' => __( 'Global data', TextDomain ),
                    'menuName' => __( 'Global data', TextDomain ),
                    'position' => 51,
                    'icon' => get_template_directory_uri() . '/includes/img/icons/global-data.png',
            ) );
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