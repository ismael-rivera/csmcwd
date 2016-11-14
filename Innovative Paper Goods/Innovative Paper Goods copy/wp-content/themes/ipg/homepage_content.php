<style type="text/css">
.event{
	width: 320px;
	height: 345px;
	background-color: #FFF;
	border: 1px solid #dbdbdb;
}
</style>
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
    <td class="event" width="320">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="52"><h3>News & Events</h3></td></tr> 
		<tr><td><?php the_post_thumbnail();?></td></tr> 
        </table>   
    </td>
    <td>&nbsp;</td>
    <td class="event" width="320">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="52"><h3>Special Offers</h3></td></tr> 
			   <?php 
               $query = 'post_type=special_offers';
                $queryObject = new WP_Query($query);
                // The Loop...
                if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						echo "<tr><td height='82'>";
                        echo the_title() . "<br/><br/>" . limited_content(10);
						echo "</td></tr>";			 
                    }
                }
                ?> 
        </table>   
    </td>
    <td>&nbsp;</td>
    <td class="event" width="320">
        <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="52"><h3>What Our Customers Say</h3></td></tr> 
			   <?php 
               $query = 'post_type=testimonials';
                $queryObject = new WP_Query($query);
                // The Loop...
                if ($queryObject->have_posts()) {
                    while ($queryObject->have_posts()) {
                        $queryObject->the_post();
						echo "<tr><td height='82'>";
                        echo the_title() . "<br/><br/>" . limited_content(10);
						echo "</td></tr>";			 
                    }
                }
                ?> 
        </table>   
    </td>
  </tr>
</table>
