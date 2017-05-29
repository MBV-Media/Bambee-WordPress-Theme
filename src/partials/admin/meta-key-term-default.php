<?php $metaValues = $metaKey->getValue(); ?>
<li id="<?php echo $metaKey->getKey(); ?>-<?php echo $term->term_id; ?>" class="toggle">
    <label class="selectit">
        <input value="<?php echo $term->term_id; ?>"
            type="checkbox"
            name="<?php echo $metaKey->getKey(); ?>[]"
            id="in-category-<?php echo $term->term_id; ?>"
            <?php checked( true, in_array( $term->term_id, $metaValues ) ); ?>>
        <?php echo $term->name; ?>
        <span class="cat-description">
            <?php echo $term->description; ?>
        </span>
    </label>

    <?php

    $subterms = get_terms( $metaKey->getTaxonomies(), array(
        'parent' => $term->term_id,
        'hide_empty' => false,
    ) );

    if ( !empty( $subterms ) ) : ?>

        <ul class="children">

            <?php foreach ( $subterms as $subterm ) : ?>

                <?php $listItem = new \MBVMedia\Lib\ThemeView( 'partials/admin/meta-key-term-default.php', array(
                    'term' => $subterm,
                    'metaKey' => $metaKey,
                ) ); ?>
                <?php echo $listItem->render(); ?>

            <?php endforeach; ?>

        </ul>

    <?php endif; ?>

</li>