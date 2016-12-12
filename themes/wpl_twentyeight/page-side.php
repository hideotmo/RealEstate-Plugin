<?php
/*
Template Name: Simple Page
*/
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;

$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();
$wpl_helper->get_head();

if(wpl_theme::getVar('wplview'))
{
    ?>
<section id="main_box_container" class="simple_page">
    <section id="main_box" class="clearfix container">
        <div id="content">
            <?php 
            if (isset($theme_options['page_title']) and $theme_options['page_title'] == 1 ){
                echo '<div class="page container">';
                if ( is_single() ) :
                    the_title( '<h1 class="entry-title">', '</h1>' );
                else :
                    the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
                endif;
                echo '</div>';
            }
            if(!$wpl_helper->have_posts()): ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'wplt'); ?></p>
            <?php
            endif;
            while($wpl_helper->have_posts())
            {
                $wpl_helper->the_post();
                $wpl_helper->the_content();
            }
            ?>
        </div>
    </section>
</section>
<?php
}
else
{
?>
<section id="main_box_container">
    <section id="main_box" class="clearfix container page_container simple_page">
        <div id="content" class="clearfix">
            <div id="content_single" class="single">
                <?php
                // Start the Loop.
                while ( have_posts() ) :
                    the_post();

                    /*
                    * Include the post format-specific template for the content. If you want to
                    * use this in a child theme, then include a file called called content-___.php
                    * (where ___ is the post format) and that will be used instead.
                    */
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                        <?php $wpl_helper->wpl_post_thumbnail(); ?>

                        <header class="entry-header">
                            <?php

                            if ( is_single() ) :
                                the_title( '<h1 class="entry-title">', '</h1>' );
                            else :
                                the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
                            endif;
                            ?>

                        </header><!-- .entry-header -->

                        <?php if ( is_search() ) : ?>
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div><!-- .entry-summary -->
                        <?php else : ?>
                            <div class="entry-content">
                                <?php
                                echo '<div class="post-content-box">';
                                the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wplt' ) );
                                wp_link_pages( array(
                                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wplt' ) . '</span>',
                                    'after'       => '</div>',
                                    'link_before' => '',
                                    'link_after'  => '',
                                ) );
                                echo '</div>';
                                ?>
                            </div><!-- .entry-content -->
                        <?php endif; ?>
                        <?php the_tags( '<footer class="entry-meta"><span class="tag-links">', ', ', '</span></footer>' ); ?>
                    </article><!-- #post-## -->

                    <?php

                endwhile;
                ?>
            </div>
        </div>

    </section>
</section>
<?php
}
$wpl_helper->get_footer();