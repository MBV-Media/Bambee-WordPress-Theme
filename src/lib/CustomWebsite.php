<?php namespace Lib;


use Inc\BambeeWebsite;

/**
 * Class CustomWebsite
 *
 * End-User frontend
 */
class CustomWebsite extends BambeeWebsite {
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