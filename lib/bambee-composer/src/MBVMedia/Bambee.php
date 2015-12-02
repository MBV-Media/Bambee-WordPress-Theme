<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


/**
 * The class representing both website (user frontend) and WordPress admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class Bambee {

    /**
     * @since 1.0.0
     * @var int
     */
    protected $postThumbnailWidth = 624;

    /**
     * @since 1.0.0
     * @var int
     */
    protected $postThumbnailHeight = 9999;

    /**
     * @since 1.0.0
     * @var boolean
     */
    protected $postThumbnailCrop = false;

    /**
     * @since 1.0.0
     * @var array
     */
    protected $additionalMenus = array();

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
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
                'footer-menu' => __( 'Footer Menu' ),
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
        $componentUrl = Bambee::getComponentUrl();

        register_post_type( 'gallery', array(
                'labels' => array(
                    'name' => __( 'Galleries', TextDomain ),
                    'singular_name' => __( 'Gallery', TextDomain ),
                ),
                'taxonomies' => array( 'category' ),
                'menu_icon' => $componentUrl . '/img/icons/gallery.png',
                'public' => true,
                'has_archiv' => true,
                'show_ui' => true,
                'capability_type' => 'post',
                'hierarchical' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                ),
                'taxonomies' => array( 'category' ),
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'excerpt' => true,
            )
        );

        add_post_type_support( 'page', 'excerpt', true );
    }

    /**
     * Returns url to compentents of bambee
     *
     * @return mixed
     */
    static function getComponentUrl() {
        // fix for windows path
        $fixedAbsPath = str_replace( '\\', '/', ABSPATH );
        $fixedDirName = str_replace( '\\', '/', dirname( __FILE__ ) );
        // replace absolute path with url
        $componentUrl = str_replace( $fixedAbsPath, get_bloginfo( 'wpurl' ) . '/', $fixedDirName );

        return $componentUrl;
    }
}