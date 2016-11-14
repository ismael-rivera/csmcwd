<?php
// prepare checkbox values
if( isset( $csv['conditions']['switches']['valueswap'] ) && $csv['conditions']['switches']['valueswap'] == true ){ $vsc = 'checked="checked"'; }else{ $vsc = ''; }
if( isset( $csv['conditions']['switches']['catdesign'] ) && $csv['conditions']['switches']['catdesign'] == true ){ $cdc = 'checked="checked"'; }else{ $cdc = ''; }
if( isset( $csv['conditions']['switches']['valdesign'] ) && $csv['conditions']['switches']['valdesign'] == true ){ $vdc = 'checked="checked"'; }else{ $vdc = ''; }
if( isset( $csv['conditions']['switches']['numericmod'] ) && $csv['conditions']['switches']['numericmod'] == true ){ $nmc = 'checked="checked"'; }else{ $nmc = ''; }
if( isset( $csv['conditions']['switches']['posttypedesign'] ) && $csv['conditions']['switches']['posttypedesign'] == true ){ $ptd = 'checked="checked"'; }else{ $ptd = ''; }
?>
<br />
<h4>Check To Active</h4>
<br />
<form method="post" name="eci_conditions_form" action="">
	<table class="widefat post fixed">
    	<tr>
        	<td><label><input type="checkbox" name="valueswap" value="1" <?php echo $vsc; ?>>Value Swap</label></td>
        	<td><label><input type="checkbox" name="catdesign" value="1" <?php echo $cdc; ?>>Category Design</label></td>
        	<td><label><input type="checkbox" name="valdesign" value="1" <?php echo $vdc; ?>>Value Design</label></td>
        </tr>  
    	<tr>
        	<td><label><input type="checkbox" name="numericmod" value="1" <?php echo $nmc; ?>>Numeric Modifier</label></td>
        	<td><label><input type="checkbox" name="posttypedesign" value="1" <?php echo $ptd; ?>>Post Type Design</label></td>
        	<td></td>
        </tr>  	
    </table>
    <br />
    <input class="button-primary" type="submit" name="eci_conditions_submit" value="Submit" />
</form> 
<br /><br />

<?php
if( isset( $csv['conditions']['switches']['valueswap'] ) && $csv['conditions']['switches']['valueswap'] == true )
{?>
    <div id="dashboard_recent_comments" class="postbox closed" >
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class='hndle'><span><?php _e('Value Swap');?></span></h3>
        <div class="inside">
            <form method="post" name="eci_valueswap_form" action=""><br />          
                <?php _e('Select Data');?>: <?php eci_conditionstypes( $pro['current'] );?>
                <br /><br />
                <?php _e('Enter Value To Be Replaced');?>: <input name="eci_oldvalue_submit" type="text" value="" size="20" maxlength="100" />
                <br /><br />            	
                <?php _e('Enter The New Value');?>: <input name="eci_newvalue_submit" type="text" value="" size="20" maxlength="100" />
                <br />
                <input class="button-primary" type="submit" name="eci_valueswap_submit" value="Save" />
            </form>  
			
			<br />
			<h4>Your Current Conditions</h4>
			<form method="post" name="eci_currentconditions_form" action="">
				<table class="widefat post fixed">
					<tr>
						<td><strong>Column</strong></td><td><strong>Old Value</strong></td><td><strong>New Value</strong></td><td></td>
					</tr>			
					<?php 
					$conditions_total = 0;
					if(isset($csv['conditions']['valueswap'][0]))
					{
						foreach($csv['conditions']['valueswap'] as $key=>$con)
						{
							echo '
							<tr>
								<td>'.$con['type'].'</td>
								<td>'.$con['old'].'</td>
								<td>'.$con['new'].'</td>
								<td>'.eci_conditionsdelete_button('valueswap',$key,$con['type']).'</td>
							</tr>';
							++$conditions_total;
						}
					}
					
					if($conditions_total == 0)
					{
						echo '
						<tr>
							<td>None Saved</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';						
					}
					?>
				</table>   
			</form>           
        </div>
    </div><?php
}
?>

<?php
if( isset( $csv['conditions']['switches']['catdesign'] ) && $csv['conditions']['switches']['catdesign'] == true )
{?>
    <div id="dashboard_recent_comments" class="postbox closed" >
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class='hndle'><span><?php _e('Category Design');?></span></h3>
        <div class="inside">
        	<form method="post" name="eci_categorydesign_form" action="">                
                <p><?php _e('You may need to create your categories early to use this feature properly. Press the Create Categories Now button in the
                Categories panel. The categories will then become available for assigning design conditions.');?></p>
                <?php eci_listcategorydesigns( $csv );?>  <br />
                <input class="button-primary" type="submit" name="eci_categorydesign_submit" value="Save" />
            </form>            
        </div>
    </div><?php
}
?>

<?php
if( isset( $csv['conditions']['switches']['valdesign'] ) && $csv['conditions']['switches']['valdesign'] == true )
{?>
    <div id="dashboard_recent_comments" class="postbox closed" >
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class='hndle'><span><?php _e('Value Design');?></span></h3>
        <div class="inside">
        	<form method="post" name="eci_valuedesigncolumn_form" action="">                
                <p><?php _e('Please see the WebTechGlobal blog or forum for more information on how to use this feature.');?></p>
                <label>Column: <?php eci_csvcolumnmenu_valdesign( $pro['current'], $csv, $pro );?></label>
                <input class="button-primary" type="submit" name="eci_valuedesigncolumn_submit" value="Change" />
            </form>    
            <form method="post" name="eci_valuedesignconfig_form" action="">    
                <?php eci_listvaluedesigns( $pro['current'] );?>  <br />
                <input class="button-primary" type="submit" name="eci_valuedesignconfig_submit" value="Save" />
            </form>            
        </div>
    </div><?php
}
?>

<?php
if( isset( $csv['conditions']['switches']['numericmod'] ) && $csv['conditions']['switches']['numericmod'] == true )
{?>
    <div id="dashboard_recent_comments" class="postbox closed" >
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class='hndle'><span><?php _e('Numeric Modifier');?></span></h3>
        <div class="inside">
        	<form method="post" name="eci_numericmod_form" action="">                
                <p><?php _e('Please see the WebTechGlobal blog or forum for more information on how to use this feature.');?></p>
                Modifier: <input name="eci_modifier" type="text" value="<?php if(isset($csv['conditions']['numericmod']['modifier'])){echo $csv['conditions']['numericmod']['modifier'];}?>" size="8" maxlength="8" />
          		<br />
                <label>Math Symbol 1: <?php eci_numericmodifiers( 1, $pro['current']);?></label>
             	<br />
             	<label>Modified Column: <?php eci_csvcolumnmenu_standard( $pro['current'], '1', 'eci_numericmod_form', $pro );?></label>
                <br />
                <input class="button-primary" type="submit" name="eci_numericmod_submit" value="Save" />
            </form>              
        </div>
    </div>		
<?php 
}
?>

<?php
if( isset( $csv['conditions']['switches']['posttypedesign'] ) && $csv['conditions']['switches']['posttypedesign'] == true )
{?>
    <div id="dashboard_recent_comments" class="postbox closed" >
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class='hndle'><span><?php _e('Post Type Design');?></span></h3>
        <div class="inside">               
        	<br />
			<?php eci_posttypedesigns_form($pro['current']);?>         
        </div>
    </div><?php
}
?>
