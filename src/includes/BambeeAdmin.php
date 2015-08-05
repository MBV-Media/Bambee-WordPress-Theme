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
     * @var array
     * @since 1.0.0
     */
    public $coreDataFields = array();

    /**
     * @var array
     * @since 1.0.0
     */
    public $globalDataFields = array();

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        # Core data page
        $core_data_fields = array_merge(
                array(
                        'clubname' => array( 'type' => 'textarea', 'title' => 'Clubname', 'default' => '' ),
                        'zeiten' => array( 'type' => 'textarea', 'title' => 'Öffnungszeiten', 'default' => '', 'multilang' => true ),
                        'adresse' => array( 'type' => 'textarea', 'title' => 'Adresse', 'default' => '' ),
                        'email' => array( 'type' => 'textarea', 'title' => 'E-Mail Adresse', 'default' => '' ),
                        'telefon' => array( 'type' => 'textarea', 'title' => 'Telefon', 'default' => '' ),
                ),
                $this->coreDataFields
        );
        if ( !empty( $core_data_fields ) ) {
            $coreDataPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $core_data_fields,
                    'id' => 'coredata',
                    'pageTitle' => 'Stammdaten',
                    'menuName' => 'Stammdaten',
                    'position' => 50,
                    'icon' => get_template_directory_uri() . '/includes/img/icons/stammdaten.png',
            ) );
        }

        # Global data page
        if ( !empty( $this->global_fields ) ) {
            $globalFieldPage = new AdminPage( array(
                    'location' => 'menu',
                    'fields' => $this->global_fields,
                    'id' => 'globaldata',
                    'pageTitle' => 'Globale Texte',
                    'menuName' => 'Globale Texte',
                    'position' => 51,
                    'icon' => get_template_directory_uri() . '/includes/img/icons/stammdaten.png',
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