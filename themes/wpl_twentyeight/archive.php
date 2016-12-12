<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();
$wpl_helper->get_head();

if(wpl_theme::getVar('wplview') or $wpl_helper->is_front_page())
{
?>
<section id="main_box_container">
	<section id="main_box" class="clearfix">
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
			if(!$wpl_helper->have_posts())
			{
			?>
			<p><?php _e('Sorry, no posts matched your criteria.', 'wplt'); ?></p>
			<?php
			}

			while($wpl_helper->have_posts())
			{
				$wpl_helper->the_post(); $wpl_helper->the_content();
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
		<h1><?php $wpl_helper->the_title(); ?></h1>
		<div id="content" class="clearfix">
			<div class="content_container clearfix">
				<div id="content_l" class="col-md-8">
					<?php if(!$wpl_helper->have_posts())
					{
						?>
						<p><?php _e('Sorry, no posts matched your criteria.', 'wplt'); ?></p>
					<?php
					}
					while($wpl_helper->have_posts())
					{
						$wpl_helper->the_post(); ?>
						<div class="page_content"><?php $wpl_helper->the_content(); ?></div>
					<?php
					}
					$wpl_helper->comments_template();
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