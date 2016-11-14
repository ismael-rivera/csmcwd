<?php
/**
 * Template Name: Testimonials
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
<div id="content" class="grid_wrapper shadow1 pages">
<div class="page_header"><h2><?php the_title(); ?></h2></div>
<table>
                  <tr>
                    <td class="grid_L">
            <div class="grid_L_contact">       
            
                <?php comments_template('/comments-testimonials.php'); ?>                          
                 
            
            </div><!-- End grid_L_contact -->
          
            
            </td> <!-- End grid_L --> 
            <td class="grid_R"><?php get_trc_sidebar('widget','testimonials');?></td>
            </tr>
            <?php get_fade_graphic(); ?>                   
</table>
   
</div><!--end content-->                     
<?php get_footer(); ?> 
  