<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use Detection\MobileDetect;
use Lib\CustomBambee;
use MagicAdminPage\MagicAdminPage;
use MBVMedia\Shortcode\Lib\ShortcodeManager;
use MBVMedia\ThemeView;

/**
 * The class representing the website (user frontend).
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class BambeeWebsite {

    /**
     * @var Bambee
     */
    private $bambee;

    /**
     * @since 1.0.0
     * @var array
     */
    private $coreData;

    /**
     * @since 1.0.0
     * @var array
     */
    private $globalData;

    /**
     * @since 1.0.0
     * @var array
     */
    private $scripts;

    /**
     * @since 1.0.0
     * @var array
     */
    private $localizedScripts;

    /**
     * @since 1.0.0
     * @var array
     */
    private $styles;

    /**
     * @since 1.1.0
     * @var string
     */
    private $commentPaginationNextText;

    /**
     * @since 1.1.0
     * @var string
     */
    private $commentPaginationPrevText;

    /**
     * @since 1.1.0
     * @var string
     */
    private $commentPaginationPageTemplate;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct( Bambee $bambee ) {

        $this->bambee = $bambee;

        $this->coreData = MagicAdminPage::getOption( 'core-data' );
        $this->globalData = MagicAdminPage::getOption( 'global-data' );

        $this->scripts = array();
        $this->localizedScripts = array();
        $this->styles = array();

        $this->commentPaginationNextText = __( 'Next &raquo;', TextDomain );
        $this->commentPaginationPrevText = __( '&laquo; Prev', TextDomain );
        $this->commentPaginationPageTemplate = '<li>%s</li>';

        # Grunt livereload (development only)
        if ( WP_DEBUG ) {
            $this->addScript( 'livereload', '//localhost:35729/livereload.js' );
        }
    }

    /**
     * @since 1.4.2
     *
     * @return Bambee
     */
    public function getBambee() {
        return $this->bambee;
    }

    /**
     * @since 1.4.0
     *
     * @param $key
     * @return null|string
     */
    public function getCoreData( $key ) {
        return isset( $this->coreData[$key] ) ? $this->coreData[$key] : null;
    }

    /**
     * @since 1.4.0
     *
     * @param $key
     * @return null|string
     */
    public function getGlobalData( $key ) {
        return isset( $this->globalData[$key] ) ? $this->globalData[$key] : null;
    }

    /**
     * @since 1.4.0
     *
     * @return string
     */
    public function getCommentPaginationNextText() {
        return $this->commentPaginationNextText;
    }

    /**
     * @since 1.4.0
     *
     * @param string $commentPaginationNextText
     */
    public function setCommentPaginationNextText( $commentPaginationNextText ) {
        $this->commentPaginationNextText = $commentPaginationNextText;
    }

    /**
     * @since 1.4.0
     *
     * @return string
     */
    public function getCommentPaginationPrevText() {
        return $this->commentPaginationPrevText;
    }

    /**
     * @since 1.4.0
     *
     * @param string $commentPaginationPrevText
     */
    public function setCommentPaginationPrevText( $commentPaginationPrevText ) {
        $this->commentPaginationPrevText = $commentPaginationPrevText;
    }

    /**
     * @since 1.4.0
     *
     * @return string
     */
    public function getCommentPaginationPageTemplate() {
        return $this->commentPaginationPageTemplate;
    }

    /**
     * @since 1.4.0
     *
     * @param string $commentPaginationPageTemplate
     */
    public function setCommentPaginationPageTemplate( $commentPaginationPageTemplate ) {
        $this->commentPaginationPageTemplate = $commentPaginationPageTemplate;
    }

    /**
     *
     */
    public function addActions() {
        add_action( 'wp_enqueue_scripts', array( $this, '_enqueueScripts' ) );
        add_action( 'wp_footer', array( $this, '_wpFooter' ) );
    }

    /**
     *
     */
    public function addFilter() {
        add_filter( 'show_admin_bar', '__return_false' );
    }

    /**
     * @since 1.4.0
     *
     * @param $handle
     * @param $src
     * @param array $deps
     * @param bool $ver
     * @param bool $inFooter
     */
    public function addScript( $handle, $src, $deps = array(), $ver = false, $inFooter = false ) {
        $this->scripts[] = array(
                'handle' => $handle,
                'src' => $src,
                'deps' => $deps,
                'ver' => $ver,
                'in_footer' => $inFooter
        );
    }

    /**
     * @since 1.4.0
     *
     * @param $handle
     * @param $name
     * @param array $data
     */
    public function addLocalizedScript( $handle, $name, array $data ) {
        $this->localizedScripts[] = array(
                'handle' => $handle,
                'name' => $name,
                'data' => $data
        );
    }

    /**
     * @since 1.4.0
     *
     * @param $handle
     * @param $src
     * @param array $deps
     * @param bool $ver
     * @param string $media
     */
    public function addStyle( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
        $this->styles[] = array(
                'handle' => $handle,
                'src' => $src,
                'deps' => $deps,
                'ver' => $ver,
                'media' => $media
        );
    }

    /**
     * Enqueue the CSS and JS.
     * Additional CSS and JS can be loaded via the class properties
     * 'styles' and 'scripts' in the child class constructor.
     * JS files can be localized via the class property 'localizedScripts'
     * in the child class constructor.
     *
     * @since 1.0.0
     * @return void
     *
     * @example
     *  Usage:
     *    $this->styles = array(
     *      'handle' => 'style-css',
     *      'src' => 'url/to/style.css'
     *    );
     *    $this->scripts = array(
     *      'handle' => 'script-js',
     *      'src' => 'url/to/script.js',
     *      'deps' => array( 'jquery' )
     *    );
     *    $this->localizedScripts = array(
     *      'handle' => 'script-js',
     *      'name' => 'localized',
     *      'data' => array(
     *          'alertText' => __( 'An error occurred!' )
     *      )
     *    );
     *    parent::__construct();
     */
    public function _enqueueScripts() {
        # Additional scripts
        if ( !empty( $this->scripts ) ) {
            foreach ( $this->scripts as $script ) {
                wp_enqueue_script(
                    $script['handle'],
                    $script['src'],
                    $script['deps'],
                    $script['ver'],
                    $script['in_footer']
                );
            }
        }

        # Localize scripts
        if ( !empty( $this->localizedScripts ) ) {
            foreach ( $this->localizedScripts as $localized_script ) {
                wp_localize_script(
                    $localized_script['handle'],
                    $localized_script['name'],
                    $localized_script['data']
                );
            }
        }

        # Additional styles
        if ( !empty( $this->styles ) ) {
            foreach ( $this->styles as $style ) {
                wp_enqueue_style(
                    $style['handle'],
                    $style['src'],
                    $style['deps'],
                    $style['ver'],
                    $style['media']
                );
            }
        }
    }

    /**
     * Customize the comment list.
     *
     * @since 1.0.0
     * @param string $comment
     * @param array $args
     * @param int $depth
     * @return void
     */
    public function commentList( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;

        $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
        $addBelow = 'comment';

        $commentListTemplate = new ThemeView( '/partials/comment-list.php', array(
            'comment' => $comment,
            'arguments' => $args,
            'depth' => $depth,
            'tag' => $tag,
            'addBelow' => $addBelow,
        ) );
        echo $commentListTemplate->render();
    }

    /**
     * Display comments pagination.
     *
     * @since 1.1.0
     * @return void
     */
    public function commentPagination() {
        echo $this->getCommentPagination();
    }

    /**
     * @since 1.4.2
     * @return string
     */
    public function getCommentPagination() {
        $pagination = paginate_comments_links( array(
            'echo' => false,
            'mid_size' => 2,
            'end_size' => 3,
            'type' => 'array',
            'add_fragment' => '',
            'next_text' => $this->commentPaginationNextText,
            'prev_text' => $this->commentPaginationPrevText,
        ) );

        $paginationPages = '';
        $paginationPrev = '';
        $paginationNext = '';

        if ( !empty( $pagination ) ) {
            $count = 0;

            foreach ( $pagination as $pageData ) {
                if ( is_numeric( strip_tags( $pageData ) )
                    || strip_tags( $pageData ) === '&hellip;'
                ) {
                    $paginationPages .= sprintf( $this->commentPaginationPageTemplate, $pageData );
                } else {
                    if ( $count > 0 ) {
                        $paginationNext = $pageData;
                    } else {
                        $paginationPrev = $pageData;
                    }
                }

                ++$count;
            }
        }

        $template = new ThemeView( '/partials/comment-pagination.php', array(
            'paginationPrev' => $paginationPrev,
            'paginationPages' => $paginationPages,
            'paginationNext' => $paginationNext,
        ) );
        return $template->render();
    }

    /**
     *
     */
    public function _wpFooter() {
        $googleTrackingCode = $this->getCoreData( 'googleTrackingCode' );
        if ( $googleTrackingCode !== 'UA-XXXXX-X' ) {
            ?>
            <script>
                (function (b, o, i, l, e, r) {
                    b.GoogleAnalyticsObject = l;
                    b[l] || (b[l] =
                        function () {
                            (b[l].q = b[l].q || []).push(arguments)
                        });
                    b[l].l = +new Date;
                    e = o.createElement(i);
                    r = o.getElementsByTagName(i)[0];
                    e.src = 'https://www.google-analytics.com/analytics.js';
                    r.parentNode.insertBefore(e, r)
                }(window, document, 'script', 'ga'));
                ga('create', '<?php echo $googleTrackingCode; ?>', 'auto');
                ga('send', 'pageview');
            </script>
            <?php
        }
    }
}