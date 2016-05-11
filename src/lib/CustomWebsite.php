<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use MBVMedia\BambeeWebsite;

/**
 * The class representing the website (user frontend).
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomWebsite extends BambeeWebsite {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        parent::__construct();

        # Enqueue additional scripts
        $this->addScript(
                'comment-reply',
                false
        );
        $this->addScript(
                'vendor',
                ThemeUrl . '/js/vendor.min.js',
                array( 'jquery' )
        );
        $this->addScript(
                'main',
                ThemeUrl . '/js/main.min.js',
                array( 'jquery' )
        );

        # Enqueue additional styles
        $this->addStyle(
                'theme',
                get_bloginfo( 'stylesheet_url' )
        );
        $this->addStyle(
                'vendor',
                ThemeUrl . '/css/vendor.min.css'
        );
        $this->addStyle(
                'main',
                ThemeUrl . '/css/main.min.css'
        );
    }
}