</section>
</main>

<footer role="contentinfo">
    <div class="row">
        <div class="column medium-4">
            <address>
                <?php echo nl2br( get_option( 'bambee_core_data_address' ) ); ?>
            </address>
        </div>
        <div class="column medium-4">
            <?php _e( 'Email:', TextDomain ); ?> <?php
            $email = get_option( 'bambee_core_data_email' );
            printf( '<a href="mailto:%s">%s</a>', $email, $email );
            ?>
            <br>
            <?php _e( 'Tel.:', TextDomain ); ?> <?php
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
