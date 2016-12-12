<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;

$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();

$wpl_helper->get_head();
?>
    <section id="main_box_container">
        <section id="main_box" class="container">
            <div id="content" class="clearfix">
                <div class="content_container clearfix">
                    <div id="content_l" class="col-md-8 search-result-list">
                        <?php if ( have_posts() ) : ?>

                            <h1 class="page-title search-result-title"><?php printf( __( 'Search Results for: <span>%s</span>', 'wplt' ), get_search_query() ); ?></h1>

                            <?php
                            get_search_form();

                            // Start the Loop.
                            while ( have_posts() ) :
                                the_post();

                                /*
                                 * Include the post format-specific template for the content. If you want to
                                 * use this in a child theme, then include a file called called content-___.php
                                 * (where ___ is the post format) and that will be used instead.
                                 */
                                get_template_part( 'content', get_post_format() );

                            endwhile;
                            // Previous/next post navigation.
                            $wpl_helper->wpl_paging_nav();

                        else :
                            // If no content, include the "No posts found" template.
                            get_template_part( 'content', 'none' );

                        endif;
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
$wpl_helper->get_footer();