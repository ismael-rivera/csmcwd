<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>  

<?php
// if no current project do not display anything on this page
if( isset( $pro['current'] ) && $pro['current'] != 'None Selected' )
{	
	$csv = get_option( 'eci_'.$pro['current'] );	
	
	// PROCESSING START
	if(isset($_POST['eci_usermetakeys_submit'])){eci_saveusermetakeys($pro['current']);}
	
	// change current project
	if( isset( $_GET['changecurrent'] ) )
	{
		$pro['current'] = $_GET['changecurrent'];
		if( update_option( 'eci_pro',$pro ) )
		{
			eci_mes( __('Current Project Changed'),'You change the Current Project to '.$pro['current'].', please remember this when working with the plugin.' );
		}
	}
	
	// process data import from csv file to database - 
	if( isset( $_POST['eci_datatransfer_submit'] ) )
	{
		// establish filename/project file data is being transfered from
		if( isset( $_POST['eci_filesettype'] ) && $_POST['eci_filesettype'] == 'multifile' )
		{
			$thefilename = $_POST['eci_datatransfer_submit'];// value displayed on button is what we use to process
			
			// apply submitted encoding to all files profiles
			foreach( $pro[$pro['current']]['multifileset'] as $path )
			{
				$tempcsv = get_option( 'eci_'.basename($path) );
				$tempcsv['importencoding'] = $_POST['eci_encoding_importencoding'];
				update_option('eci_'.basename($path), $tempcsv);
			}
		}
		else
		{
			$thefilename = $pro['current'];
			// save submitted encoding
			$csv['importencoding'] = $_POST['eci_encoding_importencoding'];
			update_option( 'eci_' . $thefilename, $csv );
		}
				
		// change project status to Active
		$pro[ $pro['current'] ]['status'] = 'Active';
		update_option( 'eci_pro', $pro );
		
		eci_dataimport( $thefilename,true,'import',$set );
	}
		
	// process delete and re-create table request
	if( isset( $_POST['eci_deletedata_submit'] ) )
	{
		// delete table
		$result = eci_deletetable( $pro['current'] );
		// on delete table success re-create table
		if( $result )
		{
			// re-create table
			eci_createtable( $pro['current'],$set );
			// reset progress counters
			$pro[$pro['current']]['rowsinsertsuccess'] = 0;
			$pro[$pro['current']]['rowsupdatesuccess'] = 0;
			$pro[$pro['current']]['rowsinsertfail'] = 0;
			$pro[$pro['current']]['rowsupdatefail'] = 0;
			$pro[$pro['current']]['events'] = 0;
			update_option('eci_pro',$pro);
		}
	}
	// PROCESSING END
	
	// get data arrays again
	$csv = get_option( 'eci_'.$pro['current'] );	
	$pro = get_option( 'eci_pro' );	
	$set = get_option( 'eci_set' );	
	$spe = get_option('eci_spe');
	$des = get_option('eci_des');
		
    // get project progress
    $p = $pro[$pro['current']]['rowsinsertfail'] + $pro[$pro['current']]['rowsinsertsuccess'];// progress includes failed inserts
    $r = eci_counttablerecords( $pro['current'] );// get actual records in table
                   
	?>
	
	<div class="wrap">
	
	<?php eci_pagetitle('ECI User Import Configuration',$pro['current']); ?>
	    
	    <p>The paid edition will soon have the ability to create posts and users together with users having their
	    correctly assigned posts even if the new post ID's are not a match to the users old post ID's</p>
	                
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
		
				<!-- DATA TRANSFER START -->
	            <div id="dashboard_recent_comments" class="postbox closed" >
	                <div class="handlediv" title="Click to toggle"><br /></div>
	                <h3 class='hndle'><span>1. First Data Import ---- CSV Rows: <?php if( isset($csv['format']['rows']) ){ echo $csv['format']['rows']; }?> ---- Progress: <?php echo $p;?> ---- Table Records: <?php echo $r; ?></span></h3>
					<div class="inside">
	                	<?php require_once('includes/eci_i_import.php');?>
	                </div>
	            </div>
				<!-- DATA TRANSFER END -->
				            
	            <!--  START -->
	            <div id="dashboard_recent_comments" class="postbox closed" >
	                <div class="handlediv" title="Click to toggle"><br /></div>
	                <h3 class='hndle'><span>2. User Meta</span></h3>
	                <div class="inside">     
	                	<?php eci_help_icon(ECIBLOG,'eciuser-meta','');?>   
	                	<p>If you do not select a column for Login/Username, the first part of email address will be used to create one. Some user meta keys are hidden to keep the form tidy.</p>
	                	<?php require_once('includes/eci_i_userbasics.php');?>
	                </div>
	            </div>	
	            <!--  END -->	
		
	            <!-- START -->
	            <div id="dashboard_recent_comments" class="postbox closed" >
	                <div class="handlediv" title="Click to toggle"><br /></div>
	                <h3 class='hndle'><span>3. Advanced User Configuration</span></h3>
	                <div class="inside">
	                	<h4>Coming Later</h4>
	                	<p>This panel will include abilities such as updating forum user database tables to match the
	                	newly created Wordpress accounts. The ability to add imported users to shopping cart databases
	                	will also be provided. Contact WebTechGlobal to register your interest in such features.</p>
	                </div>
	            </div>	
	            <!-- END -->	
	
			</div><!-- dashboard-widgets-wrap -->
		</div><!-- dashboard-widgets -->
			
	</div><!-- wrap -->
<?php 
}// is current project set

eci_footer(); ?>