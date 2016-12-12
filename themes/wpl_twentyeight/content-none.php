<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();
?>
<div class="container">
	<h1 class="page-title nothing-found"><?php _e( 'Nothing Found', 'wplt' ); ?></h1>
	<div class="page-content nothing-found">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wplt' ), admin_url( 'post-new.php' ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wplt' ); ?></p>
		<?php get_search_form(); ?>

		<?php else : ?>

		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wplt' ); ?></p>
		<?php get_search_form(); ?>

		<?php endif;

		$wpl_helper->get_menu(array(
			'theme_location'    =>'404-menu',
			'menu_class'        =>'search-not-found'
		));
		?>
	</div><!-- .page-content -->
</div>