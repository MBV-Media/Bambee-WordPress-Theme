<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaKeyCheckbox extends MetaKey {

    /**
     * MetaKeyCheckbox constructor.
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new ThemeView( 'partials/admin/meta-key-checkbox-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );
    }
}