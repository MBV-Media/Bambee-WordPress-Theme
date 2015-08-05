<?php

require_once( 'vendor/autoload.php' );

ini_set( "display_errors", true );
error_reporting( E_ALL );

define( 'TextDomain', 'bambee' );
define( 'ThemeDir', get_stylesheet_directory() );
define( 'ThemeUrl', get_stylesheet_directory_uri() );

use Lib\CustomBambee;
use Lib\CustomAdmin;
use Lib\CustomWebsite;

$bambee = new CustomBambee();
if ( is_admin() ) {
    $bambeeWebsite = new CustomAdmin();
} else {
    $bambeeWebsite = new CustomWebsite();
}