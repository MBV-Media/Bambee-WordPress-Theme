<?php get_header(); ?>

<?php while ( have_posts() ) : the_post() ?>
    <div class="row">
        <div class="small-12 columns">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header>
                    <h2 class="headline title">
                        <?php the_title(); ?>
                    </h2>
                </header>
                <?php the_content(); ?>
            </article>
            <!-- #post-<?php the_ID(); ?> -->
        </div>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>
