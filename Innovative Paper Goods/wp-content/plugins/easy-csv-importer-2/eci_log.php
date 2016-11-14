<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>  

<?php
// activate log file
if( isset( $_POST['activatelog'] ) )
{
	$set['log'] = 'Yes';
	if(update_option( 'eci_set',$set )){eci_mes( __('Log File Activated'),__('The plugin will make entries to the log file for all major procedures.') );}
}

// disable log file
if( isset( $_POST['disablelog'] ) )
{
	$set['log'] = 'No';
	if(update_option( 'eci_set',$set )){eci_mes( __('Log File Disabled'),__('The plugin no longer add new entries to the log file.') );}
}

// delete log file
if( isset( $_POST['deletelog'] ) )
{
	if( unlink( ECIPATH.'ecilog.csv' ) )
	{
		eci_mes( __('Log File Deleted'),__('The log file will be created again automatically unless logging has been turned off.') );
	}
	else
	{
		eci_err( __('Failed To Delete Log File'),__('The log file could not be deleted, it could
				possibly be in use. Please try again then seek support after confirming
				the file is actually on your server inside this plugins folder.') );
	}
}

// get options data after processing
$pro = get_option('eci_pro');
$set = get_option('eci_set');?>

    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class='hndle'><span>ECI Log File</span></h3>
    <div class="inside">
        <p><?php _e('Your log file will automatically be deleted when it reaches a size of 300kb, this can be adjusted on request.')?></p>
        
        <form method="post" name="eci_log_form" action="">            
            <table class="widefat post fixed">
               
                <tr><td><b>Action</b></td><td><b>Description</b></td></tr>
                <tr>
                <?php 
                if( !isset( $set['log'] ) || $set['log'] != 'Yes' )
				{
					echo '<td><input class="button-primary" type="submit" name="activatelog" value="Activate Log File" /></td>';
				}
				elseif( isset( $set['log'] ) && $set['log'] == 'Yes' )
				{
					echo '<td><input class="button-primary" type="submit" name="disablelog" value="Disable Log File" /></td>';				
				}
                ?>
                     <td><?php _e('Use this to being creating a log of everything the plugin does, it will help you confirm automated procedures happen.')?><br /></td>
                </tr>                 
                <tr>
                    <td><input class="button-primary" type="submit" name="deletelog" value="Delete Log File" /></td>
                    <td><?php _e('Delete log file. It will automatically be created if you have not turned logging off on the Advanced Options panel.')?><br /></td>
                </tr>                   
                <tr>
                    <td><input class="button-primary" type="submit" name="developerview" value="Developer View" /></td>
                    <td><?php _e('Displays more information about php and shortens the description text to be straight to the point and not explain things.')?><br /></td>
                </tr>      
                <tr>
                    <td><input class="button-primary" type="submit" name="easyview" value="Easy View" /></td>
                    <td><?php _e('This will display longer descriptions to help users understand what the plugin is doing and hides php information')?><br /></td>
                </tr>                      
            </table>
        </form>              
                
    </div>
                
<?php
if( !isset( $set['log'] ) || $set['log'] != 'Yes' )
{
	echo '<strong>You have not actived the Log yet.</strong>';?>

	<?php 
}
elseif( isset( $set['log'] ) || $set['log'] == 'Yes' )
{
	if ( !file_exists( ECIPATH.'ecilog.csv' ) ) 
	{
		// do not display this message if user has just deleted the log
		if( !isset( $_POST['deletelog'] ) )
		{
			eci_mes( __('Log File Not Created'),__('No log file exists because it has either been deleted or no log entries have been made yet.') );
		}
	}
	else
	{
		eci_pearcsv_include();
	
		// csv file row counter
		$rows = 0;
	
		// use pear to read csv file
		$conf = File_CSV::discoverFormat(  ECIPATH.'ecilog.csv'  );
		
		// apply seperator
		$conf['sep'] = ',';	
		
	    echo '<table class="widefat post fixed">
		<tr>
			<td width="35"></td>
			<td width="80">Date</td>
			<td width="80">Action</td>';
			
		    if( isset($_POST['developerview']) )
		    {
		    	echo '<td>Short Desc</td>';
		    }
		    else 
		    {
		    	echo '<td>Long Desc</td>';		    	
		    }
		    
		    echo '
		    <td width="125">Project</td>
			<td width="60">Priority</td>';

			if( isset($_POST['developerview']) )
		    {
		    	echo '<td width="50">Line</td>			
				<td>File</td>			
				<td>Function</td>';
		    }
		    
		echo '</tr>';
		
		$entry = 1;

		$grey = 0;
		$white = 1;

		// loop through records until speed profiles limit is reached then do exit
		while ( ( $r = File_CSV::read(  ECIPATH.'ecilog.csv' , $conf ) ) ) 
		{	
			if( $rows != 0 )
			{
				// syle rotator
				if( $r[4] == 'Critical' )
				{
					echo '<tr style="background-color:#F75D59;border:17px solid #aaaaaa;padding:1em;">';
				}
				elseif( isset( $lasttime ) && $lasttime == $r[0] )
				{
					echo '<tr style="background-color:'.$lastcol.';border:17px solid #aaaaaa;padding:1em;">';
				}
				else
				{
					if( $grey == 1 )
					{					
						echo '<tr style="background-color:#D3D3D3;border:17px solid #aaaaaa;padding:1em;">';
						$grey = 0;
						$white = 1;
						$lastcol = '#D3D3D3';
					}
					elseif( $white == 1 )
					{
						echo '<tr>';
						$grey = 1;$white = 0;
						$lastcol = '#FFFFFF';
					}
				}	    
		    
				echo '<td>'.$entry.'</td>';
				echo '<td>'.$r[0].'</td>';
				echo '<td>'.$r[1].'</td>';
				
			    if( isset($_POST['developerview']) )
			    {
			    	echo '<td>'.$r[2].'</td>';
			    }
			    else 
			    {
			    	echo '<td>'.$r[3].'</td>';	
			    }
				
				echo '<td>'.$r[4].'</td>';
				echo '<td>'.$r[5].'</td>';	

				if( isset($_POST['developerview']) )
			    {
					echo '<td>'.$r[6].'</td>';				
					echo '<td>'.basename($r[7]).'</td>';				
					echo '<td>'.$r[8].'</td>';
			    }			
				
				echo '</tr>';
				
				// put last time value into variable for comparing to the next
				$lasttime = $r[0];
				
				++$entry;
			}
			
			++$rows;
		}
		
		echo '</table>';
	}
}
?>

<?php eci_footer(); ?>
