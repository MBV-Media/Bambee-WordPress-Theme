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
                'vendor-js',
                ThemeUrl . '/js/vendor.min.js',
                array( 'jquery' )
        );
        $this->addScript(
                'main-js',
                ThemeUrl . '/js/main.min.js',
                array( 'jquery' )
        );

        # Enqueue additional styles
        $this->addStyle(
                'vendor-css',
                ThemeUrl . '/css/vendor.min.css'
        );
        $this->addStyle(
                'main-css',
                ThemeUrl . '/css/main.min.css'
        );
    }
}