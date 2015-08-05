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
    <meta charset="<?php echo get_bloginfo( 'charset' ); ?>">
    <meta name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">

    <title><?php bloginfo( 'name' ); ?><?php wp_title( '|', true, 'left' ); ?></title>
    <?php wp_enqueue_script( 'comment-reply' ); ?>

    <link rel="shortcut icon" href="<?php echo ThemeUrl; ?>/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo( 'stylesheet_url' ); ?>"/>

    <?php wp_head(); ?>

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
        <nav class="top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
                </li>
                <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
            </ul>

            <section class="top-bar-section">
                <?php
                $menu = wp_nav_menu(
                        array(
                                'container' => 'ul',
                                'theme_location' => 'header-menu',
                                'link_before' => '<span>',
                                'link_after' => '</span>',
                                'echo' => false,
                            /*'fallback_cb' => ''*/
                        )
                );
                echo $menu;
                ?>
            </section>
        </nav>
    </header>

    <!-- Main -->
    <main class="content-main" role="main">
        <section class="main">
