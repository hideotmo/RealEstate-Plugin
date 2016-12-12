<?php
// Retrieve attachment metadata.
$metadata = wp_get_attachment_metadata();
/** get theme helper **/
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();

$wpl_helper->get_head();
?>
	<section id="primary" class="content-area image-attachment">
		<div id="content" class="site-content container" role="main">
		<?php
		// Start the Loop.
		while ( $wpl_helper->have_posts() ) :
		$wpl_helper->the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta">

						<span class="entry-date"><time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>

						<span class="full-size-link"><a href="<?php echo wp_get_attachment_url(); ?>"><?php echo $metadata['width']; ?> &times; <?php echo $metadata['height']; ?></a></span>

						<span class="parent-post-link"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rel="gallery"><?php echo get_the_title( $post->post_parent ); ?></a></span>
						<?php edit_post_link( __( 'Edit', 'wplt' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="entry-attachment">
						<div class="attachment">
							<?php $wpl_helper->wpl_the_attached_image(); ?>
						</div><!-- .attachment -->

						<?php if ( has_excerpt() ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
						<?php endif; ?>
					</div><!-- .entry-attachment -->

					<?php
						the_content();
						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wplt' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>

				</div><!-- .entry-content -->
			</article><!-- #post-## -->

			<nav id="image-navigation" class="navigation image-navigation">
				<div class="nav-links">
				<?php previous_image_link( false, '<div class="previous-image btn btn-info pull-left">' . __( 'Previous Image', 'wplt' ) . '</div>' ); ?>
				<?php next_image_link( false, '<div class="next-image btn btn-info pull-right">' . __( 'Next Image', 'wplt' ) . '</div>' ); ?>
				</div><!-- .nav-links -->
			</nav><!-- #image-navigation -->

			<?php $wpl_helper->comments_template(); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
$wpl_helper->get_footer();