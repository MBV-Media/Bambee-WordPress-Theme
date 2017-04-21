<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
require_once( 'vendor/autoload.php' );

error_reporting( E_ALL );
if( WP_DEBUG ) {
    ini_set( "display_errors", 1 );
}
else {
    ini_set("display_errors", 0);
}

define( 'TextDomain', 'bambee' );
define( 'ThemeDir', get_stylesheet_directory() );
define( 'ThemeUrl', get_stylesheet_directory_uri() );

\Lib\CustomBambee::run();
