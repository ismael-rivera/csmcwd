<?php
/*
Template Name: Checkout
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
                    	
             </div>
             <?php echo slogan_tweet();?>
            <div id="content">			
				<div class="checkout"><?php the_content(); ?></div>					

<?php endwhile; ?>
	</div>
<?php include "contact_info.php";?>    
</div>
<?php get_footer(); ?>