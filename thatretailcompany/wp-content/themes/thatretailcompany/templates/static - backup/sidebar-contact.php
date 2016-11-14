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
                    <h3>Need a Purchase Order Form?</h3>
                    <div class="promo_tile_content">
                    <p>Don't have your own purchase order form? 
No worries, you can download one here!</p>
                    <a class="promo_tile_button" href="<?php bloginfo('url'); ?>/wp-content/uploads/TRC_PurchaseOrderForm.pdf">Download</a>
                    </div>
                    <div class="promo_tile_icon"><img src="<?php bloginfo('template_url'); ?>/images/promo_tiles_orderform.jpg" width="135" height="120" alt="24/7 Customer Service" /></div>
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