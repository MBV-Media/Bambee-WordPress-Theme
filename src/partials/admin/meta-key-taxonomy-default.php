<div class="taxonomydiv">

    <p class="post-attributes-label-wrapper">
        <label class="post-attributes-label"><?php echo $metaKey->getLabel(); ?></label>
    </p>
    <?php

    $args = array(
        'orderby'           => 'name',
        'order'             => 'ASC',
        'hide_empty'        => false,
        'fields'            => 'all',
        'parent'            => 0,
        'hierarchical'      => true,
        'child_of'          => 0,
        'pad_counts'        => false,
        'cache_domain'      => 'core'
    );

    $termList = get_terms( $metaKey->getTaxonomies(), $args );

    $metaValues = $metaKey->getValue();

    if ( empty( $metaValues ) ) {
        $metaValues = array();
    }

    ?>

    <input type="hidden" name="<?php echo $metaKey->getKey(); ?>[]" value="0">
    <ul class="categorychecklist" data-wp-lists="list:category">

        <?php foreach ( $termList as $term ) : ?>

            <?php $listItem = new \MBVMedia\Lib\ThemeView( 'partials/admin/meta-key-term-default.php', array(
                'term' => $term,
                'metaKey' => $metaKey,
            ) ); ?>

            <?php echo $listItem->render(); ?>

        <?php endforeach; ?>
    </ul>

</div>