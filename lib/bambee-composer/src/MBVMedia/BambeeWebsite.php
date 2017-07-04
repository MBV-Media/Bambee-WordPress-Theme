<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use MBVMedia\Lib\ThemeView;
use MBVMedia\ThemeCustomizer\ThemeCustommizerComments;

/**
 * The class representing the website (user frontend).
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
abstract class BambeeWebsite extends BambeeBase {

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

    private static $instance = null;

    /**
     * @since 1.0.0
     * @return void
     */
    protected function __construct() {

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
        add_action( 'init', array( $this, 'disableEmojis' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueLocalizeScripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyles' ) );
        add_action( 'wp_footer', array( $this, 'printGoogleAnalyticsCode' ) );
        add_action( 'wpcf7_before_send_mail', array( $this, 'addCF7DefaultRecipient' ) );

        if( get_theme_mod( 'bambee_comment_textbox_position' ) ) {
            add_filter( 'comment_form_fields', array( $this, 'moveCommentFieldToBottom' ) );
        }
    }

    /**
     *
     */
    public function addFilters() {
        add_filter( 'show_admin_bar', '__return_false' );
    }

    /**
     * Enqueue additional scripts
     */
    public function addScripts() {
        $this->addScript( 'comment-reply', false );
        $this->addScript(
            'vendor',
            ThemeUrl . '/js/vendor.min.js',
            array( 'jquery' ),
            false,
            true
        );
        $this->addScript(
            'main',
            ThemeUrl . '/js/main.min.js',
            array( 'jquery' ),
            false,
            true
        );
    }

    /**
     * Enqueue additional styles
     */
    public function addStyles() {
        $this->addStyle( 'main', ThemeUrl . '/css/main.min.css' );
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
     *
     */
    public function disableEmojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
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
        echo $this->getCommentList( $comment, $args, $depth );
    }

    /**
     * @since 1.4.2
     * @param $comment
     * @param $args
     * @param $depth
     * @return string
     */
    public function getCommentList( $comment, $args, $depth ) {
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
        return $commentListTemplate->render();
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
     * @since 1.4.2
     *
     * @param $cf7
     */
    public function addCF7DefaultRecipient( $cf7 ) {
        $mail = $cf7->prop( 'mail' );

        if( !empty( $mail['recipient'] ) ) {
            return;
        }

        $mail['recipient'] = get_bloginfo( 'admin_email' );
        $cf7->set_properties( array(
            'mail' => $mail,
        ) );
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function moveCommentFieldToBottom( $fields ) {
        $commentField = $fields['comment'];
        unset( $fields['comment'] );
        $fields['comment'] = $commentField;
        return $fields;
    }

    /**
     * Enqueue all added JS files.
     *
     * @since 1.4.2
     */
    public function enqueueScripts() {
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
    }

    /**
     * Enqueue all added localize JS files.
     *
     * @since 1.4.2
     */
    public function enqueueLocalizeScripts() {
        if ( !empty( $this->localizedScripts ) ) {
            foreach ( $this->localizedScripts as $localized_script ) {
                wp_localize_script(
                    $localized_script['handle'],
                    $localized_script['name'],
                    $localized_script['data']
                );
            }
        }
    }

    /**
     * Enqueue all added CSS files.
     *
     * @since 1.4.2
     */
    public function enqueueStyles() {
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
     * Prints the Google Analytics code if a tracking code is set.
     *
     * @since 1.4.2
     */
    public function printGoogleAnalyticsCode() {

        if( WP_DEBUG ) {
            return;
        }

        $googleTrackingId = get_option( 'bambee_google_analytics_tracking_id' );
        if ( ! empty( $googleTrackingId )) {
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
                ga('create', '<?php echo $googleTrackingId; ?>', 'auto');
                ga('send', 'pageview');
            </script>
            <?php
        }
    }

    /**
     * @param ThemeView $partial
     */
    public function mainLoop( ThemeView $partial ) {
        while ( have_posts() ) {
            the_post();
            echo $partial->render();
        }
    }

    /**
     * @param ThemeView $partial
     * @param array $queryArgs
     * @param ThemeView|null $noPosts
     */
    public function customLoop( ThemeView $partial, array $queryArgs = array(), ThemeView $noPosts = null ) {

        $theQuery = new \WP_Query( $queryArgs );

        if( $theQuery->have_posts() ) {

            $partial->setArg( 'theQuery', $theQuery );

            while( $theQuery->have_posts() ) {

                $theQuery->the_post();
                echo $partial->render();
            }
        }
        elseif( null !== $noPosts ) {
            $noPosts->setArg( 'theQuery', $theQuery );
            echo $noPosts->render();
        }

        wp_reset_postdata();
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