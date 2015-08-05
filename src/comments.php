<?php if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) : ?>
    <?php die( 'Die Datei "comments.php" kann nicht direkt aufgerufen werden.' ); ?>
<?php endif; ?>

<?php
global $bambeeWebsite;
?>


    <section class="comment-form">
        <?php comment_form( array(
            'comment_notes_after' => '<div class="pflichtfelder">*' . __( 'Pflichtfelder', TextDomain ) . '</div>',
            'label_submit'        => __( 'Beitrag senden', TextDomain )
        ) ); ?>
        <div class="clear"></div>
    </section>

<?php if ( have_comments() ) { ?>
    <div class="pageination">
        <div
            class="comment_navigate"><?php paginate_comments_links(); ?></div>
    </div>
    <section class="comments">
        <div class="sideline"></div>
        <ul class="commentlist">
            <?php
            $comments = array_reverse( $comments );

            wp_list_comments( array(
                'style'       => 'ul',
                'avatar_size' => 0,
                'per_page'    => 5
            ), $comments );
            ?>
        </ul>
        <!-- .commentlist -->
    </section><!-- kommentare -->
    <div class="pageination">
        <div
            class="comment_navigate"><?php paginate_comments_links(); ?></div>
    </div>
<?php } ?>