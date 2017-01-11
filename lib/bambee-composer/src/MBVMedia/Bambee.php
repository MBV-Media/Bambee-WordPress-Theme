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
abstract class Bambee extends BambeeBase {

    /**
     * @since 1.0.0
     * @var array
     */
    private $postThumbnail;

    /**
     * @var array
     */
    private $customLogo;

    /**
     * @var array
     */
    private $customHeader;

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

        $this->loadThemeTextdomain();

        $this->postThumbnail = array(
            'width' => 624,
            'height' => 999,
            'crop' => false,
        );

        $this->customLogo = array(
            'width' => 300,
            'height' => 200,
            'flex-width' => true,
            'flex-height' => true,
        );

        $this->customHeader = array(
            'width' => 1200,
            'height' => 450,
            'flex-width' => true,
            'flex-height' => true,
        );

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

        $entranceOverlay = new SessionControlledTemplate(
            'partials/overlay-entrance.php',
            'enter',
            '.overlay-entry .js-enter',
            '.overlay-entry'
        );
        $entranceOverlay->addActions();

        $cookieNotice = new SessionControlledTemplate(
            'partials/cookie-notice.php',
            'cookie',
            '.cookie-notice .js-hide',
            '.cookie-notice'
        );
        $cookieNotice->addActions();
    }

    /**
     *
     */
    public function addActions() {
        add_action( 'after_setup_theme', array( $this, 'actionAfterSetupTheme' ) );
    }

    /**
     *
     */
    public function addFilters() {
        // TODO: Implement addFilters() method.
    }

    /**
     *
     */
    public function actionAfterSetupTheme() {
        $this->initCustomPostTypes();
        $this->addThemeSupportPostThumbnails();
        $this->addThemeSupportCustomLogo();
        $this->addThemeSupportCustomHeader();
        $this->addPostTypeSupportExcerpt();
        $this->registerMenus();
        $this->registerPostTypes();
    }

    /**
     *
     */
    private function initCustomPostTypes() {
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
        $this->postThumbnail['width'] = $postThumbnailWidth;
    }

    /**
     * @since 1.4.2
     *
     * @param int $postThumbnailHeight
     */
    public function setPostThumbnailHeight( $postThumbnailHeight ) {
        $this->postThumbnail['height'] = $postThumbnailHeight;
    }

    /**
     * @since 1.4.2
     *
     * @param boolean $postThumbnailCrop
     */
    public function setPostThumbnailCrop( $postThumbnailCrop ) {
        $this->postThumbnail['crop'] = $postThumbnailCrop;
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
    public function registerMenus() {
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
    public function registerPostTypes() {

        foreach( $this->postTypeList as $postType => $args ) {
            register_post_type( $postType, $args );
        }
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


    /**
     * Action-hook callbacks
     */

    /**
     *
     */
    public function loadThemeTextdomain() {
        $path = ThemeDir . '/languages';
        load_theme_textdomain( TextDomain, $path );
    }

    /**
     *
     */
    public function addThemeSupportPostThumbnails() {
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size(
            $this->postThumbnail['width'],
            $this->postThumbnail['height'],
            $this->postThumbnail['crop']
        );
    }

    /**
     *
     */
    public function addThemeSupportCustomLogo() {
        add_theme_support( 'custom-logo', $this->customLogo );
    }

    /**
     *
     */
    public function addThemeSupportCustomHeader() {
        add_theme_support( 'custom-header', $this->customHeader );
    }

    /**
     *
     */
    public function addPostTypeSupportExcerpt() {
        add_post_type_support( 'page', 'excerpt', true );
    }
}