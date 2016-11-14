<br />
<?php	
# ONCE UNIQUE KEY SETUP DISPLAY FORMS FOR APPLYING UPDATING BASED ON SPEED PROFILE
if( !isset( $pro[$pro['current']]['speed'] ) )
{    // speed profile has not been set, it should be set on new project page so this should be investigated
	_e('Your project does not have an Events Speed set. This is meant to be set on the
	Create Project form and must mean that it failed to save or the speed setting was deleted.
	Please report this problem and seek support so that it can be investigated.');
	echo '<br /><br />';
}
elseif( isset( $pro[$pro['current']]['speed'] ) && isset( $csv['updating']['ready'] ) && $csv['updating']['ready'] == true )
{
    // get speed profile type (not name) to determine output
    $speedtype = $spe[$pro[$pro['current']]['speed']]['type'];
    
	// calculate curent progress
	$progress = eci_progress( $pro['current'] );
	
	// get project tables row count
	$records = eci_counttablerecords( $pro['current'] );
	
	$resetbutton = '
	<h4>Reset Update Counters (New Update Phase)</h4>
	<p>You may reset the previous update progress using the button below if you would like to start again or know you have a new csv file.</p>
	<form method="post" name="eci_dataupdatereset_form" action=""> 
		<input class = "button-primary" type = "submit" name = "eci_dataupdatereset_submit" value = "Start New Update" />
	</form>';

	// if table records equal csv rows and update progress matches or greater then we are complete
	if( $progress >= $csv['format']['rows'] )
	{
		echo '<h4>Data Update Complete</h4><p>The plugin has processed your entire csv file. This
		is based on the progress counters from the last update. Your
		options from here on is to press the Update Reset button to reset progress counters however
		this is only needed when you upload a new csv file or if your simply testing.</p>'.$resetbutton;
	}
	else
	{
		// create import button
		$eventbutton = '
		<form method="post" name="eci_dataupdate_form" action="">  
			<input name="eci_filename" type="hidden" value="'.$pro['current'].'" />
			<input class="button-primary" type="submit" name="eci_dataupdate_submit" value="Start Data Update Event" /><br /><br /><br />
		</form>';
	
		// display start button - different messages depending on speed profile
		if( $speedtype == 'fullspeed' )
		{
			echo '<h4>Full Speed Update</h4>
			<p>The plugin will loop through the rows in your csv file until it reaches the
			end of the file, in a single event with no breaks. If a row does not have a matching
			record in the database, it will be inserted instead. Please ensure your Unique Key
			is correct and monitor your database table if your not sure.</p>'.$eventbutton.$resetbutton;
		}
		elseif( $speedtype == 'manualevents' )
		{
			echo '<h4>Manual Events Import</h4><p>To update your data using your entire csv file,
			you will need to initiate each update event yourself. Press the button below to action
			the next update event now.</p>'.$eventbutton.$resetbutton;
		}
		elseif( $speedtype == 'spreadout' )
		{
			echo '<h4>Spreadout Import</h4><p>The plugin will handle updating itself and do it at the 
			rate set in your projects speed profile. Please press the Perform Update button below
			to begin the process now.</p>'.$eventbutton.$resetbutton;
		}
		elseif( $speedtype == 'blog2blog' )
		{
			echo '<h4>Blog2Blog Import</h4><p>This will update a table of data in another blog,
			not the database in this blog.</p>'.$eventbutton.$resetbutton;
		}
	}
	
	echo '<br /><br />';
}
?>
<div id="dashboard_recent_comments" class="postbox closed" >
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class='hndle'><span>Upload New CSV File For Update</span></h3>
    <div class="inside">
        <form method="post" enctype="multipart/form-data" name="eci_uploadnewfile_form" class="form-table">
            <input type="file" name="file" size="70" /><br /><br />
            <input class="button-primary" type="submit" value="Upload CSV File" name="eci_uploadnewfile_submit" />
           	<br />
           	<br />
        </form>     
    </div>
</div>                    
                    
                    
<div id="dashboard_recent_comments" class="postbox closed" >
    <div class="handlediv" title="Click to toggle"><br /></div>
    <?php if( isset( $csv['updating']['ready'] ) && $csv['updating']['ready'] == true ){$settingsstatus = ' ---- Ready';}
	elseif( !isset( $csv['updating']['ready'] ) || $csv['updating']['ready'] == false ){$settingsstatus = ' ---- Not Ready (required for updating)';}?>
    <h3 class='hndle'><span>Unique Key <?php echo $settingsstatus;?></span></h3>
    <div class="inside">
        <p>You must not use columns that may have different values per record. Your unique key must be
        made up of columns that never change i.e. product id, url.</p>
		<form method="post" name="eci_uniquekey_form" action="">            
			<table class="widefat post fixed">                
				<tr><td width="100"><b>Reference</b></td><td><b>CSV Columns</b></td></tr>
				<tr><td><b>Main Key</b></td><td><?php eci_csvcolumnmenu_updatekey( $pro['current'], '1', $pro );?></td></tr>
				<tr><td><b>Sub-Key 1</b></td><td><?php eci_csvcolumnmenu_updatekey( $pro['current'], '2', $pro );?> not required</td></tr>
				<tr><td><b>Sub-Key 2</b></td><td><?php eci_csvcolumnmenu_updatekey( $pro['current'], '3', $pro );?> not required</td></tr>                      	   
			</table>
			<br />
			<input class="button-primary" type="submit" name="eci_uniquekey_submit" value="Save &amp; Test Key" />
		</form>   
    </div>
</div>

<div id="dashboard_recent_comments" class="postbox closed">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class='hndle'><span>Column Exclusions <?php echo eci_columnexclusionsstatus( $pro['current']  ); ?></span></h3>
    <div class="inside">
    <br />
       <form method="post" name="eci_updateexclusion_form" action="">            
			<table class="widefat post fixed">                
            <tr><td width="175"><b>Column Titles</b></td><td><b>Include/Exclude</b></td></tr>
                <?php	
                $columnid = 0;
                foreach($csv['format']['titles'] as $column)
                {
                    ?><tr>
                            <td><strong><?php echo $column; ?></strong></td>
                            <td><?php echo eci_exclusionmenu( $column,$pro['current'] ); ?></td>
                        </tr>
                    <?php 
                    ++$columnid;
                }
                ?>
            </table>
            <br />
            <input class="button-primary" type="submit" name="eci_columnexclusions_submit" value="Save" />
        </form> 
        
    </div>
</div>

<div id="dashboard_recent_comments" class="postbox closed" >
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class='hndle'>
        <span>Update Settings
       		<?php if( isset( $csv['updating']['updateposts'] ) && $csv['updating']['updateposts'] == 'Yes' ){ echo ' ---- Post Updating Active';}
       		elseif( !isset( $csv['updating']['updateposts'] ) || $csv['updating']['updateposts'] == 'No' ){ echo ' ---- Post Updating Disabled';}?>
        </span>
    </h3>
    <div class="inside">
    <?php if( ECI_EFF_HOOKSFILE ){?>
		<form method="post" name="eci_updatingsettings_form" action="">            
			<table>
				<tr>
					<td><b>Update Posts Automatically</b></td>
					<td>                   
						<select name="eci_updateposts" size="1" >
						  <?php if(!isset($csv['updating']['updateposts'])){echo '<option value="No">No</option>';}?>
						  <option value="Yes" <?php if(isset($csv['updating']['updateposts'])){eci_selected('Yes',$csv['updating']['updateposts']);}?>>Yes</option>
						  <option value="No" <?php if(isset($csv['updating']['updateposts'])){eci_selected('No',$csv['updating']['updateposts']);}?>>No</option>
						</select></td>
				</tr>   	   
				<tr>
					<td><b>Process New Files Automatically</b></td>
					<td>                   
						<select name="eci_autonewfile" size="1" >
						  <?php if(!isset($csv['updating']['autonewfile'])){echo '<option value="No">No</option>';}?>
						  <option value="Yes" <?php if(isset($csv['updating']['autonewfile'])){eci_selected('Yes',$csv['updating']['autonewfile']);}?>>Yes</option>
						  <option value="No" <?php if(isset($csv['updating']['autonewfile'])){eci_selected('No',$csv['updating']['autonewfile']);}?>>No</option>
						</select>
                    </td>
				</tr>	
				
				<tr>
					<td><b>Post To Data Sync</b></td>
					<td>                   
						<select name="eci_postsync" size="1" >
						  <?php if(!isset($csv['updating']['postdatasync'])){echo '<option value="No">No</option>';}?>
						  <option value="Yes" <?php if(isset($csv['updating']['postdatasync'])){eci_selected('Yes',$csv['updating']['postdatasync']);}?>>Yes</option>
						  <option value="No" <?php if(isset($csv['updating']['postdatasync'])){eci_selected('No',$csv['updating']['postdatasync']);}?>>No</option>
						</select>
                    </td>
                </tr>
				<tr>
					<td><b>Post To Data Sync - Content column</b></td>
					<td>                   
						<?php eci_csvcolumnmenu_syncontent($pro['current'],$pro);?>
                    </td>
				</tr>
							 				 	   
			</table>
			<input class="button-primary" type="submit" name="eci_updatingsettings_submit" value="Save" />
		</form>   
		<?php }else{eci_not('You Have Disabled This Feature', 'You can activate this feature again in the eci_i_efficiency.php file');}?>	
		
    </div>
</div>