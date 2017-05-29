<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaKeyColorPicker extends MetaKey {

    /**
     * MetaKeyTextfield constructor.
     * @param $key
     * @param $label
     * @param string $inpitType
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new ThemeView( 'partials/admin/meta-key-color-picker-default.php' );
        $this->setTemplate( $defaultTemplate );

        add_action( 'admin_enqueue_scripts', array( $this, 'actionAdminEnqueueScripts' ) );

        parent::__construct( $key, $label, $type );
    }

    /**
     *
     */
    public function actionAdminEnqueueScripts() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
    }
}