<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;


use Detection\MobileDetect;
use MagicAdminPage\MagicAdminPage;
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
     * @since 1.0.0
     * @var array
     */
    public $coreData = array();

    /**
     * @since 1.0.0
     * @var array
     */
    public $globalData = array();

    /**
     * @since 1.0.0
     * @var null|MobileDetect
     */
    public $mobileDetect = null;

    /**
     * @since 1.0.0
     * @var array
     */
    protected $scripts = array();

    /**
     * @since 1.0.0
     * @var array
     */
    protected $localizedScripts = array();

    /**
     * @since 1.0.0
     * @var array
     */
    protected $styles = array();

    /**
     * @since 1.1.0
     * @var string
     */
    protected $commentPaginationNextText = '';

    /**
     * @since 1.1.0
     * @var string
     */
    protected $commentPaginationPrevText = '';

    /**
     * @since 1.1.0
     * @var string
     */
    protected $commentPaginationPageTemplate = '<li>%s</li>';

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        $this->coreData = MagicAdminPage::getOption( 'core-data' );
        $this->globalData = MagicAdminPage::getOption( 'global-data' );
        $this->mobileDetect = new MobileDetect();
        if ( empty( $this->commentPaginationNextText ) ) {
            $this->commentPaginationNextText = __( 'Next &raquo;', TextDomain );
        }
        if ( empty( $this->commentPaginationPrevText ) ) {
            $this->commentPaginationPrevText = __( '&laquo; Prev', TextDomain );
        }

        add_shortcode( 'page-link', array( $this, 'shortcodeGetLink' ) );
        add_shortcode( 'coredata', array( $this, 'shortcodeCoredata' ) );
        add_shortcode( 'globaldata', array( $this, 'shortcodeGlobaldata' ) );

        add_shortcode( 'col', array( $this, 'shortcodeColumn' ) );
        add_shortcode( 'row', array( $this, 'shortcodeRow' ) );

        add_filter( 'show_admin_bar', '__return_false' );

        add_action( 'wp_enqueue_scripts', array( $this, '_enqueueScripts' ) );
    }

    /**
     * Get permalink by id.
     *
     * @since 1.0.0
     * @param array $args
     * @return mixed
     *
     * @example
     *  Usage:
     *    [page-link id=42]
     */
    public function shortcodeGetLink( $args ) {
        $id = $args['id'];
        return get_permalink( $id );
    }

    /**
     * Load a core_data field.
     *
     * @since 1.0.0
     * @param array $args
     * @param string $content
     * @return string
     *
     * @example
     *  Usage:
     *    [coredata]street[/coredata]
     */
    public function shortcodeCoredata( $args, $content = '' ) {
        if ( empty( $content ) || empty( $this->coreData[$content] ) ) {
            return '';
        }
        return nl2br( $this->coreData[$content] );
    }

    /**
     * Load a global_data field.
     *
     * @since 1.0.0
     * @param array $args
     * @param string $content
     * @return string
     *
     * @example
     *  Usage:
     *    [globaldata]key[/globaldata]
     */
    public function shortcodeGlobaldata( $args, $content = '' ) {
        if ( empty( $content ) || empty( $this->globalData[$content] ) ) {
            return '';
        }

        return $this->globalData[$content];
    }

    /**
     * Generate a foundation grid row.
     *
     * @since 1.0.0
     * @param array $args
     * @param string $content
     * @return string
     *
     * @example
     *  Usage:
     *    [row]Hello World![/row]
     */
    public function shortcodeRow( $args, $content = '' ) {
        $class = $args['class'] ? $args['class'] : '';
        $content = sprintf(
            '<div class="row %s">%s</div>',
            $content,
            $class
        );
        return apply_filters( 'the_content', $content );
    }

    /**
     * Generate a foundation grid column.
     *
     * @since 1.0.0
     * @param array $args
     * @param string $content
     * @return string
     *
     * @example
     *  Usage:
     *    [col small=12 medium=6 large=4]Hello World![/col]
     */
    public function shortcodeColumn( $args, $content = '' ) {
        $class = 'column ';

        if ( !empty( $args['small'] ) ) {
            $class .= ' small-' . $args['small'];
        }
        if ( !empty( $args['medium'] ) ) {
            $class .= ' medium-' . $args['medium'];
        }
        if ( !empty( $args['large'] ) ) {
            $class .= ' large-' . $args['large'];
        }

        if ( !empty( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        $content = sprintf(
            '<div class="%s">%s</div>',
            $class,
            $content
        );

        return apply_filters( 'the_content', $content );
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
        wp_enqueue_script( 'jquery' );

        # Grunt livereload (development only)
        if ( WP_DEBUG ) {
            wp_enqueue_script( 'livereload', '//localhost:35729/livereload.js' );
        }

        # Additional scripts
        if ( !empty( $this->scripts ) ) {
            foreach ( $this->scripts as $script ) {
                wp_enqueue_script(
                    $script['handle'],
                    isset( $script['src'] ) ? $script['src'] : '',
                    isset( $script['deps'] ) ? $script['deps'] : '',
                    isset( $script['ver'] ) ? $script['ver'] : '',
                    isset( $script['in_footer'] ) ? $script['in_footer'] : ''
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
                    isset( $style['deps'] ) ? $style['deps'] : '',
                    isset( $style['ver'] ) ? $style['ver'] : '',
                    isset( $style['media'] ) ? $style['media'] : 'all'
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
        extract( $args, EXTR_SKIP );

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $addBelow = 'comment';
        } else {
            $tag = 'li';
            $addBelow = 'div-comment';
        }

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
        echo $template->render();
    }

    /**
     * Display comments pagination.
     *
     * @deprecated since 1.1.0
     * @since 1.0.0
     * @return void
     */
    public function commentPages() {
        return $this->commentPagination();
    }
}