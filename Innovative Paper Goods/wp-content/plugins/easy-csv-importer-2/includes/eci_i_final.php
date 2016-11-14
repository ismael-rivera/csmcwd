<br />
    <form method="post" name="eci_finalactionsposts_form" action="">            
        <?php 
		// if speed profile import rate is set to zero warn user they must import a record
		// check if any records exist
		$recready = eci_counttablerecords($pro['current']);
		if( isset( $pro[ $pro['current'] ]['speed'] ) && $spe[ $pro[ $pro['current'] ]['speed'] ]['import'] == 0 || $recready == 0 )
		{
			eci_not(__('Project Data Required'),
			__('You must import records before you can view the post creation buttons here'));		
		}
		elseif( !isset( $csv['maindesign'] ) )
		{
			eci_not(__('Default Project Design Required'),
			__('Please go to the Project Options panel on the Configuration page and save a default design.'));
		}
		else
		{		
			echo '<table class="widefat post fixed">';?>
            
			<tr>
				<td width="200">
					<br /><input class="button-primary" type="submit" name="eci_postcreation_submit" value="Run Post Creation Event" /><br /><br />
				</td>
				<td>
					<br /><?php _e('Create 2000 posts in a post creation event');?>
				</td>
			</tr>
            
            <tr>
                <td>
                    <br /><input class="button-primary" type="submit" name="eci_publishall_submit" value="Publish All" /><br /><br />
                </td>
                <td>
                    <br /><?php _e('If you created drafts or pending posts you can publish them quickly by using this button. Currently processes a maximum of 500 posts per use');?>
                </td>
            </tr>             
            
            <?php if( isset($pro[$pro['current']]['rowsinsertfail']) && $pro[$pro['current']]['rowsinsertfail'] > 0 || isset($pro[$pro['current']]['rowsupdatefail']) && $pro[$pro['current']]['rowsupdatefail'] > 0 ){?>
            <tr>
                <td>
                    <br /><input class="button-primary" type="submit" name="eci_resetfails_submit" value="Force Re-Try" /><br /><br />
                </td>
                <td>
                    <br /><?php echo 'You currently have failed data updates or inserts. You can reset the counters for these and try again. Sometimes failures are one off occurences due to project configuration or other plugin activity within the blog';?>
                </td>
            </tr>         
            <?php }?>        
              
            </form>              
            <?php 
		}?>
        
</table>