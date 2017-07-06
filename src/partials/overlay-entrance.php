<div class="overlay-entry">
    <div class="overlay-entry-content text-center">
        <div class="row">
            <div class="column medium-6 medium-centered">
                <header>
                    <?php the_custom_logo(); ?>
                </header>
                <p>
                    <?php _e( 'This is an example entrance overlay.', TextDomain ); ?>
                </p>
                <p>
                    <strong><?php _e( 'I have read and understood the proceeding information:', TextDomain ); ?></strong>
                </p>
                <p>
                    <a href="?enter" class="button success js-enter" data-prevent-default><?php _e( 'Enter', TextDomain ); ?></a>
                    <a href="http://www.google.de" class="button alert"><?php _e( 'Leave', TextDomain ); ?></a>
                </p>
                <a href="<?php echo home_url( '/impressum' ); ?>">Impressum</a>
            </div>
        </div>
    </div>
</div>