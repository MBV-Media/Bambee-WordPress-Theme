<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;
use MBVMedia\Shortcode\Lib\ShortcodeManager;


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
    private $postThumbnailWidth;

    /**
     * @since 1.0.0
     * @var int
     */
    private $postThumbnailHeight;

    /**
     * @since 1.0.0
     * @var boolean
     */
    private $postThumbnailCrop;

    /**
     * @since 1.0.0
     * @var array
     */
    private $menuList;

    /**
     * @since 1.4.2
     * @var array
     */
    private $postTypeList;

    /**
     * @since 1.4.2
     * @var ShortcodeManager
     */
    private $shortcodeManager;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {

        $this->postThumbnailWidth = 624;

        $this->postThumbnailHeight = 999;

        $this->postThumbnailCrop = false;

        $this->menuList = array();

        $this->postTypeList = array();

        $this->shortcodeManager = new ShortcodeManager();
        $this->shortcodeManager->loadShortcodes(
            dirname( __FILE__ ) . '/shortcode/',
            '\MBVMedia\Shortcode\\'
        );
        $this->shortcodeManager->loadShortcodes(
            ThemeDir . '/lib/shortcode/',
            '\Lib\Shortcode\\'
        );
    }

    /**
     * Set up the theme configuration
     */
    public function setupTheme() {

        add_theme_support( 'custom-logo', array(
            'width' => 300,
            'height' => 200,
            'flex-width' => true,
            'flex-height' => true,
        ) );

        add_theme_support( 'custom-header', array(
            'width' => 1200,
            'height' => 450,
            'flex-width' => true,
            'flex-height' => true,
        ) );

        $componentUrl = $this->getComponentUrl();
        $this->addPostType( 'gallery', array(
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
        ) );
    }

    /**
     * @since 1.4.2
     * @return ShortcodeManager
     */
    public function getShortcodeManager() {
        return $this->shortcodeManager;
    }

    /**
     * @since 1.4.2
     *
     * @param int $postThumbnailWidth
     */
    public function setPostThumbnailWidth( $postThumbnailWidth ) {
        $this->postThumbnailWidth = $postThumbnailWidth;
    }

    /**
     * @since 1.4.2
     *
     * @param int $postThumbnailHeight
     */
    public function setPostThumbnailHeight( $postThumbnailHeight ) {
        $this->postThumbnailHeight = $postThumbnailHeight;
    }

    /**
     * @since 1.4.2
     *
     * @param boolean $postThumbnailCrop
     */
    public function setPostThumbnailCrop( $postThumbnailCrop ) {
        $this->postThumbnailCrop = $postThumbnailCrop;
    }

    /**
     *
     * @since 1.4.2
     */
    public function _initActionCallback() {

        # Thumbnail-Support
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( $this->postThumbnailWidth, $this->postThumbnailHeight, $this->postThumbnailCrop );

        add_post_type_support( 'page', 'excerpt', true );

        $this->registerMenus();
        $this->registerPostTypes();
    }

    /**
     *
     */
    public function addActions() {
        add_action( 'init', array( $this, '_initActionCallback' ) );
        add_action( 'after_setup_theme', array( $this, '_loadTextdomain' ), 10 );
    }

    /**
     *
     *
     * @since 1.4.0
     *
     * @param $slug
     * @param $title
     */
    public function addMenu( $slug, $title ) {
        $this->menuList[$slug] = $title;
    }

    /**
     * Register menus.
     *
     * @since 1.0.0
     * @return void
     */
    private function registerMenus() {
        register_nav_menus( $this->menuList );
    }

    /**
     * @since 1.4.2
     *
     * @param $postType
     * @param array $args
     */
    public function addPostType( $postType, array $args ) {
        $this->postTypeList[ $postType ] = $args;
    }

    /**
     * Register post types.
     * type 'page'.
     *
     * @since 1.0.0
     * @return void
     */
    private function registerPostTypes() {

        foreach( $this->postTypeList as $postType => $args ) {
            register_post_type( $postType, $args );
        }
    }

    /**
     *
     * @since 1.4.2
     */
    public function _loadTextdomain() {
        load_theme_textdomain( TextDomain, ThemeDir . '/languages' );
    }

    /**
     * Returns url to compentents of bambee
     *
     * @return mixed
     */
    public function getComponentUrl() {
        // fix for windows path
        $fixedAbsPath = str_replace( '\\', '/', ABSPATH );
        $fixedDirName = str_replace( '\\', '/', dirname( __FILE__ ) );
        // replace absolute path with url
        $componentUrl = str_replace( $fixedAbsPath, get_bloginfo( 'wpurl' ) . '/', $fixedDirName );

        return $componentUrl;
    }
}