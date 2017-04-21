<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use MBVMedia\ControlledTemplate\CookieControlledTemplate;
use MBVMedia\Lib\ThemeView;
use MBVMedia\Shortcode\Lib\ShortcodeManager;
use MBVMedia\ThemeCustomizer\Control;
use MBVMedia\ThemeCustomizer\Panel;
use MBVMedia\ThemeCustomizer\Section;
use MBVMedia\ThemeCustomizer\Setting;
use MBVMedia\ThemeCustomizer\ThemeCustommizer;


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

    private static $instance = null;

    /**
     * @since 1.0.0
     * @return void
     */
    protected function __construct() {

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


        if( get_theme_mod( 'bambee_dynamic_front_page_show', true ) ) {
            $interval = get_theme_mod( 'bambee_dynamic_front_page_interval', '24:00:00' );
            $interval = empty( $interval ) ? '24:00:00' : $interval;
            $interval = strtotime( $interval ) - strtotime( 'TODAY' );

            $entranceOverlay = new CookieControlledTemplate(
                new ThemeView( 'partials/overlay-entrance.php' ),
                'enter',
                '.overlay-entry .js-enter',
                '.overlay-entry',
                $interval
            );
            $entranceOverlay->addActions();
        }

        $cookieNotice = new CookieControlledTemplate(
            new ThemeView( 'partials/cookie-notice.php' ),
            'cookie',
            '.cookie-notice .js-hide',
            '.cookie-notice'
        );
        $cookieNotice->addActions();

        $this->addPostTypeGallery();

        $this->themeCustomizer = new ThemeCustommizer();
        $this->initThemeSettingsDynamicFrontPage();
        $this->initThemeSettingsComments();
        $this->initThemeSettingsCoreData();
        $this->initThemeSettingsGoogle();
        $this->themeCustomizer->register();
    }

    /**
     *
     */
    public function addActions() {
        add_action( 'init', array( $this, 'actionInit' ) );
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
    public function actionInit() {
        $this->registerPostTypes();
    }

    /**
     *
     */
    public function actionAfterSetupTheme() {
        $this->addThemeSupportPostThumbnails();
        $this->addThemeSupportCustomLogo();
        $this->addThemeSupportCustomHeader();
        $this->addPostTypeSupportExcerpt();
        $this->registerMenus();
    }

    /**
     *
     */
    private function addPostTypeGallery() {
        $componentUrl = $this->getComponentUrl();
        $this->addPostType( 'gallery', array(
            'labels' => array(
                'name' => __( 'Galleries', TextDomain ),
                'singular_name' => __( 'Gallery', TextDomain ),
            ),
            'taxonomies' => array( 'category' ),
            'menu_icon' => $componentUrl . '/assets/img/icons/gallery.png',
            'public' => true,
            'has_archive' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => true,
            'supports' => array(
                'title',
                'author',
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
     *
     */
    public function initThemeSettingsDynamicFrontPage() {
        $settingDynamicFrontpageShow = new Setting( 'bambee_dynamic_front_page_show', array(
            'default' => true,
        ) );

        $controlDynamicFrontpageShow = new Control( 'bambee_dynamic_front_page_show_control', array(
            'label' => __( 'Show frontpage-overlay', TextDomain ),
            'type' => 'checkbox',
        ), $settingDynamicFrontpageShow );

        $settingDynamicFrontpageInterval = new Setting( 'bambee_dynamic_front_page_interval', array(
            'default' => '',
        ) );

        $controlDynamicFrontpageInterval = new Control( 'bambee_dynamic_front_page_interval_control', array(
            'label' => __( 'Anzeige Intervall', TextDomain ),
            'description' => __( 'Zeit nach der Das Overlay erneut angezeigt wird. (Standard: 24:00:00)', TextDomain ),
            'type' => 'text',
            'input_attrs' => array(
                'placeholder' => 'hh:mm:ss',
            ),
        ), $settingDynamicFrontpageInterval );

        $sectionDynamicFrontpage = new Section( 'bambee_dynamic_front_page', array(
            'title' => __( 'Dynamic frontpage', TextDomain ),
            'priority' => 120,
        ) );
        $sectionDynamicFrontpage->addControl( $controlDynamicFrontpageShow );
        $sectionDynamicFrontpage->addControl( $controlDynamicFrontpageInterval );

        $this->themeCustomizer->addSection( $sectionDynamicFrontpage );
    }

    /**
     *
     */
    public function initThemeSettingsComments() {
        $settingCommentTextboxPosition = new Setting( 'bambee_comment_textbox_position', array(
            'default' => false,
        ) );

        $controlCommentTextbox = new Control( 'bambee_comment_textbox', array(
            'label' => __( 'Move form textfield to the bottom', TextDomain ),
            'type' => 'checkbox',
        ), $settingCommentTextboxPosition );

        $sectionComment = new Section( 'bambee_comment', array(
            'title' => __( 'Comments' ),
            'priority' => 80,
        ) );
        $sectionComment->addControl( $controlCommentTextbox );

        $this->themeCustomizer->addSection( $sectionComment );
    }

    /**
     *
     */
    public function initThemeSettingsCoreData() {
        $settingCoreDataAddress = new Setting( 'bambee_core_data_address', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlCoreDataAddress = new Control( 'bambee_core_data_address_control', array(
            'label' => __( 'Address', TextDomain ),
            'type' => 'textarea',
        ), $settingCoreDataAddress );

        $settingEmail = new Setting( 'bambee_core_data_email', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlCoreDataEmail = new Control( 'bambee_core_data_email_control', array(
            'label' => __( 'E-Mail address', TextDomain ),
            'type' => 'text',
        ), $settingEmail );

        $settingCoreDataPhone = new Setting( 'bambee_core_data_phone', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlCoreDataPhone = new Control( 'bambee_core_data_phone_control', array(
            'label' => __( 'Phone', TextDomain ),
            'type' => 'text',
        ), $settingCoreDataPhone );

        $sectionCoreData = new Section( 'bambee_core_data_section', array(
            'title' => __( 'Core data', TextDomain ),
            'priority' => 700,
            'description' => __(
                'You can use the [coredata]key[coredata]' .
                ' shortcode to display the core data field inside a post.',
                TextDomain
            )
        ) );
        $sectionCoreData->addControl( $controlCoreDataAddress );
        $sectionCoreData->addControl( $controlCoreDataEmail );
        $sectionCoreData->addControl( $controlCoreDataPhone );

        $this->themeCustomizer->addSection( $sectionCoreData );
    }

    /**
     *
     */
    public function initThemeSettingsGoogle() {
        $settingGoogleMapsLatitude = new Setting( 'bambee_google_maps_latitude', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlGoogleMapsLatitude = new Control( 'bambee_google_maps_latitude_control', array(
            'label' => __( 'Latitude', TextDomain ),
            'type' => 'text',
        ), $settingGoogleMapsLatitude );

        $settingGoogleMapsLongitude = new Setting( 'bambee_google_maps_longitude', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlGoogleMapsLongitude = new Control( 'bambee_google_maps_longitude_control', array(
            'label' => __( 'Longitude', TextDomain ),
            'type' => 'text',
        ), $settingGoogleMapsLongitude );

        $settingGoogleMapsZoom = new Setting( 'bambee_google_maps_zoom', array(
            'type' => 'option',
            'default' => 15,
        ) );

        $controlGoogleMapsZoom = new Control( 'bambee_google_maps_zoom_control', array(
            'label' => __( 'Zoom', TextDomain ),
            'type' => 'number',
            'input_attrs' => array(
                'min' => 0,
            ),
        ), $settingGoogleMapsZoom );

        $settingGoogleMapsApiKey = new Setting( 'bambee_google_maps_api_key', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlGoogleMapsApiKey = new Control( 'bambee_google_maps_api_key_control', array(
            'label' => __( 'API-Key', TextDomain ),
            'type' => 'text',
        ), $settingGoogleMapsApiKey );

        $settingGoogleMapsStyles = new Setting( 'bambee_google_maps_styles', array(
            'type' => 'option',
            'default' => '',
        ) );

        $controlGoogleMapsStyles = new Control( 'bambee_google_maps_styles_control', array(
            'label' => __( 'Styles', TextDomain ),
            'description' => sprintf( __( 'Das erstellte %sMap-Style%s JSON in das Textfeld kopieren.', TextDomain ), '<a href="https://mapstyle.withgoogle.com/" target="_blank">', '</a>' ),
            'type' => 'textarea',
        ), $settingGoogleMapsStyles );

        $sectionGoogleMaps = new Section( 'bambee_google_maps_section', array(
            'title' => __( 'Maps', TextDomain ),
        ) );
        $sectionGoogleMaps->addControl( $controlGoogleMapsLatitude );
        $sectionGoogleMaps->addControl( $controlGoogleMapsLongitude );
        $sectionGoogleMaps->addControl( $controlGoogleMapsZoom );
        $sectionGoogleMaps->addControl( $controlGoogleMapsApiKey );
        $sectionGoogleMaps->addControl( $controlGoogleMapsStyles );

        $settingGoogleAnalyticsTracktingId = new Setting( 'bambee_google_analytics_tracking_id', array(
            'type' => 'option',
        ) );

        $controlGoogleAnalyticsTracktingId = new Control( 'bambee_google_analytics_tracking_id_control', array(
            'label' => __( 'Trackting-ID', TextDomain ),
            'type' => 'text',
            'input_attrs' => array(
                'placeholder' => 'UA-XXXXX-X',
            ),
        ), $settingGoogleAnalyticsTracktingId );

        $sectionGoogleAnalytics = new Section( 'bambee_google_analytics_section', array(
            'title' => __( 'Analytics', TextDomain ),
            'description' => __( 'Nach Eingabe der Tracking-ID wird das Google Analytics Script automatisch eingebunden.', TextDomain ),
        ) );
        $sectionGoogleAnalytics->addControl( $controlGoogleAnalyticsTracktingId );

        $panelGoogle = new Panel( 'bambee_google_panel', array(
            'priority'       => 800,
            'title'          => __( 'Google', TextDomain ),
            'description'    => '',
        ) );
        $panelGoogle->addSection( $sectionGoogleMaps );
        $panelGoogle->addSection( $sectionGoogleAnalytics );

        $this->themeCustomizer->addPanel( $panelGoogle );
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

    /**
     * @return static
     */
    public static function self() {
        if( null === self::$instance ) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}