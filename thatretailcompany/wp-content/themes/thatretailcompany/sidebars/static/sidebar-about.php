<?php
/**
 * The About Sidebar Static widget areas.
 *
 * @package WordPress
 * @subpackage thatretailcompany
 * @since thatretailcompany 1.0
 */
?>
<div class="grid_R_wrap_top"></div><!--grid_R_wrap_top-->
<div class="grid_R_wrap_mid">
<table class="sidebar-right">
	<tr>
		<td class="promotiles">
            <div class="promotiles_inner">
                    <h3>Make a Booking Request</h3>
                    <div class="promo_tile_content">
                    <p>For general enquires, booking requests or a cost estimate, please contact us today.</p>
                    <a class="promo_tile_button" href="<?php echo get_page_link(18); ?>">Contact Us</a>
                    </div>
                    <div class="promo_tile_icon"><img src="<?php bloginfo('template_url'); ?>/images/promo_tiles_bookrequest.png" width="135" height="120" alt="24/7 Customer Service" /></div>
                    </div>
        </td>
     </tr>
     <tr>
        <td id="rowspace1" colspan="5">&nbsp;</td>
     </tr>
     <tr>
		<td class="promotiles">
            <?php echo testimonials_widget(); ?>
        </td>
     </tr>
</table>
</div>
<div class="grid_R_wrap_bot"></div><!--grid_R_wrap_bot-->