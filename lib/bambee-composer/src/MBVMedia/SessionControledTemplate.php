<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia;


class SessionControlledTemplate {

    /**
     * @var ThemeView
     */
    private $template;

    /**
     * @var string
     */
    private $sessionVar;

    /**
     * @var string
     */
    private $selectorOnClick;

    /**
     * @var string
     */
    private $selectorContainer;

    /**
     * SessionControledTemplate constructor.
     * @param $template string
     * @param $sessionVar string
     * @param $selectorOnClick string
     * @param $selectorContainer string
     */
    public function __construct( $template, $sessionVar, $selectorOnClick, $selectorContainer) {
        $this->template = new ThemeView( $template );
        $this->sessionVar = $sessionVar;
        $this->selectorOnClick = $selectorOnClick;
        $this->selectorContainer = $selectorContainer;
    }

    /**
     *
     */
    public function addActions() {

        add_action( 'init', array( 'MBVMedia\Session', 'start' ) );

        if( is_admin() ) {
            $this->addAdminActions();
        }
        else {
            $this->addWebsiteActions();
        }
    }

    /**
     *
     */
    private function addWebsiteActions() {
        add_action( 'wp_footer', array( $this, 'renderTemplate' ) );
        add_action( 'wp_footer', array( $this, 'printScript' ) );
    }

    /**
     *
     */
    private function addAdminActions() {
        add_action( 'wp_ajax_enter', array( $this, 'ajaxCallback' ) );
        add_action( 'wp_ajax_nopriv_' . $this->sessionVar , array( $this, 'ajaxCallback' ) );
    }

    /**
     *
     */
    public function ajaxCallback() {
        $nonce = filter_input( INPUT_POST, 'nonce' );

        if ( !defined( 'DOING_AJAX' ) || !DOING_AJAX || !wp_verify_nonce( $nonce, $this->sessionVar ) ) {
            return;
        }

        $this->hide();

        die();
    }

    /**
     *
     */
    public function renderTemplate() {
        if( $this->hidden() ) {
            return;
        }

        echo $this->template->render();
    }

    /**
     *
     */
    public function printScript() {
        if( $this->hidden() ) {
            return;
        }

        ?>
        <script type="text/javascript">
            (function($) {
                $('<?php echo $this->selectorOnClick; ?>').on('click', function(e) {
                    e.preventDefault();
                    $('<?php echo $this->selectorContainer; ?>').addClass('hidden');
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        data: {
                            action: '<?php echo $this->sessionVar; ?>',
                            nonce: '<?php echo wp_create_nonce( $this->sessionVar ); ?>'
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
    private function hide() {
        Session::setVar( $this->sessionVar, true );
    }

    /**
     * @return bool
     */
    private function hidden() {
        return Session::getVar( $this->sessionVar ) === true;
    }
}