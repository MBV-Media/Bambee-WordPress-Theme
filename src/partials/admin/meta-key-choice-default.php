<p class="post-attributes-label-wrapper">
    <label class="post-attributes-label" for="<?php echo $metaKey->getKey(); ?>">
        <?php echo $metaKey->getLabel(); ?>
    </label>

</p>
<select id="<?php echo $metaKey->getKey(); ?>"
    name="<?php echo $metaKey->getKey(); ?>">

    <?php foreach ( $metaKey->getChoices() as $choice ) : ?>
        <option value="<?php echo $choice['value']; ?>"<?php selected( $choice['value'], $metaKey->getValue() ); ?>>
            <?php echo $choice['label']; ?>
        </option>
    <?php endforeach; ?>

</select>