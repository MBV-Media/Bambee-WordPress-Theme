<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Inc;


use Inc\MottoDays;

/**
 * The class representing both website (user frontend) and WordPress admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class Bambee {

    /**
     * @var null|MottoDays
     * @since 1.0.0
     */
    public $mottoDays = null;

    /**
     * @var int
     * @since 1.0.0
     */
    public $postThumbnailWidth = 624;

    /**
     * @var int
     * @since 1.0.0
     */
    public $postThumbnailHeight = 9999;

    /**
     * @var boolean
     * @since 1.0.0
     */
    public $postThumbnailCrop = false;

    /**
     * @var array
     * @since 1.0.0
     */
    private $additionalMenus = array();

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        $this->mottoDays = new MottoDays();

        # Thumbnail-Support
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( $this->postThumbnailWidth, $this->postThumbnailHeight, $this->postThumbnailCrop );

        add_action( 'init', array( $this, '_createPostTypes' ) );
        add_action( 'init', array( $this, '_registerMenus' ) );
        # Language Text-Domain
        add_action( 'after_setup_theme', function () {
            load_theme_textdomain( TextDomain, ThemeDir . '/languages' );
        }, 10 );
    }

    /**
     * Register default menus for header and footer.
     *
     * @since 1.0.0
     * @return void
     */
    public function _registerMenus() {
        $menus = array_merge(
                array(
                        'header-menu' => __( 'Header Menu' ),
                        'footer-menu' => __( 'Footer Menu' )
                ),
                $this->additionalMenus
        );

        register_nav_menus( $menus );
    }

    /**
     * Register post type 'gallery' and add excerpt to post
     * type 'page'.
     *
     * @since 1.0.0
     * @return void
     */
    public function _createPostTypes() {
        register_post_type( 'gallery', array(
                        'labels' => array(
                                'name' => __( 'Galerien' ),
                                'singular_name' => __( 'Galerie' )
                        ),
                        'taxonomies' => array( 'category' ),
                        'menu_icon' => get_template_directory_uri() . '/includes/img/icons/galerie.png',
                        'public' => true,
                        'has_archiv' => true,
                        'show_ui' => true, // UI in admin panel
                        'capability_type' => 'post',
                        'hierarchical' => true,
                        'supports' => array( 'title', 'editor', 'thumbnail', 'trackbacks', 'custom-fields', 'revisions' ),
                        'taxonomies' => array( 'category' ),
                        'exclude_from_search' => true,
                        'publicly_queryable' => true,
                        'excerpt' => true
                )
        );

        add_post_type_support( 'page', 'excerpt', true );
    }
}