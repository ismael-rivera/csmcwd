<?php
/*
Template Name: Contact Us
*/
?>
<?php
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
        <div id="content">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

					<?php if ( is_front_page() ) { ?>
						<h1><?php the_title(); ?></h1>
					<?php } else { ?>	
                        <div class="instruct linearBg2"><h2>Here's How You Can Reach Us</h2>
                        <p>Euismod tincidunt ut laoreet dolore magna aliquam erat volutpat ut. Nisl ut aliquip ex ea commodo consequat duis autem vel eum iriure dolor in hendrerit in. Nibh wisi enim ad minim veniam quis nostrud exerci tation ullamcorper suscipit lobortis vulputate.
</p></div>
					<?php } ?>				
						<?php echo slogan_tweet();?>
<table id="cform" width="1030" height="343" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="eform"width="610"><?php the_content(); ?></td>
    <td>&nbsp;</td>
    <td class="gmap" width="320">
    <span></span>
    <img></img>
    <p></p>
    </td>
  </tr>
</table>
<?php include "contact_info.php";?>
 
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>

				

<?php endwhile; ?>
</div>
</div>
<?php get_footer(); ?>