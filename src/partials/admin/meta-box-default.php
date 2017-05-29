<?php foreach ( $metaKeyList as $metaKey ) : ?>

    <?php echo $metaKey->getTemplate()->render(); ?>

<?php endforeach; ?>