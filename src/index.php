<?php get_header(); ?>

<?php $mainContent = new \MBVMedia\Lib\ThemeView( 'partials/main-loop.php' ); ?>
<?php \Lib\CustomWebsite::self()->mainLoop( $mainContent ); ?>

<?php get_footer(); ?>
