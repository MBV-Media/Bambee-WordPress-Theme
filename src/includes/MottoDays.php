<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Inc;


/**
 * The class representing the MottoDays.
 * Creates the custom post type 'mottotage' and the corresponding
 * MetaBox.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class MottoDays {

    /**
     * @var int
     * @since 1.0.0
     */
    public $weekday = 0;

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        $todayTimestamp = strtotime( 'today' );
        $this->weekday = intval( date( 'w', $todayTimestamp ) );

        add_action( 'init', array( $this, '_createPostTypes' ) );
        add_action( 'add_meta_boxes', array( $this, '_createMetaBoxes' ) );
        add_action( 'save_post', array( $this, '_saveMottotageMetaBox' ), 10, 2 );
    }

    /**
     * Register post type 'mottotage'.
     *
     * @since 1.0.0
     * @return void
     */
    public function _createPostTypes() {
        $slug = 'mottotage';

        # TODO: Move po/mo for translation
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            switch ( ICL_LANGUAGE_CODE ) {
                case 'en':
                    $slug = 'theme-days';
                    break;
                case 'fr':
                    $slug = 'journees-a-theme';
                    break;
            }
        }

        register_post_type( 'mottotage', array(
                        'labels' => array(
                                'name' => __( 'Mottotage' ),
                                'singular_name' => __( 'mottotag' )
                        ),
                        'menu_icon' => get_template_directory_uri() . '/includes/img/icons/mottotage.png',
                        'public' => true,
                        'has_archiv' => true,
                        'rewrite' => array( 'slug' => $slug ),
                        'show_ui' => true, # UI in admin panel
                        'capability_type' => 'post',
                        'hierarchical' => true,
                        'supports' => array( 'title', 'editor', 'thumbnail', 'trackbacks', 'custom-fields', 'revisions' ),
                        'taxonomies' => array( 'category' ),
                        'exclude_from_search' => true,
                        'publicly_queryable' => true,
                        'excerpt' => true,
                        'suppress_filters' => false
                )
        );
    }

    /**
     * Register the MetaBox.
     *
     * @since 1.0.0
     * @return void
     */
    public function _createMetaBoxes() {
        add_meta_box( 'mottotage', 'Mottotage', array( $this, '_addMottotageMetaBox' ), 'mottotage', 'side' );
    }

    /**
     * Render the MetaBox.
     *
     * @since 1.0.0
     * @param WP_Post $post
     * @return void
     */
    public function _addMottotageMetaBox( $post ) {
        $days = array(
                0 => __( 'Sonntag' ),
                1 => __( 'Montag' ),
                2 => __( 'Dienstag' ),
                3 => __( 'Mittwoch' ),
                4 => __( 'Donnerstag' ),
                5 => __( 'Freitag' ),
                6 => __( 'Samstag' )
        );
        $selectedDays = get_post_meta( $post->ID, 'mottotage', true );
        if ( empty( $selectedDays ) ) {
            $selectedDays = array();
        }

        $output = '<select multiple size="7" name="mottotage[]" id="mottotage">';

        foreach ( $days as $key => $day ) {
            $selected = ( in_array( $key, $selectedDays ) ? 'selected' : '' );
            $output .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    $key,
                    $selected,
                    $day
            );
        }

        $output .= '</select>';
        echo $output;

        # Add nonce field
        wp_nonce_field( basename( __FILE__ ), 'mottotage_nonce' );
    }

    /**
     * Save the MetaBox.
     *
     * @since 1.0.0
     * @param int $postId
     * @param WP_Post $post
     * @return mixed
     */
    public function _saveMottotageMetaBox( $postId, $post ) {
        # Verify the nonce before proceeding.
        if ( !isset( $_POST['mottotage_nonce'] ) || !wp_verify_nonce( $_POST['mottotage_nonce'], basename( __FILE__ ) ) )
            return $postId;

        $postType = get_post_type_object( $post->post_type );

        # Check if the current user has permission to edit the post.
        if ( !current_user_can( $postType->cap->edit_post, $postId ) )
            return $postId;

        $newMetaValue = array();
        if ( isset( $_POST['mottotage'] ) ) {
            $days = is_array( $_POST['mottotage'] ) ? $_POST['mottotage'] : array( $_POST['mottotage'] );

            foreach ( $days as $day ) {
                $newMetaValue[] = sanitize_html_class( $day );
            }
        }

        $metaKey = 'mottotage';

        $metaValue = get_post_meta( $postId, $metaKey );

        # If a new meta value was added and there was no previous value, add it.
        if ( count( $newMetaValue ) > 0 && '' == $metaValue )
            add_post_meta( $postId, $metaKey, $newMetaValue, true );

        # If the new meta value does not match the old value, update it.
        elseif ( count( $newMetaValue ) > 0 && $newMetaValue != $metaValue )
            update_post_meta( $postId, $metaKey, $newMetaValue );

        # If there is no new meta value but an old value exists, delete it.
        elseif ( count( $newMetaValue ) <= 0 && $metaValue )
            delete_post_meta( $postId, $metaKey );
    }
}