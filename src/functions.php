<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
require_once( 'vendor/autoload.php' );

error_reporting( E_ALL );
ini_set( "display_errors", WP_DEBUG );

\Lib\CustomBambee::run();
