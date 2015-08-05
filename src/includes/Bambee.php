<?php namespace Inc;


use Inc\MottoDays;

/**
 * Class Bambee
 *
 * Class for both website (end-user frontend) and Wordpress admin
 */
class Bambee {

    /**
     * @var null|MottoDays
     */
    public $mottoDays = null;

    /**
     * @var int
     */
    public $postThumbnailWidth = 624;

    /**
     * @var int
     */
    public $postThumbnailHeight = 9999;

    /**
     * @var boolean
     */
    public $postThumbnailCrop = false;

    /**
     * @var array
     */
    private $additionalMenus = array();

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
     * Add menus
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
     * Register post types
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