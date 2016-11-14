<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>       

<?php
// PROCESSING START
if( isset( $_POST['eci_saveexport_submit'] ) )
{
	eci_export_savesettings();	
}

if( isset( $_POST['eci_exportstart_submit'] ) )
{
	eci_export_processstart();	
}

if( isset( $_POST['eci_resetexport_submit'] ) )
{
	$exp = get_option('eci_exp');
	unset($exp);
	$exp = array();
	update_option('eci_exp',$exp);
	eci_mes('Export Settings Reset','Your configuration of tables and columns have been erased');
}
// PROCESSING END

// if no current project do not display anything on this page
if( isset( $pro['current'] ) && $pro['current'] != 'None Selected' )
{
	// get data arrays after processing
	$spe = get_option('eci_spe'); 
	$set = get_option('eci_set'); 
	$pro = get_option('eci_pro'); 
	?>

	<div class="wrap">
	
		<?php eci_pagetitle('ECI Database Manager',$pro['current']); ?>
		
			<div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">
			
				<form method="post" name="eci_executeexport_form" action=""> 
					<input type="hidden" name="eci_exporter" value="exportsubmitted">
				
					<div class="postbox closed">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3>Database Operations - Select Columns</h3>
						<div class="inside">
							<p>				
								<?php eci_displaytables_exporter();?>
							</p>	
						</div>
					</div>
	
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3>Database Operations - Actions</h3>
						<div class="inside">
													<br />
							<p>
								<input class = "button-primary" type = "submit" name = "eci_saveexport_submit" value = "Save Settings" />								
								<input class = "button-primary" type = "submit" name = "eci_resetexport_submit" value = "Reset Settings" />
								<input class = "button-primary" type = "submit" name = "eci_exportstart_submit" value = "CSV Export" />
								<?php eci_help_icon(ECIBLOG,'eci-database-manager','');?>
							</p>
							<br />
						</div>
					</div>
										
				</form>
		</div>
	</div>
	<?php
}// end if current project set
?>

<?php eci_footer(); ?>