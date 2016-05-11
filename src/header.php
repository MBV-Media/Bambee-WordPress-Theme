<?php
global $bambee, $bambeeWebsite;
$short_lang = get_locale();
$short_lang = explode( '_', $short_lang );
$short_lang = $short_lang[0];
?><!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 ie-lte8" lang="<?php echo $short_lang; ?>"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 ie-lte8" lang="<?php echo $short_lang; ?> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 ie-lte8" lang="<?php echo $short_lang; ?>"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 ie-lte9" lang="<?php echo $short_lang; ?>"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!-->
<html lang="<?php echo $short_lang; ?>"><!--<![endif]-->
<head>
    <script class="bambee-vars">
        var bambee = {
            websiteName: '<?php bloginfo( 'name' ); ?>',
            websiteUrl: '<?php bloginfo( 'wpurl' ); ?>',
            themeUrl: '<?php echo ThemeUrl; ?>',
            isSearch: <?php echo number_format( is_search() ); ?>,
        };
    </script>
    <meta charset="<?php echo get_bloginfo( 'charset' ); ?>">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">

    <title><?php bloginfo( 'name' ); ?><?php wp_title( '|', true, 'left' ); ?></title>

    <link rel="shortcut icon" href="<?php echo ThemeUrl; ?>/favicon.ico" type="image/x-icon"/>

    <?php wp_head(); ?>

    <!--[if IE 7]>
    <script type="text/javascript">
        window.location = "http://whatbrowser.org/";
    </script>
    <![endif]-->

    <!--[if lt IE 9 ]>
    <script type="text/javascript" src="<?php echo ThemeUrl; ?>/js/vendor/ie.min.js"></script>
    <![endif]-->
</head>
<body <?php body_class( 'no-js' ); ?>>
<script>
    jQuery('body').removeClass('no-js');
</script>
<div class="wrapper">
    <header class="header-main" role="banner">
        <div class="top-bar">
            <div class="top-bar-title">
                <span data-responsive-toggle="responsive-menu" data-hide-for="medium">
                    <button class="menu-icon dark" type="button" data-toggle></button>
                </span>
                <strong><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></strong>
            </div>
            <div id="responsive-menu">
                <nav class="top-bar-left" role="navigation">
                    <?php
                    echo wp_nav_menu(
                        array(
                            'container' => 'ul',
                            'theme_location' => 'header-menu',
                            'link_before' => '<span>',
                            'link_after' => '</span>',
                            'echo' => false,
                        )
                    );
                    ?>
                </nav>
                <div class="top-bar-right">
                    <!--Add more top-bar elements here-->
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="content-main" role="main">
        <section class="main">
