<<?php echo $tag; ?> <?php comment_class( empty( $arguments['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
<?php if ('div' != $arguments['style']) : ?>
<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>

    <div class="row">
        <!-- Comment date -->
        <div class="col-xs-12 col-md-5 col-md-push-7">
            <div class="post-meta">
                <div class="post-date day"><?php echo get_comment_date( 'd' ); ?>.</div>
                <div class="post-date month"><?php echo get_comment_date( 'F' ); ?></div>
                <div class="post-date year"><?php echo get_comment_date( 'Y' ); ?>.</div>
            </div>
        </div>
        <!-- End comment date -->

        <!-- Comment content -->
        <div class="col-xs-12 col-md-6 col-md-pull-5">
            <h2><?php echo get_comment_meta( get_comment_ID(), 'subject', true ); ?></h2>

            <div class="comment-head">
                <div class="comment-author vcard">
                    <?php printf( '%s <span class="fn">%s</span>', __( 'Verfasst von', TextDomain ), get_comment_author_link() ); ?>
                    <div class="reply">
                        <?php
                        comment_reply_link(
                                array_merge( $arguments, array(
                                        'reply_text' => '<span class="inner"></span>',
                                        'add_below' => $add_below,
                                        'depth' => $depth,
                                        'max_depth' => $arguments['max_depth']
                                ) )
                        );
                        ?>
                    </div>
                </div>
            </div>

            <div class="comment-entry">
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', TextDomain ); ?></p>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </div>
        <!-- End comment content -->
    </div>

    <?php if ('div' != $arguments['style']) : ?>
</div>
<?php endif; ?>
