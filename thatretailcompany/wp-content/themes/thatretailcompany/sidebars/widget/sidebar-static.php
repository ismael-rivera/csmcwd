<?php
/**
 * The Sidebar Static widget areas.
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
            <?php dynamic_sidebar( 'home-promo-tiles-area-left' ) ?>
        </div>
        </td>
     </tr>
     <tr>
        <td id="rowspace1" colspan="5">&nbsp;</td>
     </tr>
     <tr>
		<td class="promotiles">
            <div class="promotiles_inner">
            <?php dynamic_sidebar( 'home-promo-tiles-area-left' ) ?>
        </div>
        </td>
     </tr>
</table>
</div>
<div class="grid_R_wrap_bot"></div><!--grid_R_wrap_bot-->