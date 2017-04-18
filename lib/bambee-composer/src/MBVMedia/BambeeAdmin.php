<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace MBVMedia;



/**
 * The class representing the WordPress Admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
abstract class BambeeAdmin extends BambeeBase {

    /**
     * @var integer
     */
    private $postPerPageLimit;

    private static $instance = null;

    /**
     *
     */
    public function addActions() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueStyles' ) );
        add_action( 'admin_init', array( $this, 'displaySvgThumbs' ) );
        add_action( 'manage_gallery_posts_custom_column' , array( $this, 'customColumnsData' ), 10, 2 );
    }

    /**
     *
     */
    public function addFilters() {
        add_filter( 'upload_mimes', array( $this, 'addSvgMediaSupport' ) );
        add_filter( 'edit_comments_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        foreach ( get_post_types() as $postType ) {
            add_filter( 'edit_' . $postType . '_per_page', array( $this, 'modifyPostPerPageLimit' ) );
        }
        add_filter('manage_posts_columns' , array( $this, 'customColumns' ) );
    }

    /**
     * @param integer $postPerPageLitmit
     */
    public function setPostPerPageLimit( $postPerPageLitmit ) {
        $this->postPerPageLimit = $postPerPageLitmit;
    }


    /**
     * Action-hook callbacks
     */

    /**
     * Enqueue the CSS.
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueueStyles() {
        wp_enqueue_style( 'custom_css', ThemeUrl . '/css/admin.min.css' );
    }


    /**
     * Filter-hook callbacks
     */

    /**
     * @param $mimes
     * @return mixed
     */
    public function addSvgMediaSupport( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     *
     */
    public function displaySvgThumbs() {

        ob_start();

        add_action( 'shutdown', array( $this, 'svgThumbsFilter' ), 0 );
        add_filter( 'final_output', array( $this, 'svgFinalOutput' ) );
    }

    /**
     *
     */
    public function svgThumbsFilter() {

        $final = '';
        $ob_levels = count( ob_get_level() );

        for ( $i = 0; $i < $ob_levels; $i++ ) {

            $final .= ob_get_clean();

        }

        echo apply_filters( 'final_output', $final );
    }

    /**
     * @param $content
     * @return mixed
     */
    public function svgFinalOutput( $content ) {

        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
				<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',

            $content
        );

        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',

            $content
        );

        return $content;
    }

    /**
     * @param $option
     * @param int $default
     * @return int
     */
    public function modifyPostPerPageLimit( $option, $default = 20 ) {
        return $this->postPerPageLimit;
    }

    /**
     * @param $columns
     * @return array
     */
    public function customColumns( $columns ) {

        $offset = array_search( 'date', array_keys( $columns ) );

        return array_merge (
            array_slice( $columns, 0, $offset ),
            array( 'featured_image' => __( 'Beitragsbild', TextDomain ) ),
            array_slice( $columns, $offset, null)
        );
    }

    /**
     * @param $column
     * @param $postId
     */
    public function customColumnsData( $column, $postId ) {
        switch ( $column ) {
            case 'featured_image':
                echo the_post_thumbnail( 'thumbnail' );
                break;
        }
    }
}