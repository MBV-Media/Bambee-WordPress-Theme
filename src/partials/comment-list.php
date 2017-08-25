<<?php echo $tag; ?> <?php comment_class( empty( $arguments['has_children'] ) ? '' : 'parent' ); ?>
    id="comment-<?php comment_ID(); ?>">
    <div class="comment-head">
        <div class="vcard">
            <?php
            printf(
                '%s <span class="comment-author fn">%s</span>',
                __( 'Author: ', TextDomain ),
                get_comment_author_link()
            );
            ?>
            <span class="comment-date">
                <?php _e( 'on', TextDomain ); ?> <?php echo get_comment_date( 'd.m.Y H:i' ); ?>
            </span>

            <div class="reply">
                <?php
                comment_reply_link(
                    array_merge( $arguments, array(
                        'reply_text' => '<span class="inner"></span>',
                        'add_below' => $addBelow,
                        'depth' => $depth,
                        'max_depth' => $arguments['max_depth'],
                    ) )
                );
                ?>
            </div>
        </div>
    </div>

    <div class="comment-entry">
        <?php if ( $comment->comment_approved == '0' ) : ?>
            <p class="comment-awaiting-moderation">
                <?php _e( 'Your comment is awaiting moderation.', TextDomain ); ?>
            </p>
        <?php endif; ?>
        <?php comment_text(); ?>
    </div>