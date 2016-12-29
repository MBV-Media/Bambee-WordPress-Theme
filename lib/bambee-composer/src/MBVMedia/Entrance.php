<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia;


class Entrance {

    /**
     * @var int
     */
    private $sessionLifetime;

    /**
     * Entrance constructor.
     */
    public function __construct() {
        $this->sessionLifetime = 86400; // one day

        if( filter_input( INPUT_GET, 'enter' ) !== null ) {
            $this->enter();
        }
    }

    /**
     *
     */
    public function addWebsiteActions() {
        add_action( 'init', array( $this, 'startSession' ) );
        add_action( 'wp_footer', array( $this, 'renderTemplate' ) );
        add_action( 'wp_footer', array( $this, 'printScript' ) );
    }

    /**
     *
     */
    public function addAdminActions() {
        add_action( 'wp_ajax_enter', array( $this, 'ajaxCallback' ) );
        add_action( 'wp_ajax_nopriv_enter', array( $this, 'ajaxCallback' ) );
    }

    /**
     *
     */
    public function startSession() {
        if( session_status() == PHP_SESSION_NONE ) {
            session_set_cookie_params( $this->sessionLifetime );
            session_start();
        }
    }

    /**
     *
     */
    public function ajaxCallback() {
        $nonce = filter_input( INPUT_POST, 'nonce' );

        if ( !defined( 'DOING_AJAX' ) || !DOING_AJAX || !wp_verify_nonce( $nonce, 'enter' ) ) {
            return;
        }

        $this->enter();

        die();
    }

    /**
     *
     */
    public function renderTemplate() {
        if( $this->entered() ) {
            return;
        }

        get_template_part( 'partials/overlay', 'entrance' );
    }

    /**
     *
     */
    public function printScript() {
        if( $this->entered() ) {
            return;
        }

        ?>
        <script type="text/javascript">
            (function($) {
                $('.overlay-entry .js-enter').on('click', function(e) {
                    e.preventDefault();
                    $('.overlay-entry').addClass('entered');
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        data: {
                            action: 'enter',
                            nonce: '<?php echo wp_create_nonce( 'enter' ); ?>'
                        }
                    });
                    return false;
                });
            })(jQuery);
        </script>
        <?php
    }

    /**
     *
     */
    private function enter() {
        $this->startSession();
        $_SESSION['entrance'] = true;
    }

    /**
     * @return bool
     */
    private function entered() {
        return isset( $_SESSION['entrance'] ) && $_SESSION['entrance'];
    }
}