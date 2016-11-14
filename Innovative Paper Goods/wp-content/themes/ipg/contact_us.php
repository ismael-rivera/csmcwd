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
		<div class="intro drop-shadow lifted">
            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
          			<?php if ( is_front_page() ) { ?>
            	<h2><?php the_title(); ?></h2>
					<?php } else {
					the_content();
					?>					
<?php } ?>	
             </div>	
        <?php echo slogan_tweet();?>     						
        <div id="content">				
<table id="cform_block" class="grid" height="343" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="block_title eform"><h3><?php the_title(); ?></h3></td>
<td></td>
<td class="block_title eform"><h3>How To Get Our Offices</h3></td>
</tr>
  <tr>
    <td class="eform"width="610">
    <?php 			
	$post_id=get_the_ID();	 
	$custom_fields = get_post_custom($post_id);//Current post id
  	$my_custom_field = $custom_fields['contact_form_text'];//key name
  	echo '<p>' . $my_custom_field[0] . '</p>';
	echo do_shortcode('[contact-form 1 "Contact form 1"]');
	?>
    </td>
    <td>&nbsp;</td>
    <td class="gmap" width="320">
    <?php echo do_shortcode('[SGM lat="49.117499" lng="-122.812064" zoom="14" type="ROADMAP" directionsto="Surrey, BC V3S 3C4, Canada"]'); ?>
    </td>
  </tr>
</table>
 
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>

				

<?php endwhile; ?>
</div>
<?php include ABSPATH . "contact_info.php";
?>
<?php get_footer(); ?>