<?php
/**
 * Template Name: About
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Ishygrids
 * @since Ishygrids 1.0
 */

get_header(); ?>
<!--[if IE]>
<div id="content" class="grid_wrapper pages">
<![endif]-->
<!--[if !IE]>-->
<div id="content" class="grid_wrapper shadow1 pages">
<!--<![endif]--> 
    <div class="page_header"><h2><?php the_title(); ?></h2></div>
<table>
                  <tr>
                    <td class="grid_L">
                            <div id="about_grid_L_column">
                                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                                <?php the_content(); ?>
                                <?php endwhile; ?>
                            </div><!-- about_grid_L_column -->  
          			 </td> <!-- End grid_L --> 
            		<td class="grid_R">
                          		<?php get_trc_sidebar('widget','about');?>          
            		</td>
            	</tr> 
               <?php get_fade_graphic(); ?>                  
</table>      
</div><!--end content-->
<?php get_footer(); ?> 