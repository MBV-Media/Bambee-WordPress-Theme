<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use Inc\BambeeWebsite;

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
        # Enqueue additional scripts
        $this->scripts = array(
                array(
                        'handle' => 'vendor-js',
                        'src' => ThemeUrl . '/js/vendor.min.js',
                        'deps' => array( 'jquery' )
                ),
                array(
                        'handle' => 'main-js',
                        'src' => ThemeUrl . '/js/main.min.js',
                        'deps' => array( 'jquery' )
                )
        );

        # Enqueue additional styles
        $this->styles = array(
                array(
                        'handle' => 'vendor-css',
                        'src' => ThemeUrl . '/css/vendor.min.css'
                ),
                array(
                        'handle' => 'main-css',
                        'src' => ThemeUrl . '/css/main.min.css'
                )
        );
        parent::__construct();
    }
}