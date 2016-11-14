<br />

<?php eci_help_icon(ECIBLOG,'eci-tutorial-data-import','');?>

<?php	
// if speed profile not set, offer profiles list only else also display import actions - also only display message if project is post creation, other projects have default import settings
if( $pro[$pro['current']]['protype'] == 'postcreation' && ( !isset( $pro[$pro['current']]['speed'] ) || $pro[$pro['current']]['speed'] == 'NA' ) )
{    
	_e('Please select a Speed Profile for your project on the Speeds page to begin importing data. Speed profiles
	provide the settings required for the project to know how much data to import per event.');
	echo '<br /><br /><br />';
}
else
{
    // get speed profile type (not name) to determine output
    if( $pro[$pro['current']]['protype'] == 'postcreation' )
    {
		$speedtype = $spe[ $pro[ $pro['current'] ]['speed'] ]['type'];
    }
    else
    {
    	$speedtype = 'fullspeed';
    }
    
	// calculate progress
	$progress = $pro[$pro['current']]['rowsinsertfail'] + $pro[$pro['current']]['rowsinsertsuccess'];
	
	// get project tables row count
	$records = eci_counttablerecords( $pro['current'] );
	
	$undobutton = '';
	
	// if this is a multi file project we need to establish if all files have been imported before displaying completion
	if( isset( $pro[ $pro['current'] ]['filesettype'] ) && $pro[ $pro['current'] ]['filesettype'] == 'multifile' )
	{
		$multicomplete = eci_ismultifile_importcomplete( $pro,$pro['current'] );
	}
	
	// if imported records equal csv rows - do not show import button - show undo button to delete records instead
	if( isset( $csv['format']['rows'] ) && $records >= $csv['format']['rows'] 
	&& ( !isset( $multicomplete ) || isset( $multicomplete ) && $multicomplete == true ) )
	{		
		echo '<h4>';
		_e('Data Import Complete');
		echo '</h4><p>';
		_e('Number of records in the project table matches number of rows in your csv file.
		No further importing is required.');
		echo '</p>'.$undobutton.'';
	}
	elseif( isset( $csv['format']['rows'] ) && $progress >= $csv['format']['rows'] 
	&& ( !isset( $multicomplete ) || isset( $multicomplete ) && $multicomplete == true ) )
	{
		echo '<h4>';
		_e('Data Import Complete');
		echo '</h4><p>';
		_e('Your project statistics show that the plugin may have failed to import some rows from your
		csv file to the project table. However it has processed every csv file row and will not continue any importing attempts. You may
		need to re-create your project but only do so when you are sure failures are not related to issues in your data such as
		duplicate records.');
		echo $undobutton.'</p>';
	}
	else
	{
		// create import button - change values to trigger different processing when submitted
		// none post creation projects do not allow pausing - they do not allow anything but fullspeed 

			$buttontext = __('Import Data');
			$buttonname = __('eci_datatransfer_submit');
		
		
		$importbutton = '
		<form method="post" name="eci_importstage_form" action="">  
			<input name="eci_filename" type="hidden" value="'.$pro['current'].'" />
			Encoding To Apply:
			<select name="eci_encoding_importencoding" size="s">
				<option value="None">None</option>
				<option value="UTF8Standard">UTF-8 Standard Function</option>
				<option value="UTF8Full">UTF-8 Full (extra processing)</option>
			</select>
			<br /><br />';
		

			$importbutton .= '<input class="button-primary" type="submit" name="'.$buttonname.'" value="'.$buttontext.'" />';
			$importbutton .= '<input class="button-primary" type="submit" name="eci_datatransferonerecord_submit" value="Test Import" />';
		
		
		$importbutton .= '</form><br />';
		
		// display start button - different messages depending on speed profile

			echo '<h4>';
			_e('Full Speed Import');
			echo '</h4><p>';
			_e('Your import speed is currently set to a Full Speed event. The plugin will only import data when
			your ready. It will	continue the import process until the entire csv file has been imported.
			Click the Import button below to begin this process.');
			echo '</p>'.$importbutton;

	}
}
?>
<br />
