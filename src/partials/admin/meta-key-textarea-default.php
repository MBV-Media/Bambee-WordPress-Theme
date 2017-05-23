<p class="post-attributes-label-wrapper">
    <label class="post-attributes-label" for="<?php echo $metaKey->getKey(); ?>">
        <?php echo $metaKey->getLabel(); ?>
    </label>

</p>
<textarea id="<?php echo $metaKey->getKey(); ?>"
    name="<?php echo $metaKey->getKey(); ?>"><?php echo $metaKey->getValue(); ?></textarea>