<?php
/*
Template Name: Products Page
*/
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>
<div id="content_wrapper" class="grid">
<div class="intro drop-shadow lifted">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

					<?php if ( is_front_page() ) { ?>
						<h2><?php the_title(); ?></h2>
					<?php } else { ?>	
						<h1><?php the_title(); ?></h1>
					<?php } ?>				

						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>				

<?php endwhile; ?>
</div>
<?php echo slogan_tweet();?>
<table class="products_page" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> 			
<?php echo (do_shortcode('[dpsc_grid_display category="14" total="12" column="3" per_page="6" type="duka" order="ASC"]')); ?>
	</td>
</tr>
</table>
</div>
<?php get_footer(); ?>