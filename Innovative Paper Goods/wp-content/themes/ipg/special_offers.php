<?php
/*
Template Name: Special Offers
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
				<table width="960" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td><h3>Special Offers</h3></td></tr>
         <tr><td class="offers" valign="top">
			   <?php 
               $query = 'post_type=special_offers';
                $queryObject = new WP_Query($query);
                // The Loop...
                if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						echo "<div>";
						$posttext = $post->post_content;
                        echo the_title() . "</n>" . '<p>' . $posttext . '</p>'; 
						echo "</div>";			 
                    }
                }
                ?> 
                </td>
                <td><?php get_sidebar(); ?></td>
                </tr>     
        </table>
 </div>
<?php get_footer(); ?>