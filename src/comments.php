<?php if ( !empty( $_SERVER['SCRIPT_FILENAME'] )
    && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] )
) : ?>
    <?php die( __( 'The file "comments.php" can not be accessed directly.', TextDomain ) ); ?>
<?php endif; ?>

<?php global $bambeeWebsite; ?>

    <div class="comment-form">
        <?php comment_form( array(
            'title_reply' => '',
            'comment_notes_before' => '',
            'comment_notes_after' => __(
                'Die mit * gekennzeichneten Felder sind Pflichtfelder.',
                TextDomain
            ),
            'label_submit' => __( 'Beitrag senden', TextDomain ),
        ) ); ?>
    </div>

<?php if ( have_comments() ) : ?>
    <?php $bambeeWebsite->commentPagination(); ?>
    <section class="comments">
        <div class="sideline"></div>
        <ul class="comment-list">
            <?php
            wp_list_comments( array(
                'style' => 'li',
                'avatar_size' => 0,
                'per_page' => get_option( 'comments_per_page' ),
                'callback' => array( $bambeeWebsite, 'commentList' ),
            ), array_reverse( $comments ) );
            ?>
        </ul>
    </section>
    <?php $bambeeWebsite->commentPagination(); ?>
<?php endif; ?>