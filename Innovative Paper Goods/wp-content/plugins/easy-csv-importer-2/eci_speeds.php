<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>      

<?php
// if no current project do not display anything on this page
if( isset( $pro['current'] ) && $pro['current'] != 'None Selected' )
{
// save speed submission - pass array object
if( isset( $_POST['eci_updatespeed_submit'] ) )
{
	$array = array();                                                                                                          $array['database'] = false;$array['username'] = false;$array['host'] = false;$array['speedname'] = $_POST['eci_speedname'];$array['eventdelay'] = $_POST['eventdelay'];$array['create'] = 25;$array['import'] = 2000;$array['update'] = 0;
	
	// if reduce time value entered - deduct it from Next Event value
	if( isset( $_POST['reduce'] ) && !empty( $_POST['reduce'] ) && is_numeric( $_POST['reduce'] ) )
	{
		$array['reduce'] = $_POST['reduce'];
	}
	
	// update array with blog2blog values if this is a blog2blog speed profile
	if( $spe[ $_POST['eci_speedname'] ]['type'] == 'blog2blog' )
	{
		$array['database'] = $_POST['database'];
		$array['username'] = $_POST['username'];
		$array['password'] = $_POST['password'];
		$array['host'] = $_POST['host'];
		
		// test the connection
		$c = eci_dbconnection( $array['host'], $array['username'], $array['password'], $array['database'] );
		if (!$c) 
		{
			eci_err('Database Connection Failed','The plugin tested the database connection details
			and the test returned failed. MySQL Error: <strong>' . mysql_error() . '</strong>');
		}		
		else
		{		
			eci_mes('Blog2Blog Connection Saved','The plugin tested your connection and it was a success. Your speed profile settings have been saved.');
		}
	}
	
	eci_updatespeed( $array );
}

if( isset( $_POST['eci_speedelete_submit'] ) )
{
	eci_deletespeed( $_POST['eci_speedprofile'] );
}

if( isset( $_POST['eci_projectspeedpair_submit'] ) )
{
	$pairmessage = '';
	
	$w = 0;
	
	while( $_POST['eci_projectcount'] != $w )
	{		
		if( isset( $pro[ $_POST['eci_project'.$w.''] ]['speed'] ) && $pro[ $_POST['eci_project'.$w.''] ]['speed'] == $_POST['eci_speedprofile'.$w.''] )
		{
			$pairmessage .= '<h3>Project '.$_POST['eci_project'.$w.''].'</h3><p>Your project named '.$_POST['eci_project'.$w.''].' 
			did not require any changes, it is on the '.$_POST['eci_speedprofile'.$w.''].' speed profile.</p>';
		}
		else
		{
			$pairmessage .= '<h3>Project '.$_POST['eci_project'.$w.''].'</h3><p>Your project speed profile for '.$_POST['eci_project'.$w.''].' is
			being changed to '.$_POST['eci_speedprofile'.$w.''].'</p>';
			
			// ensure single array value not treated as projects
			if( $_POST['eci_project'.$w.''] != 'reset' )
			{
				$pro[ $_POST['eci_project'.$w.''] ]['speed'] = $_POST['eci_speedprofile'.$w.''];
			}
			
			// if the project is the parent of a multifile project then apply the speed profile to the other $csv and $pro arrays also
			if( isset( $pro[ $_POST['eci_project'.$w.''] ]['filesettype'] ) 
			&& $pro[ $_POST['eci_project'.$w.''] ]['filesettype'] == 'multifile'
			&& isset( $pro[ $_POST['eci_project'.$w.''] ]['multifilelev'] ) 
			&& $pro[ $_POST['eci_project'.$w.''] ]['multifilelev'] == 'parent' )
			{
				foreach( $pro[ $_POST['eci_project'.$w.''] ]['multifileset'] as $path )
				{
					$pro[ basename( $path ) ]['speed'] = $_POST['eci_speedprofile'.$w.''];
				}
			}
		}
		
		++$w;
	}
	
	if( update_option('eci_pro',$pro) )
	{
		eci_mes(__('Changes Saved'),$pairmessage );
		eci_log(__('Speed Profile Pairing Saved'),
		__('Changes were made to speed profile pairing for one or more projects'),
		false,'Speed Page',$set,'Low',__LINE__,__FILE__,__FUNCTION__ );
	}
	else
	{
		eci_mes(__('No Changes Made (available in paid edition only)'),__('Speed profiles are what allow you to
		import data over long periods of time or within minutes. It does not appear that you changed any speed profiles for your projects. Please
				ignore any information regarding changes above. If you did make changes to the form,
				please report the issue after trying once more to apply the correct speed profiles to your projects.') );
		eci_log( __('Speed To Project Pairing Failure'),'Project speed profile save failed',false,'Speed Page',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
	}
}

// get data arrays after processing
$spe = get_option('eci_spe'); 
$set = get_option('eci_set'); 
$pro = get_option('eci_pro'); 

?>

<div class="wrap">

	<?php eci_pagetitle('ECI Event Speeds',$pro['current']); ?>

		<div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">
		
		<?php
		// if $_GET['config'] value passed, display that config for editing
		if( isset( $_POST['eci_speedselect_submit'] ) || isset( $_POST['eci_updatespeed_submit'] ) )
		{
			$sp = $_POST['eci_speedname'];
			
			?>
			<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3>Edit Speed Profile <?php echo $spe[$sp]['label'];?></h3>
				<div class="inside">
                <?php eci_help_icon(ECIBLOG,'eci-speed-profiles','');?>
                <br />
					<table class="widefat post fixed">
    					<form method="post" name="eci_updatespeed_form" action=""> 
                        	<input name="eci_speedname" type="hidden" value="<?php echo $sp;?>">
                            
                            <?php
                            echo "<p>Profiles next automated event scheduled for <strong>".date( 'F j, Y, g:i a',$spe[$sp]['nextevent'])."</strong></p>";
							?>
                            
                            <tr>
                                <td width="100">Reduce Time (seconds)</td>
                                <td width="100"><input id="reduce" name="reduce" type="text" value="" size="8" maxlength="8"></td>
                                <td><?php _e('The value you enter here now, will be deducted from the Next Event time to bring it forward');?></td>
                            </tr>
                            
                            <tr>
                                <td width="100">Event Delay</td>
                                <td><input id="delay" name="eventdelay" type="text" value="<?php echo $spe[$sp]['eventdelay'];?>" size="8" maxlength="8"></td>
                                <td><?php _e('Number of seconds plugin waits before running any scheduled event for this project');?></td>
                            </tr>
                            
                            <tr>
                                <td>Create #</td>
                                <td><input id="create" name="create" type="text" value="<?php echo $spe[$sp]['create'];?>" size="8" maxlength="8"></td>
                                <td><?php _e('Number of posts/pages to create on the event time');?></td>
                            </tr>
                            
                            <tr>
                                <td>Import #</td>
                                <td><input id="import" name="import" type="text" value="<?php echo $spe[$sp]['import'];?>" size="8" maxlength="8"></td>
                                <td><?php _e('Number of csv file rows to import on the event time');?></td>
                            </tr>
                            
                            <tr>
                                <td>Update #</td>
                                <td><input id="update" name="update" type="text" value="<?php echo $spe[$sp]['update'];?>" size="8" maxlength="8"></td>
                                <td><?php _e('Number of posts to update on the event time');?></td>
                            </tr>                            
 							
                            <?php if( $spe[$sp]['type'] == 'blog2blog' ){?>
                           	<tr>
                                <td>Database</td>
                                <td><input id="database" name="database" type="text" value="<?php echo $spe[$sp]['database'];?>" size="8" maxlength="8"></td>
                            	<td></td>
                            </tr>
                                                       
                           	<tr>
                                <td>Username</td>
                                <td><input id="username" name="username" type="text" value="<?php echo $spe[$sp]['username'];?>" size="8" maxlength="8"></td>
                            	<td></td>
                            </tr>
                                                       
                           	<tr>
                                <td>Password</td>
                                <td><input id="password" name="password" type="text" value="<?php echo $spe[$sp]['password'];?>" size="8" maxlength="8"></td>
                            	<td></td>
                            </tr>
                                                       
                           	<tr>
                                <td>Host</td>
                                <td><input id="host" name="host" type="text" value="<?php echo $spe[$sp]['host'];?>" size="8" maxlength="8"></td>
                            	<td></td>
                            </tr>
                            <?php } ?>
                            </table><br />
                            <input class="button-primary" type="submit" name="eci_updatespeed_submit" value="Save" />						
                                                        
                        </form>
                    	
                        <h4>Future Events Based On Above Settings</h4>
						<table class="widefat post fixed">
                        	<tr>
                            	<td width="25"></td>
                                <td width="175"><strong>Due Date/Time</strong></td>
                                <td><strong>Priority Action</strong></td>
                            </tr>
                            <?php		
							$time = $spe[$sp]['nextevent'];
							for ($i = 1; $i <= 10; $i++) 
							{
								$time = $time + $spe[$sp]['eventdelay'];
								echo "                            
								<tr>
									<td>".$i."</td>
									<td>".date( 'F j, Y, g:i a',$time )."</td>
									<td>Import</td>
								</tr>";
							}                            	
							?>

                       </table>
				</div>
			</div><?php 
		}
		?>


		<div class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3>Speed Profile List</h3>
			<div class="inside">
				<?php eci_help_icon(ECIBLOG,'eci-speed-profiles','');?>
				<p>				
					<form method="post" name="eci_speedselect_form" action=""> 
						<?php eci_speedprofilelist(); ?><br />
						<input class = "button-primary" type = "submit" name = "eci_speedselect_submit" value = "Open" />
						<input class = "button-primary" type = "submit" name = "eci_speedelete_submit" value = "Delete" />
					</form>
				</p>	
			</div>
		</div>
		
		<div class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3>Project Speed Assignments</h3>
			<div class="inside">
				<p>				
					<form method="post" name="eci_projectspeedpair_form" action=""> 
						<?php eci_speedprofilepairinglist( $pro ); ?><br />
						<input class = "button-primary" type = "submit" name = "eci_projectspeedpair_submit" value = "Save" />
					</form>
				</p>	
			</div>
		</div>
		
	</div>
</div>
<?php
}// end if current project set
?>

<?php eci_footer(); ?>