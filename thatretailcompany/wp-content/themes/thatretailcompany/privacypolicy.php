<?php
/**
 * Template Name: Privacy Policy Page
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
<div id="content" class="grid_wrapper shadow1 pages-full">
    <div class="page_header"><h2><?php the_title(); ?></h2></div>
    <div class="grid_content_fpage">
            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
                <?php endwhile; ?>
            <a target="_blank" href="http://www.freeprivacypolicy.com/" class="fpp">This policy is powered by Free Privacy Policy</a>     
       </div>                  
    </div>   <!--end content-->                 
  <?php get_footer(); ?>