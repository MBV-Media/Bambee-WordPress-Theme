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
        $core_data_fields = array_merge(
                array(
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
        if ( !empty( $core_data_fields ) ) {
            $coreDataPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $core_data_fields,
                    'id' => 'core-data',
                    'pageTitle' => __( 'Core data', TextDomain ),
                    'menuName' => __( 'Core data', TextDomain ),
                    'position' => 50,
                    'icon' => get_template_directory_uri() . '/includes/img/icons/core-data.png',
            ) );
        }

        # Global data page
        if ( !empty( $this->globalDataFields ) ) {
            $globalFieldPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $this->globalDataFields,
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