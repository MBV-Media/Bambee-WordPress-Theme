<?php
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
    <script class="bambee-vars">
        var bambee = {
            websiteName: '<?php bloginfo( 'name' ); ?>',
            websiteUrl: '<?php bloginfo( 'wpurl' ); ?>',
            themeUrl: '<?php echo ThemeUrl; ?>',
            isSearch: <?php echo number_format( is_search() ); ?>,
            isMobile: <?php echo number_format( wp_is_mobile() ); ?>,
            debug: <?php echo number_format( WP_DEBUG || current_user_can( 'debug' ) ); ?>
        };
    </script>

    <title><?php wp_title( '| ' . get_bloginfo( 'name' ), true, 'right' ); ?></title>

    <?php wp_head(); ?>

    <!--[if lt IE 9]>
    <script type="text/javascript">
        window.location = "http://whatbrowser.org/intl/de/";
    </script>
    <![endif]-->
</head>
<body <?php body_class( 'no-js' ); ?>>
<script>
    jQuery('body').removeClass('no-js');
</script>
<div class="wrapper">
    <header class="header-main" role="banner" style="background-image: url('<?php header_image(); ?>')">

        <div class="top-bar">

            <div class="row">
                <div class="column">

                    <div class="top-bar-title">

                        <?php echo get_template_part( 'partials/main', 'logo' ); ?>
                        <strong>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </strong>

                    </div>

                    <div class="top-bar-left">
                        <!--Add more top-bar elements here-->
                    </div>

                    <nav    id="responsive-menu"
                            class="top-bar-right responsive-menu"
                            role="navigation">
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
                    <span class="responsive-menu-toggle hide-for-large" data-responsive-toggle="responsive-menu" data-hide-for="large">
                        <button class="menu-icon dark" type="button" data-toggle></button>
                    </span>

                </div>
            </div>

        </div>

    </header>

    <!-- Main -->
    <main class="content-main" role="main">
