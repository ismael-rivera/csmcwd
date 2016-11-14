<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;

// set variables to control post box open closed status
$postbox_closed_import = true;// true = closed
$postbox_closed_posttypes = true;
$postbox_closed_taxonomies = true;
$postbox_closed_datafunctions = true;
$postbox_closed_customfields = true;
$postbox_closed_categories = true;
$postbox_closed_conditions = true;
$postbox_closed_updating = true;
$postbox_closed_projectoptions = true;

if( isset( $pro['current'] ) && $pro['current'] != 'None Selected' )
{
	$csv = get_option( 'eci_'.$pro['current'] );	
			
	// process data import from csv file to database - 
	if(isset($_POST['eci_datatransfer_submit']) || isset($_POST['eci_datatransferonerecord_submit']))
	{
		$postbox_closed_import = false;
		
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
	
	// process special functions submit
	if( isset( $_POST['eci_specialfunctions_submit'] ) ){eci_savespecials( $pro['current'] );$postbox_closed_datafunctions = false;}
	
	// processes custom fields form submission
	if(isset($_POST['eci_customfields_submit'])){eci_savecustomfields($pro['current']);$postbox_closed_customfields = false;}
	
	// save single category
	if(isset($_POST['eci_singlecategory_submit'])){eci_savesinglecategory($pro['current'],$_POST['eci_category']);$postbox_closed_categories = false;}
	
	// save category group
	if(isset($_POST['eci_categorygroupsave_submit'])){eci_savegroupcategory($pro['current']);$postbox_closed_categories = false;}

	// save data category to blog category pairing
	if(isset($_POST['eci_categorypairing_submit'])){eci_save_categorypairing($pro['current']);$postbox_closed_categories = false;}

	// process category group delete request - by url only
	if(isset($_POST['eci_deletecategorygroup_submit']) && isset($_POST['eci_catgroupid'])){eci_deletecategorygroup($pro['current'],$_POST['eci_catgroupid']);$postbox_closed_categories = false;}
	

	// save project options - stage seven
	if(isset($_POST['eci_projectoptions_submit'])){eci_saveprojectoptions($_POST['eci_datemethod'],'post',$_POST['eci_publish'],$_POST['eci_comments'],$_POST['eci_pings'],$_POST['eci_publisher'],$pro['current'],$_POST['eci_adopt'],$_POST['eci_designmain']);$postbox_closed_projectoptions = false;}

    // get data arrays after functions are run
	$pro = get_option('eci_pro');
	$spe = get_option('eci_spe');
	$csv = get_option( 'eci_'.$pro['current'] );

	// ensure profile has been saved, this has been added to due a bug on 16th July 2011
	// project is being created, but not everything is being saved
	if(!isset($csv['format']))
	{
		eci_err('CSV Profile Missing','It appears the plugin has not been able to save your CSV files format.
		This is required for the plugin to operate. Please ensure you have a valid CSV file by opening it in Excel.
		If your sure your CSV file is not at fault please contact the webmaster@webtechglobal.co.uk');
	}
	else
	{?>
		<div class="wrap">
		
			<?php eci_pagetitle('ECI Configuration',$pro['current']); ?>
		
			<!-- POST BOXES START -->
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets" class="metabox-holder">
		
					<?php
		            // get project progress
		            $p = $pro[$pro['current']]['rowsinsertfail'] + $pro[$pro['current']]['rowsinsertsuccess'];// progress includes failed inserts
		            $r = eci_counttablerecords( $pro['current'] );// get actual records in table
		            ?>                  
		
					<!-- DATA TRANSFER START -->
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_import) && $postbox_closed_import == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>First Data Import ---- CSV Rows: <?php if( isset($csv['format']['rows']) ){ echo $csv['format']['rows']; }?> ---- Progress: <?php echo $p;?> ---- Table Records: <?php echo $r;?></span></h3>
						<div class="inside">
		                	<?php require_once('includes/eci_i_import.php');?>
		                	<p><strong>Your server and ECI plugin settings only allows the plugins events to run for <?php echo ECIMAXEXE;?> seconds despite your speed profile settings. Contact your hosting to increase this limit.</strong></p>
		                </div>
		            </div>
					<!-- DATA TRANSFER END -->
					           
		            <!-- DATA FUNCTION START -->
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_datafunctions) && $postbox_closed_datafunctions == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>Data Functions ---- Used: <?php echo eci_countspecials($pro['current']);?></span></h3>
		                <div class="inside">
		                	<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-data-functions','');?>
							<?php 
							if(isset($pro[$pro['current']]['rowsinsertsuccess']) && $pro[$pro['current']]['rowsinsertsuccess'] >= 1
							|| isset($pro[$pro['current']]['rowsupdatesuccess']) && $pro[$pro['current']]['rowsupdatesuccess'] >= 1 )
							{
								require_once('includes/eci_i_specials.php');
							}
							else
							{
								eci_not('Project Data Required','ECI import your CSV file rows before creating posts. The data is stored in a new table within
								your Wordpress database. Please import data using the first panel on the Configuration page for your project to display special functions, a single record is enough.
								The plugin simply needs some data to test and use in examples.');
								echo '<br />';
							}
							?>
		                </div>
		            </div>
		            <!-- DATA FUNCTION END -->
		            
		            <!-- CUSTOM FIELDS START -->
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_customfields) && $postbox_closed_customfields == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>Custom Fields ---- Used: <?php echo eci_customfieldsinuse( $pro['current'] );?></span></h3>
		                <div class="inside">        
		                	<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-custom-fields','');?>
		                	<p>Custom Fields are a type of meta data, usually used by themes to build the page. You do not need to
		                	remove all values from Custom Fields/Meta Keys, they are populated to help make it easier for you but will not
		                	create meta data unless you change the menus. You must create a custom field for every column you want to create a
		                	shortcode in your design for. Click the help icon for more information.</p>
							<?php require_once('includes/eci_i_customfields.php');?>
		                </div>
		            </div>
		            <!-- CUSTOM FIELDS END -->
		                       
					<!-- CATEGORIES START -->  
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_categories) && $postbox_closed_categories == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>Categories <?php eci_categoriesstatus();?></span></h3>
		                <div class="inside">
		                	<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-categories','');?>
		                	<p><?php _e('Important: in the free edition, Apply To Post is used for every level meaning 3 categories are applied to each post. After creating categories, some may be hidden on the Categories admin page. Create a category manually, then deleted it
		                	to refresh the cache and make all your categories show');?>.</p>
		                	<?php require_once('includes/eci_i_categories.php');?>
		                </div>
		            </div>
					<!-- CATEGORIES END -->
		                                  
					<!-- UPDATING START -->  
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_updating) && $postbox_closed_updating == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>Update Data and Posts (PAID EDITION ONLY)</span></h3>
		                
		                <div class="inside">
		                	<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-updating','');?>  
		              
		                	<p>Updating can be applied to data or/and posts. It happens in different operations but uses a lot of the
		                	same configuration. The main part to complete is the Unique Key which ties records from your csv file to
		                	those already in the database.</p>
		                	      
							<?php require_once('includes/eci_i_updating.php');?>
		                </div>
		            </div>            
					<!-- UPDATING END -->                       
		
					<!-- PROJECT OPTIONS START -->
		            <div id="dashboard_recent_comments" class="postbox <?php if(isset($postbox_closed_projectoptions) && $postbox_closed_projectoptions == true){echo 'closed';}?>" >
		                <div class="handlediv" title="Click to toggle"><br /></div>
		                <h3 class='hndle'><span>Project Options</span></h3>
		                <div class="inside">
							<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-project-options','');?>
		                	<?php require_once('includes/eci_i_projectopts.php');?>                    
		                </div>
		            </div>
					<!-- PROJECT OPTIONS CREATION -->			
		            
		            
		<div class="clear"></div>
		</div><!-- dashboard-widgets-wrap -->
		
		</div><!-- wrap -->
<?php
	}// end if $csv format not saved
}// end if current project set
?>

<?php eci_footer(); ?>