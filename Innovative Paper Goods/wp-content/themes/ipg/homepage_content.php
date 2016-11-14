<?php
/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	/*if (   ! is_active_sidebar( 'left-widget-area'  )
		&& ! is_active_sidebar( 'center-widget-area' )
		&& ! is_active_sidebar( 'right-widget-area'  )
	)
		return;*/
	// If we get this far, we have widgets. Let do this.
?>
<!--If no widgets are active the table below will dissappear. If you wish to avoid this, then remove or comment out the above php code that checks for widgets. Otherwise you should leave this as is.-->
<table class="homepage_tbl" border="0" cellspacing="20" cellpadding="0">
  <tr>
    <td class="block">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="block_title"><h3>News & Events</h3></td>
        </tr>
        <tr><td class="block_content">
        	<?php
			$query = 'category_name=Announcements';
                $queryObject = new WP_Query($query);
if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						$size = array(280,90);
						the_post_thumbnail($size, $attr); ?>
						<div class='block_offers'>
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><br/>
                        <?php $posttext= $post->post_content; ?>
                        <?php //echo mb_substr($posttext , 0, 200);
						echo '<p>' . trim_text($posttext, 200, false) . '</p>';
						?>
						</div>			 
                  <?php  }
                }
?></td>
    	</tr>
        <tr><td class="block_footer"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">Continue reading..</a></td></tr>  
        </table>   
    </td>
    <td>&nbsp;</td>
    <td class="block">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr><td class="block_title"><h3>Special Offers</h3></td></tr>
         <tr><td class="block_content">
			   <?php 
               $query = 'post_type=special_offers';
                $queryObject = new WP_Query($query);
                // The Loop...
                if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						echo "<div class='block_offers'>";
						$posttext = $post->post_content;
                        echo the_title() . "</n>" . '<p>' . trim_text($posttext, 70) . '</p>'; 
						echo "</div>";			 
                    }
                }
                ?> 
                </td></tr>
           <tr><td class="block_footer"></td></tr>     
        </table>   
    </td>
    <td>&nbsp;</td>
    <td class="block">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr><td class="block_title"><h3>What Our Customers Say</h3></td></tr>
        <tr><td class="block_content"> 
			   <?php 
               $query = 'post_type=testimonials';
                $queryObject = new WP_Query($query);
                // The Loop...
                if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						echo "<div class='block_testimonials'>";
                        echo the_title() . "</n>" . limited_content(10);
						echo "</div>";			 
                    }
                }
                ?>
                </td></tr>
                <tr><td class="block_footer"></td></tr> 
        </table>   
    </td>
  </tr>
</table>
