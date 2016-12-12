<?php
/* 
Template Name: Archives2
*/
get_header(); ?>

<div id="primary" class="site-content">
<div id="content" role="main">

<?php while ( have_posts() ) : the_post(); ?>
				
<h1 class="entry-title"><?php the_title(); ?></h1>

<div class="entry-content">

<?php the_content(); ?>

<!-- Custom Archives Functions Go Below this line -->


<h1>Pages</h1>
<?php wp_list_pages( 'title_li=' ); ?>

<h1>Recent Posts</h1>
<?php wp_get_archives('type=postbypost&limit=10'); ?>

<h1>Authors</h1>
<?php wp_list_authors( 'exclude_admin=0&optioncount=1' ); ?>

<!-- Custom Archives Functions Go Above this line -->

</div><!-- .entry-content -->

<?php endwhile; // end of the loop. ?>

</div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>