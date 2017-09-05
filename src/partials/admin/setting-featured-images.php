<label>
    <?php _e( 'Width' ); ?>
    <input name="bambee_featured_images[width]" type="number" step="1" min="0" value="<?php echo $width; ?>" class="small-text">
</label>
<label>
    <?php _e( 'Height' ); ?>
    <input name="bambee_featured_images[height]" type="number" step="1" min="0" value="<?php echo $height; ?>" class="small-text">
</label>
<br>
<label>
    <input name="bambee_featured_images[crop]" type="hidden" value="0">
    <input name="bambee_featured_images[crop]" type="checkbox" value="1"<?php checked( true, boolval( $crop ), true ); ?>>
    Beschneide das Beitragsbild auf die exakte Größe (Beitragsbilder sind normalerweise proportional)
</label>
