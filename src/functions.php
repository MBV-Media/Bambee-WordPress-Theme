<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
require_once( 'vendor/autoload.php' );

$displayErrors = WP_DEBUG || current_user_can( 'debug' );
ini_set( "display_errors", $displayErrors );
ini_set( 'error_reporting', E_ALL );

\Lib\CustomBambee::run();
