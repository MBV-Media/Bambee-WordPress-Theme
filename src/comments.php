<?php if ( !empty( $_SERVER['SCRIPT_FILENAME'] )
    && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] )
) : ?>
    <?php die( __( 'The file "comments.php" can not be accessed directly.', TextDomain ) ); ?>
<?php endif; ?>

<?php global $bambee,$bambeeWebsite; ?>

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
    <?php $pagination = $bambeeWebsite->getCommentPagination(); ?>
    <?php echo $pagination; ?>
    <section class="comments">
        <div class="sideline"></div>
        <ul class="comment-list">
            <?php

            $commenter = wp_get_current_commenter();

            $comments = get_comments( array(
                'post_id' => get_the_ID(),
                'include_unapproved' => $commenter['comment_author_email'],
                'status' => 'approve',
            ) );

            wp_list_comments( array(
                'style' => 'ul',
                'avatar_size' => 0,
                'callback' => array( $bambeeWebsite, 'commentList' ),
                'walker' => new \MBVMedia\BambeeWalkerComment(),
            ), $comments );

            ?>
        </ul>
    </section>
    <?php echo $pagination; ?>
<?php endif; ?>
