<?php

$customLogoId = get_theme_mod( 'custom_logo' );
$customLogoFile = get_attached_file( $customLogoId );

if( pathinfo( $customLogoFile, PATHINFO_EXTENSION ) == 'svg' ) : ?>

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <?php include $customLogoFile; ?>
    </a><?php

else :
    the_custom_logo();
endif;
