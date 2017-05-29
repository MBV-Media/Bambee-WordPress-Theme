<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

abstract class MetaKey {

    const TYPE_DEFAULT = FILTER_DEFAULT;
    const TYPE_ARRAY = FILTER_REQUIRE_ARRAY;

    /**
     * @var
     */
    private $key;

    /**
     * @var
     */
    private $label;

    /**
     * @var
     */
    private $type;

    /**
     * @var ThemeView
     */
    private $template;

    /**
     * MetaKey constructor.
     * @param $key
     * @param $label
     * @param $type
     */
    public function __construct( $key, $label, $type) {
        $this->key = $key;
        $this->type = $type;
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return ThemeView
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param ThemeView $template
     */
    public function setTemplate( ThemeView $template ) {
        $template->setArg( 'metaKey', $this );
        $this->template = $template;
    }

    /**
     * @param null $postId
     * @return mixed
     */
    public function getValue( $postId = null ) {

        if (  null === $postId ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $this->key, true );
    }
}