<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>        

<?php
// get data arrays before processing for the functions themselves
@$csv = get_option('eci_'.$pro['current']);

// reset failed importer and update counters to continue attempting to do so
if(isset($_POST['eci_resetfails_submit']))
{
	$pro[$pro['current']]['rowsinsertfail'] = 0;
    $pro[$pro['current']]['rowsupdatefail'] = 0;
    if( update_option('eci_pro',$pro) )
    {
    	eci_mes('Reset Complete','Counters for failed data inserts or updates to the project database table have been reset. You can now
    	process the csv file again manually or automatically to attempt to add any missed records');
    }
    else
    {
    	eci_err('Reset Failed','Please try again then seek support if this continues to happen');
    }
}

// set all project created posts to publish
if(isset($_POST['eci_publishall_submit'])){eci_publishall($pro['current']);}

// runs post creation event based on projects speed profile create integer
if( isset( $_POST['eci_postcreation_submit'] ) ){eci_createposts($csv,$pro,$spe,$set,$des,$spe[$pro[$pro['current']]['speed']]['create'],true,$pro['current'] );}
							
// get data arrays after processing functions for this page
$pro = get_option('eci_pro');
@$csv = get_option('eci_'.$pro['current']);
$set = get_option('eci_set');
$spe = get_option('eci_spe');
$des = get_option('eci_des');

if( isset( $pro['current'] ) && $pro['current'] != '' && !is_null( $pro['current'] ) && $pro['current'] != 'None Selected' )
{
	$csv = get_option('eci_'. $pro['current'] );?>

	<div class="wrap">
	
        <?php eci_pagetitle('ECI Project Finish',$pro['current']); ?>
                               
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
        
               <!-- START OF BOX -->
               <div class="postbox">
                   <div class="handlediv" title="Click to toggle"><br /></div>
                   <h3>Final Actions</h3>
                   <div class="inside">
                        <?php require_once('includes/eci_i_final.php');?>    
                        <p><strong>Your server and ECI plugin settings only allows the plugins events to run for <?php echo ECIMAXEXE;?> seconds despite your speed profile settings. Contact your hosting to increase this limit.</strong></p>
                   </div>
               </div>  
               <!-- END OF BOX -->		

           </div>
               </div>  
                      
</div><!-- wrap -->
	
    
		
<?php
}
?>

<?php eci_footer(); ?>