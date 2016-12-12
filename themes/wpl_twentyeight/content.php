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
        <?php

			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
			<?php
				if ( 'post' == get_post_type() )
                    $wpl_helper->wpl_posted_by();
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

    <?php if ( is_search() ) : ?>

        <div class="entry-content">
            <?php
            echo '<div class="post-content-box">';
            the_excerpt();
            echo '</div>';

            if ( 'post' == get_post_type() )
            {
                $wpl_helper->wpl_posted_on();
            }
            ?>
            <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wplt' ), __( '1 Comment', 'wplt' ), __( '% Comments', 'wplt' ) ); ?></span>
        </div><!-- .entry-content -->
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

        if ( 'post' == get_post_type() )
        {
        	$wpl_helper->wpl_posted_on();
        }
        ?>
        <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wplt' ), __( '1 Comment', 'wplt' ), __( '% Comments', 'wplt' ) ); ?></span>
        <?php
        edit_post_link( __( 'Edit', 'wplt' ), '<span class="edit-link">', '</span>' );
        ?>
	</div><!-- .entry-content -->
    <?php endif; ?>

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', ', ', '</span></footer>' ); ?>
</article><!-- #post-## -->