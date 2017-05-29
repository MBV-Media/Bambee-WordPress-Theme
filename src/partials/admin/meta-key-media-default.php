<?php $metaValue = $metaKey->getValue(); ?>

<p class="hide-if-no-js">
    <img id="<?php echo $metaKey->getKey(); ?>-image"
        src="<?php echo wp_get_attachment_image_url( $metaValue, 'full' ); ?>"
        style="width: 100%; height: auto; border: 0; cursor: pointer; display: <?php echo !empty( $metaValue ) ? 'block' : 'none'; ?>;" />
</p>
<p class="hide-if-no-js howto" id="set-<?php echo $metaKey->getKey(); ?>-desc"
    style="display: <?php echo !empty( $metaValue ) ? 'block' : 'none'; ?>;">
    <?php _e( 'Click the image to edit or update' ); ?>
</p>

<p class="hide-if-no-js">
    <a href="javascript:;"
        id="<?php echo $metaKey->getKey(); ?>-remove"
        style="display: <?php echo !empty( $metaValue ) ? 'block' : 'none'; ?>;" >
        <?php esc_html_e( sprintf( '%s entfernen', $metaKey->getLabel() ), TextDomain ); ?>
    </a>
    <a title="<?php esc_html_e( sprintf( '%s festlegen', $metaKey->getLabel() ), TextDomain ); ?>"
        href="javascript:;"
        id="<?php echo $metaKey->getKey(); ?>-upload"
        style="display: <?php echo empty( $metaValue ) ? 'block' : 'none'; ?>;"
        data-uploader_title="<?php echo $metaKey->getLabel(); ?>"
        data-uploader_button_text="<?php esc_html_e( sprintf( '%s festlegen', $metaKey->getLabel() ), TextDomain ); ?>">
        <?php esc_html_e( sprintf( '%s festlegen', $metaKey->getLabel() ), TextDomain ); ?>
    </a>
</p>
<input type="hidden" id="<?php echo $metaKey->getKey(); ?>-value" name="<?php echo $metaKey->getKey(); ?>" value="<?php echo esc_attr( $metaValue ); ?>" />

<script type="text/javascript">
    jQuery(document).ready(function($) {

        // Uploading files
        var fileFrame;

        jQuery.fn.uploadListingImage = function() {

            // If the media frame already exists, reopen it.
            if ( fileFrame ) {
                fileFrame.open();
                return;
            }

            // Create the media frame.
            fileFrame = wp.media.frames.file_frame = wp.media({
                title: jQuery('#<?php echo $metaKey->getKey(); ?>-upload').data( 'uploader_title' ),
                button: {
                    text: jQuery('#<?php echo $metaKey->getKey(); ?>-upload').data( 'uploader_button_text' ),
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            fileFrame.on( 'select', function() {
                var attachment = fileFrame.state().get('selection').first().toJSON();
                jQuery('#<?php echo $metaKey->getKey(); ?>-value').val(attachment.id);
                jQuery('#<?php echo $metaKey->getKey(); ?>-image').attr('src',attachment.url).show();
                jQuery('#<?php echo $metaKey->getKey(); ?>-upload').hide();
                jQuery('#<?php echo $metaKey->getKey(); ?>-remove').show();
                jQuery('#set-<?php echo $metaKey->getKey(); ?>-desc').show();
            });

            // Finally, open the modal
            fileFrame.open();
        };

        jQuery('#<?php echo $metaKey->getKey(); ?>-upload, #<?php echo $metaKey->getKey(); ?>-image').on( 'click', function( event ) {
            event.preventDefault();
            jQuery.fn.uploadListingImage();
        });

        jQuery('#<?php echo $metaKey->getKey(); ?>-remove').on( 'click', function( event ) {
            event.preventDefault();
            jQuery('#<?php echo $metaKey->getKey(); ?>-value').val( '' );
            jQuery('#<?php echo $metaKey->getKey(); ?>-image').attr( 'src', '' ).hide();
            jQuery('#<?php echo $metaKey->getKey(); ?>-upload').show();
            jQuery('#<?php echo $metaKey->getKey(); ?>-remove').hide();
            jQuery('#set-<?php echo $metaKey->getKey(); ?>-desc').hide();
        });

    });
</script>