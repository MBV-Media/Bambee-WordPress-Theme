<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\MetaBox;


use MBVMedia\Lib\ThemeView;

class MetaBox {

    private $id;
    private $title;
    private $context;
    private $template;

    private $metaKeyList;

    private $postTypeList;

    private $nonceName;
    private $nonceAction;

    /**
     * MetaBox constructor.
     * @param $id
     * @param $title
     * @param $context
     * @param ThemeView $template
     * @param int $priority
     */
    public function __construct( $id, $title, $context = 'advanced', $priority = 10, ThemeView $template = null ) {

        $this->id = $id;
        $this->title = $title;
        $this->context = $context;

        if( null === $template ) {
            $template = new ThemeView( 'partials/admin/meta-box-default.php' );
        }

        $this->template = $template;

        $this->metaKeyList = array();

        $this->postTypeList = array();

        $this->nonceName = get_class( $this );
        $this->nonceAction = 'save-' . $this->nonceName;

        add_action( 'add_meta_boxes' , array( $this, 'actionAddMetaBox' ), $priority, 1 );
        add_action( 'save_post', array( $this, 'actionSavePost' ), 10, 3 );
    }

    /**
     * @return array
     */
    public function getMetaKeyList() {
        return $this->metaKeyList;
    }

    /**
     * @param MetaKey $metaKey
     */
    public function addMetaKey( MetaKey $metaKey ) {
        $metaKey->getTemplate()->setArg( 'metaBox', $this );
        $this->metaKeyList[] = $metaKey;
    }

    /**
     * @param $postType
     * @param int $priority
     */
    public function addPostTypeSupport( $postType, $priority = 10 ) {
        $this->postTypeList[] = $postType;

        remove_action( 'add_meta_boxes' , array( $this, 'actionAddMetaBox' ) );
        add_action( 'add_meta_boxes_' . $postType , array( $this, 'actionAddMetaBox' ), $priority, 1 );
    }

    /**
     * @param $post
     */
    public function actionAddMetaBox( $post ) {
        $postType = $post instanceof \WP_Post ? $post->post_type : $post;
        add_meta_box( $this->id, $this->title, array( $this, 'renderMetaBox' ), $postType, $this->context );
    }

    /**
     * @param $post
     */
    public function renderMetaBox( $post ) {
        wp_nonce_field( $this->nonceAction, $this->nonceName );
        $this->template->setArg( 'metaKeyList', $this->getMetaKeyList() );
        echo $this->template->render();
    }

    /**
     * @param $postId
     * @param $post
     * @param $update
     */
    public function actionSavePost( $postId, $post, $update ) {

        $postType = get_post_type_object( $post->post_type );
        $currentUserCanEditPostType = current_user_can( $postType->cap->edit_post, $postId );

        if ( wp_is_post_autosave( $postId ) || wp_is_post_revision( $postId ) || ! $currentUserCanEditPostType ) {
            return;
        }

        $nonce = filter_input( INPUT_POST, $this->nonceName );
        if ( $nonce === null ) {
            $nonce = filter_input( INPUT_GET, $this->nonceName );
        }

        if( !wp_verify_nonce( $nonce, $this->nonceAction ) ) {
            return;
        }

        foreach ( $this->metaKeyList as $metaKey ) {
            $metaValue = filter_input( INPUT_POST, $metaKey->getKey(), FILTER_DEFAULT, $metaKey->getType() );
            update_post_meta( $postId, $metaKey->getKey(), $metaValue );
        }
    }

    /**
     * @param $metaKey
     * @param null $postId
     * @return mixed
     */
    public function getPostMeta( $metaKey, $postId = null ) {

        if ( $postId === null ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $metaKey, true );
    }

    /**
     * @param null $postId
     * @return array
     */
    public function getPostMetas( $postId = null ) {

        $postMetas = array();

        foreach ( $this->metaKeyList as $metaKey => $type ) {
            $postMetas[$metaKey] = $this->getPostMeta( $metaKey, $postId );
        }

        return $postMetas;
    }
}