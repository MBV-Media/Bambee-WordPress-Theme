<?php
/** * @since 2.0.3
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ControlledTemplate;


use MBVMedia\Lib\ThemeView;

abstract class ControlledTemplate {

    /**
     * @var ThemeView
     */
    private $template;

    /**
     * @var string
     */
    private $nonce;

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
     * @param $nonce string
     * @param $selectorOnClick string
     * @param $selectorContainer string
     */
    public function __construct( ThemeView $template, $nonce, $selectorOnClick, $selectorContainer ) {
        $this->template = $template;
        $this->nonce = $nonce;
        $this->selectorOnClick = $selectorOnClick;
        $this->selectorContainer = $selectorContainer;
    }

    /**
     *
     */
    public function addActions() {
        global $wp_actions, $wp_filter;
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
        add_action( 'init', array( $this, 'checkForNonce' ) );
        add_action( 'wp_footer', array( $this, 'renderTemplate' ) );
        add_action( 'wp_footer', array( $this, 'printScript' ) );
    }

    /**
     *
     */
    private function addAdminActions() {
        add_action( 'wp_ajax_' . $this->nonce, array( $this, 'ajaxCallback' ) );
        add_action( 'wp_ajax_nopriv_' . $this->nonce , array( $this, 'ajaxCallback' ) );
    }

    /**
     *
     */
    public function ajaxCallback() {
        $nonce = filter_input( INPUT_POST, 'nonce' );

        if ( !defined( 'DOING_AJAX' ) || !DOING_AJAX || !wp_verify_nonce( $nonce, $this->nonce ) ) {
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
    public function checkForNonce() {
        $nonce = filter_input( INPUT_GET, $this->nonce );

        if( $nonce === null ) {
            return;
        }

        $this->hide();
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
                            action: '<?php echo $this->nonce; ?>',
                            nonce: '<?php echo wp_create_nonce( $this->nonce ); ?>'
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
    public abstract function hide();

    /**
     * @return bool
     */
    public abstract function hidden();
}