<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;

$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();
$wpl_helper->get_head();

// Get Theme options
$theme_options = wpl_theme::get_wpl_theme_options();

if(wpl_theme::getVar('wplview'))
{
?>
<section id="main_box_container">

    <section id="main_box" class="clearfix container">
        <div id="content">
            <?php 
            if ($theme_options['page_title'] == 1 ){
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
    <section id="main_box" class="clearfix container page_container">
    <div id="content" class="clearfix">
        <div class="content_container clearfix">
            <div id="content_l" class="col-md-8 single">
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();

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

                            <div class="entry-meta">
                                <?php
                                if($theme_options['show_author'] == '2'){
                                    if ( 'post' == get_post_type() )
                                    {
                                        $wpl_helper->wpl_posted_by();
                                    }
                                }

                                if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
                                ?>
                            </div>
                            <div class="entry-meta-details">
                            <span class="post-format <?php echo get_post_format(); ?>">
                                <?php echo get_post_format(); ?>
                            </span>
                                <?php
                                if ( 'post' == get_post_type() )
                                {
                                    if($theme_options['show_date'] == '2'){
                                        $wpl_helper->wpl_posted_on();
                                    }
                                }
                                ?>
                                <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wplt' ), __( '1 Comment', 'wplt' ), __( '% Comments', 'wplt' ) ); ?></span>
                                <?php
                                endif;

                                edit_post_link( __( 'Edit', 'wplt' ), '<span class="edit-link">', '</span>' ); ?>
                            </div>
                            <!-- .entry-meta -->
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
                    // Previous/next post navigation.
                    if($theme_options['show_navigation'] == '2'){
                        $wpl_helper->wpl_post_nav();
                    }

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() )
                    {
                        $wpl_helper->comments_template();
                    }
                endwhile;
                ?>
            </div>
            <aside id="right_col" class="col-md-4">
                <?php $wpl_helper->get_sidebar('sidebar-2'); ?>
            </aside>
        </div>
        <div id="main_widget_area" class="main_widget_area widget">
            <?php $wpl_helper->get_sidebar('sidebar-1'); ?>
        </div>
    </div>

</section>
</section>
<?php
}
$wpl_helper->get_footer();