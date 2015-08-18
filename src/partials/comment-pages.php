<div class="row">
    <ul class="pagination" role="menubar" aria-label="Pagination">
        <?php if( $pagination_prev ) : ?>
            <li class="arrow"><?php echo $pagination_prev; ?></li>
        <?php endif; ?>

        <?php if( $pagination_pages ) : ?>
            <?php echo $pagination_pages; ?>
        <?php endif; ?>

        <?php if( $pagination_next ) : ?>
            <li class="arrow"><?php echo $pagination_next; ?></li>
        <?php endif; ?>
    </ul>
</div>