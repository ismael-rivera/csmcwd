<br />
    <form method="post" name="eci_finalactionsusers_form" action="">            
        <?php 
		// if speed profile import rate is set to zero warn user they must import a record
		// check if any records exist
		$recready = eci_counttablerecords($pro['current']);
		if( isset( $pro[ $pro['current'] ]['speed'] ) && $spe[ $pro[ $pro['current'] ]['speed'] ]['import'] == 0 || $recready == 0 )
		{
			eci_not(__('Project Data Required'),
			__('You must import records before you can view the post creation buttons here'));		
		}
		else
		{
			echo '<table class="widefat post fixed">';?>
            
			<tr>
				<td width="200">
					<br /><input class="button-primary" type="submit" name="eci_testuser_submit" value="Create Test User" /><br /><br />
				</td>
				<td>
					<br /><?php _e('Test your configuration before creating large amounts of user accounts');?>
				</td>
			</tr>
			<tr>
				<td>
					<br /><input class="button-primary" type="submit" name="eci_postcreation_submit" value="Run User Creation Event" /><br /><br />
				</td>
				<td>
					<br /><?php _e('Run a user creation event based on your projects speed settings');?>
				</td>
			</tr>

            <tr>
                <td>
                    <br /><input class="button-primary" type="submit" name="eci_deleteprojectusers_submit" value="Delete Projects Users" /><br /><br />
                </td>
                <td>
                    <br /><?php _e('Deletes all your projects created users and removes their ID from project table plus resets progress counters');?>
                </td>
            </tr>             
              
            </form>              
            <?php 
		}?>
        
</table>