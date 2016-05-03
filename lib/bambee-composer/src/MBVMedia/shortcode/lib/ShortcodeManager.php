<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 28.04.2016
 * Time: 10:55
 */

namespace MBVMedia\Shortcode\Lib;


class ShortcodeManager {

    /**
     * @var array
     */
    private $shortcodeList;

    /**
     * ShortocdeManager constructor.
     */
    public function __construct() {
        $this->shortcodeList = array();
    }

    /**
     * @param array $locationInfo
     */
    public function loadShortcodes( array $locationInfo ) {

        $shortcodeDir = scandir( $locationInfo['path'] );

        foreach ( $shortcodeDir as $shortcodeFile ) {
            if ( !is_dir( $locationInfo['path'] . $shortcodeFile ) ) {

                $index = count( $this->shortcodeList );
                $class = $locationInfo['namespace'] . pathinfo( $shortcodeFile, PATHINFO_FILENAME );

                $this->shortcodeList[$index]['class'] = $class;
                $this->shortcodeList[$index]['file'] = $shortcodeFile;
                $this->shortcodeList[$index]['tag'] = $class::getUnqualifiedClassName( $class );
            }
        }
    }

    /**
     *
     */
    public function addShortcodes() {
        foreach ( $this->shortcodeList as $shortcode ) {
            $class = $shortcode['class'];
            if ( is_callable( array( $class, 'addShortcode' ) ) ) {
                $class::addShortcode();
            }
        }
    }

    /**
     *
     */
    public function extendTinyMCE() {
        add_action( 'admin_head', array( $this, 'printShortcodeData' ) );
        add_filter( 'mce_buttons', array( $this, 'tinyMceRegisterButton' ) );
        add_filter( 'mce_external_plugins', array( $this, 'tinyMceRegisterPlugin' ) );
    }

    /**
     *
     */
    public function printShortcodeData() {
        ?>
        <script type="text/javascript">
            window.bambeeShortcodeList = [
                <?php foreach($this->shortcodeList as $shortcode) : ?>
                <?php $shortcodeObject = new $shortcode['class'](); ?>
                {
                    text: '[<?php echo $shortcode['tag']; ?>]',
                    value: '<?php echo $shortcode['tag']; ?>',
                    atts: <?php echo json_encode( $shortcodeObject->getSupportedAtts() ); ?>
                },
                <?php endforeach; ?>
            ];
        </script><?php
    }

    /**
     * @param $buttons
     * @return mixed
     */
    public function tinyMceRegisterButton( $buttons ) {
        global $current_screen; //  WordPress contextual information about where we are.

        $type = $current_screen->post_type;

        if ( is_admin() && ( $type == 'post' || $type == 'page' ) ) {
            array_push( $buttons, 'separator', 'ShortcodeSelector' );
        }

        return $buttons;
    }

    /**
     * @param $pluginArray
     * @return mixed
     */
    public function tinyMceRegisterPlugin( $pluginArray ) {
        $pluginArray['ShortcodeSelector'] = get_template_directory_uri() . '/vendor/mbv-media/bambee-composer/src/MBVMedia/shortcode/lib/tinyMcePlugin.js';
        return $pluginArray;
    }
}