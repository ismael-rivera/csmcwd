<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>  
     
<div class="wrap">

	<?php @eci_pagetitle('ECI Status',$pro['current']); ?>
	
    <p><?php _e('This page does not only display the status of the plugin. It establishes facts about your server,
your Wordpress installation and specific configuration you have applied that matter to Easy CSV Importer.');?></p>   
	
	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<h4>ECI</h4>
            <table class="widefat post fixed">
            	<tr>
            		<td><strong>Current Version:</strong> <?php echo WP_EASYCSVIMPORTER_VERSION; ?></td>
            	</tr>
            	<tr>
            		<td><strong>Latest Beta Version:</strong> (coming soon)</td>
            	</tr>
            	<tr>
            		<td><strong>Installation Date:</strong> <?php echo get_option('eci_installdate'); ?></td>
            	</tr>
            	<tr>
            		<td><strong>Last Post Create Memory Use:</strong> <?php echo eci_status('memuse_create', false, 1).' bytes - '; echo ' ('.eci_formatfilesize(eci_status('memuse_create', false, 1)).') ';?></td>
            	</tr>
            </table><br />

			<h4>Wordpress</h4>
            <table class="widefat post fixed">
            	<tr>
            		<td><strong>Theme:</strong> <?php echo get_current_theme(); ?></td>
            	</tr>
            </table><br />
            
			<h4>Server</h4>
			<p>This is where we check your server and hosting limitations. All information displayed here effects how Easy CSV Importer operates.</p>
            <table class="widefat post fixed">
            	<tr>
            		<td><strong>PHP Version:</strong> <?php echo phpversion(); ?></td>
            	</tr>
             	<tr>
            		<td><strong>Server Maximum Execution Time:</strong> <?php echo ini_get('max_execution_time'); ?> seconds</td>
            	</tr>
             	<tr>
            		<td><strong>ECI Maximum Execution Time:</strong> <?php echo ECIMAXEXE; ?> seconds (5 seconds deducted to give room for the final part of scripts)</td>
            	</tr>
            	<tr>
            		<td><strong>Display Errors:</strong> <?php if(ini_get('display_errors') ){ echo 'Yes'; }else{ echo 'No'; }; ?></td>
            	</tr>  
            	<tr>
            		<td><strong>File Upload Limit:</strong> <?php echo ini_get( "upload_max_filesize"); ?></td>
            	</tr>         	
            	<tr>
            		<td><strong>Function: microtime:</strong> <?php if(function_exists('microtime')){echo 'Installed';}else{echo 'Not Installed';}; ?></td>
            	</tr>              	
            	<tr>
            		<td><strong>Function: bcsub:</strong> <?php if(function_exists('bcsub')){echo 'Installed';}else{echo 'Not Installed';}; ?></td>
            	</tr>             	
            	     	
            </table><br />
            
    	<div class="clear"></div>
    	
    	</div><!-- dashboard-widgets-wrap -->
	</div>
</div><!-- wrap -->

<?php eci_footer(); ?>