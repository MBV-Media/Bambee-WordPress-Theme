<?php

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
    public function loadShortcodes( $path, $namespace ) {

        $shortcodeDir = scandir( $path );

        foreach ( $shortcodeDir as $shortcodeFile ) {
            if ( !is_dir( $path . $shortcodeFile ) ) {

                $class = $namespace . pathinfo( $shortcodeFile, PATHINFO_FILENAME );

                $this->shortcodeList[] = array(
                        'class' => $class,
                        'file' => $shortcodeFile,
                        'tag' => $class::getShortcodeAlias()
                );
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
                    tag: '<?php echo $shortcode['tag']; ?>',
                    atts: <?php echo json_encode( $shortcodeObject->getSupportedAtts() ); ?>,
                    descr: '<?php echo $shortcodeObject->getDescription(); ?>'
                },
                <?php endforeach; ?>
            ];
        </script>
        <?php
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