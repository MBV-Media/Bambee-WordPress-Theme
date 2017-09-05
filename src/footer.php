</main>

<footer role="contentinfo">
    <div class="row">
        <div class="column medium-4">
            <nav class="footer-nav" role="navigation">
                <?php
                echo wp_nav_menu(
                    array(
                        'container' => 'ul',
                        'theme_location' => 'footer-menu',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                        'echo' => false,
                    )
                );
                ?>
            </nav>
        </div>
        <div class="column medium-4">
            <address>
                <?php echo nl2br( get_option( 'bambee_core_data_address' ) ); ?>
            </address>
        </div>
        <div class="column medium-4">
            <?php _e( 'E-mail:', TextDomain ); ?> <?php
            $email = get_option( 'bambee_core_data_email' );
            printf( '<a href="mailto:%s">%s</a>', $email, $email );
            ?>
            <br>
            <?php _e( 'Phone:', TextDomain ); ?> <?php
            $phoneNumber = get_option( 'bambee_core_data_phone' );
            $phoneNumberClean = str_replace( array( ' ', '-', '/', '(', ')' ), '', $phoneNumber );
            printf( '<a href="tel:%s">%s</a>', $phoneNumberClean, $phoneNumber );
            ?>
        </div>
    </div>
</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
