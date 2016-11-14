<?php
/**
 * Template Name: Services
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
                        <div class="grid_L_services">
                    	<?php query_posts('category_name=services&meta_key=services_post_order&orderby=meta_value&order=ASC'); ?>
                            <div id="accordion">                
                            <?php if ( have_posts() ) while (have_posts()) : the_post(); ?>
                                <h3 id="<?php $str = strtolower(str_replace(' ','', get_the_title($post->post_parent))); 
                                              $newString = preg_replace("/&.{0,}?;/",'',$str);
                                              echo $newString;?>" 
                                 class="nav">
                                 <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                 </h3>
                                 <div class="services_content"><?php the_content(); ?></div>
                            <?php endwhile;?>
                            </div><!-- End Accordion -->    
                        </div><!-- grid_L_services -->
            		</td>
                    <td class="grid_R">
					<?php get_trc_sidebar('widget','services');?> 
                    </td>
                  </tr>
                  <?php get_fade_graphic(); ?> 
             </table>                             
    </div>   <!--end content-->                    
  <?php get_footer(); ?> 