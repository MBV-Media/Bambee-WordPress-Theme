<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaKeyMedia extends MetaKey {

    /**
     * MetaKeyCheckbox constructor.
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new ThemeView( 'partials/admin/meta-key-media-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );
    }

    /**
     * @param $key
     * @param null $postId
     * @param string $size
     * @param string $attr
     */
    public static function thePostMedia( $key, $postId = null, $size = 'thumbnail', $attr = '' ) {
        echo self::getThePostMedia( $key, $postId, $size, $attr );
    }

    /**
     * @param $key
     * @param null $postId
     * @param string $size
     * @param string $attr
     * @return string
     */
    public static function getThePostMedia( $key, $postId = null, $size = 'thumbnail', $attr = '' ) {
        return wp_get_attachment_image( self::getPostMediaId( $key, $postId ), $size, false, $attr );
    }

    /**
     * @param $key
     * @param null $postId
     * @return mixed
     */
    public static function getPostMediaId( $key, $postId = null ) {
        if ( null === $postId ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $key, true);
    }
}