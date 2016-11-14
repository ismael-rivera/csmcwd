<?php
/*
Template Name: Home
*/
?>
<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */
?>
<?php get_header(); ?>
<div id="slider_wrapper" class="grid"><?php include (ABSPATH . '/wp-content/plugins/wp-featured-content-slider/content-slider.php'); ?></div>
<?php echo slogan_tweet();?>
<div id="content_wrapper" class="grid">			
        <div id="content">
        	<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 include ("homepage_content.php");
			?>
        </div>

</div>
<?php get_footer(); ?>