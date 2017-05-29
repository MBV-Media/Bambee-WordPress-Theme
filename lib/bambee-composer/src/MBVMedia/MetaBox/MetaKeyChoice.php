<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaKeyChoice extends MetaKey {

    private $choices;

    /**
     * MetaKeyTextfield constructor.
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $this->choices = array();

        $defaultTemplate = new ThemeView( 'partials/admin/meta-key-choice-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );
    }

    /**
     * @return array
     */
    public function getChoices() {
        return $this->choices;
    }

    /**
     * @param $value
     * @param $label
     */
    public function addChoice( $value, $label ) {
        $this->choices[] = array(
            'value' => $value,
            'label' => $label,
        );
    }

}