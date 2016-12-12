<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php $wpl_helper->wpl_post_thumbnail(); ?>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && $wpl_helper->wpl_categorized_blog() ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_category_list( __( ', ', 'Used between list items, there is a space after the comma.', 'wplt' ) ); ?></span>
		</div><!-- .entry-meta -->
		<?php
			endif;

			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
			<?php $wpl_helper->wpl_posted_by(); ?>

		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
            echo '<div class="post-content-box">';
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wplt' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wplt' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
            echo '</div>';
		?>
        <span class="post-format audio">
            <a class="entry-format" href="<?php echo esc_url( get_post_format_link( 'audio' ) ); ?>"><?php echo get_post_format_string( 'audio' ); ?></a>
        </span>
        <?php
        if ( 'post' == get_post_type() )
        {
        	$wpl_helper->wpl_posted_on();
        }

        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
            <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wplt' ), __( '1 Comment', 'wplt' ), __( '% Comments', 'wplt' ) ); ?></span>
        <?php endif; ?>

        <?php edit_post_link( __( 'Edit', 'wplt' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
