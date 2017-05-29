<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaKeyTaxonomy extends MetaKey {

    /**
     * @var array
     */
    private $taxonomy;

    /**
     * @var ThemeView
     */
    private $termTemplate;

    /**
     * MetaKeyCheckbox constructor.
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $this->taxonomy = array();

        $this->setTermTemplate( new ThemeView( 'partials/admin/meta-key-term-default.php' ) );

        $defaultTemplate = new ThemeView( 'partials/admin/meta-key-taxonomy-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );
    }

    /**
     * @param $taxonomy
     */
    public function addTaxonomy( $taxonomy ) {
        $this->taxonomy[] = $taxonomy;
    }

    /**
     * @return array
     */
    public function getTaxonomies() {
        return $this->taxonomy;
    }

    /**
     * @param ThemeView $template
     */
    public function setTermTemplate( ThemeView $template ) {
        $template->setArg( 'metaKey', $this );
        $this->termTemplate = $template;
    }

    /**
     * @param null $postId
     * @return array
     */
    public function getValue( $postId = null ) {
        $value = parent::getValue( $postId );
        return empty( $value ) ? array() : $value;
    }
}