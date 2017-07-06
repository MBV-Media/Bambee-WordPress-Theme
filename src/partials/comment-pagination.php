<ul class="pagination" role="menubar" aria-label="Pagination">
    <?php if ( $paginationPrev ) : ?>
        <li class="arrow"><?php echo $paginationPrev; ?></li>
    <?php endif; ?>

    <?php if ( $paginationPages ) : ?>
        <?php echo $paginationPages; ?>
    <?php endif; ?>

    <?php if ( $paginationNext ) : ?>
        <li class="arrow"><?php echo $paginationNext; ?></li>
    <?php endif; ?>
</ul>