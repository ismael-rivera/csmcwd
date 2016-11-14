<?php
function eci_webtechglobal_admin()
{
	
}

/**
 * Builds standard Wordpress plugin menu
 * @global $set
 * $global $pro
 */
function eci_menu() 
{
	global $set;
	
	// we need to call $pro here as it is change by project creation action hook
	$pro = get_option('eci_pro');
	
    // Add a new top-level menu (ill-advised):
    add_menu_page(__('ECI','the-eci'), __('ECI','the-eci'), 'Administrator', 'eci-top-level-handle', 'eci_toppage' );

    // Add submenus
    add_submenu_page('eci-top-level-handle', __('ECI Home','the-eci'), __('ECI Home','menu-test'), 'administrator', 'sub-page0', 'eci_toppage');
    add_submenu_page('eci-top-level-handle', __('1. Start','the-eci'), __('1. Start','menu-test'), 'administrator', 'sub-page1', 'eci_subpage_start');
    
	// if a post creation project show these menu pages	
	if( !isset($pro['current'])
	|| isset($pro['current']) && !isset($pro[$pro['current']]['protype']) 
	|| isset($pro['current']) && isset($pro[$pro['current']]['protype']) && $pro[$pro['current']]['protype'] == 'postcreation')
	{    
	    add_submenu_page('eci-top-level-handle', __('2. Designs','the-eci'), __('2. Designs','menu-test'), 'administrator', 'sub-page2', 'eci_subpage_designs');
	    add_submenu_page('eci-top-level-handle', __('3. Speeds','the-eci'), __('3. Speeds','menu-test'), 'administrator', 'sub-page3', 'eci_subpage_speeds');
	    add_submenu_page('eci-top-level-handle', __('4. Config','the-eci'), __('4. Config','menu-test'), 'administrator', 'sub-page4', 'eci_subpage_config');
	    add_submenu_page('eci-top-level-handle', __('5. Finish','the-eci'), __('5. Finish','menu-test'), 'administrator', 'sub-page5', 'eci_subpage_finish');
	}
	elseif( isset($pro['current']) && isset( $pro[$pro['current']]['protype'] ) && $pro[$pro['current']]['protype'] == 'usercreation')
	{	
    	add_submenu_page('eci-top-level-handle', __('2. Config','the-eci'), __('2. Config','menu-test'), 'administrator', 'sub-page6', 'eci_subpage_user_config');
        add_submenu_page('eci-top-level-handle', __('3. Finish','the-eci'), __('3. Finish','menu-test'), 'administrator', 'sub-page7', 'eci_subpage_user_finish');		
	}   
	 
    add_submenu_page('eci-top-level-handle', __('Plugin Settings','the-eci'), __('Plugin Settings','menu-test'), 'administrator', 'sub-page8', 'eci_subpage_settings');
    add_submenu_page('eci-top-level-handle', __('Database','the-eci'), __('Database','menu-test'), 'administrator', 'sub-page9', 'eci_subpage_database');
    add_submenu_page('eci-top-level-handle', __('Log','the-eci'), __('Log','menu-test'), 'administrator', 'sub-page10', 'eci_subpage_log');
    add_submenu_page('eci-top-level-handle', __('Troubleshooting','the-eci'), __('Troubleshooting','menu-test'), 'administrator', 'sub-page11', 'eci_subpage_troubleshooting');
    add_submenu_page('eci-top-level-handle', __('Partners','the-eci'), __('Partners','menu-test'), 'administrator', 'sub-page12', 'eci_subpage_partners');
    add_submenu_page('eci-top-level-handle', __('Featured','the-eci'), __('Featured','menu-test'), 'administrator', 'sub-page13', 'eci_subpage_featured');
    add_submenu_page('eci-top-level-handle', __('Integrate','the-eci'), __('Integrate','menu-test'), 'administrator', 'sub-page14', 'eci_subpage_integrate');
    add_submenu_page('eci-top-level-handle', __('Status','the-eci'), __('Status','menu-test'), 'administrator', 'sub-page15', 'eci_subpage_status');
}

# Page Include Functions
function eci_toppage(){require_once(ECIPATH.'eci_home.php');}
function eci_subpage_speeds(){require_once(ECIPATH.'eci_speeds.php');}
function eci_subpage_finish(){require_once(ECIPATH.'eci_overview.php');}
function eci_subpage_designs(){require_once(ECIPATH.'eci_designs.php');}
function eci_subpage_start(){require_once(ECIPATH.'eci_start.php');}
function eci_subpage_config(){require_once(ECIPATH.'eci_configs.php');}
function eci_subpage_settings(){require_once(ECIPATH.'eci_settings.php');}
function eci_subpage_log(){require_once(ECIPATH.'eci_log.php');}
function eci_subpage_partners(){require_once(ECIPATH.'eci_partners.php');}
function eci_subpage_troubleshooting(){require_once(ECIPATH.'eci_trouble.php');}
function eci_subpage_featured(){require_once(ECIPATH.'eci_featured.php');}
function eci_subpage_integrate(){require_once(ECIPATH.'eci_integrate.php');}
function eci_subpage_status(){require_once(ECIPATH.'eci_status.php');}
function eci_subpage_database(){require_once(ECIPATH.'eci_database.php');}
function eci_subpage_user_config(){require_once(ECIPATH.'eci_user_config.php');}
function eci_subpage_user_finish(){require_once(ECIPATH.'eci_user_finish.php');}

/**
 * Checks if a multifile projects data import is complete by checking all files in the set
 * @param file $filename
 */
function eci_ismultifile_importcomplete( $pro,$filename )
{
	$complete = false;
	
	foreach( $pro[ $filename ]['multifileset'] as $path )
	{
		$csvtemp = get_option( 'eci_'. basename($path) );
		
		// were not going to include failed inserts - all records for all files must insert
		if( $csvtemp['format']['rows'] > $pro[ basename($path) ]['rowsinsertsuccess'] )
		{
			$complete = false;
		}
		elseif( $csvtemp['format']['rows'] < $pro[ basename($path) ]['rowsinsertsuccess'] )
		{
			$complete = true;
		}
		elseif( $csvtemp['format']['rows'] == $pro[ basename($path) ]['rowsinsertsuccess'] )
		{
			$complete = true;
		}		
		
		unset( $csvtemp );
	}
	
	return $complete;
}

# Processes CSV File Upload For Overwriting Old File Only
function eci_csvuploadupdate( $upload )
{
	$set = get_option( 'eci_set' );
	
	// check for errors
	if ( $upload['error'] != 0 ) 
	{
		eci_log(__('Upload Failed'),'A file upload for overwriting existing file failed',
		'You attempted to upload a new csv file for replacing an existin file, normally done to trigger a data
		update. However the upload failed, please investigate.','Operation',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );	
		eci_err(__('CSV File Upload Failed'),__('CSV file upload could not be started at all as the file loader returned error, please try again.'));
	}
	elseif ( $upload['error'] == 0 ) 
	{	
		// get project data
		$pro = get_option('eci_pro');
		
		// confirm filename is a match to an existing project else stop the upload		
		if( !isset( $pro[$upload['name']] ) )
		{
			eci_err(__('Uploaded File Does Not Match Any Project'),'The file you uploaded is named '. $upload['name'] .' and no
			project was found with that name. Please go to the Project Start page if you are trying to setup a new project or
			correct the filename to match your existing projects filename so that it overwrites the existing file.' );
		}
		elseif( isset( $pro[$upload['name']] ) )
		{
			// is the uploaded file for current project or different project ( we still complete the upload )
			if( $upload['name'] == $pro['current'] )
			{
				eci_mes(__('File Matches Current Project'),__('The plugin confirmed that the csv file being uploaded is for the project you are current working on, the old file has been overwritten.') );
			}
			else
			{
				eci_mes(__('File Matches Another Project'),__('The plugin will continue to upload your csv file
						and any matching file will be overwritten however the file you are uploading is not for
						the current active project. This does not mean that your current project will use the data
						from the file you have uploaded. This message is just to confirm that you have not uploaded
						a new csv file for the project you are currently working on. Your active project has not been
						changed in any way.') );
			}
			
			// get the path to the existing project file - used as final path
			$path = $pro[$upload['name']]['filepath'];
			
			// confirm file is actually there and delete, it may already be deleted
			if ( file_exists( $path ) ) 
			{
				// change file exists switch to true
				$fileexisted = true;
				
				// get existing files datestamp
				$oldtime = filemtime( $path );

				// delete the existing file
				$deleted = unlink( $path );
				
				if( $deleted )
				{
					eci_err(__('Existing File Deleted'),__('The old csv file for the one you have uploaded was deleted.') );
				
					// move temp upload to its final path
					move_uploaded_file( $upload['tmp_name'], $path );	
					
					// confirm file was moved
					if ( file_exists( $path ) ) 
					{
						$newtime = filemtime( $path );

						$csv['previousfiledate'] = $oldtime;
						$csv['currentfiledate'] = $newtime;
						$pro[$upload['name']]['reset'] = time();

						$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

						update_option( 'eci_'.$upload['tmp_name'],$csv );
						update_option( 'eci_pro',$pro );
						
						eci_log(__('Upload Success'),'CSV file uploaded as an update to existing project',false,$upload['name'],$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	

						// compare dates and display message
						if( $newtime > $oldtime )
						{
							eci_mes(__('Uploaded File Is Newer Than Previous File'),__('New file has been uploaded. Please ensure your Updating settings are complete.') );
						}
						elseif( $newtime < $oldtime )
						{
							eci_mes( __('Uploaded File Is Older Than Previous File'),__('It appears that
									the file you uploaded is an older copy.
									This is confirmed so that you can ensure that you have not
									uploaded the wrong file and may be updating newer data with
									old data.'));
						}
						elseif( $newtime == $oldtime )
						{
							eci_mes( __('Uploaded File Does Not Appear To Be Different'),__('The plugin
									checked the date of your existing csv file and the one you
									uploaded. Both modification dates match and so it appears
									that the csv file you just uploaded may not have new data.'));
						}
					}
					else
					{
						eci_log( __('Upload Failed'),__('A CSV file upload attempt failed'),'You attempted to upload a csv file to replace
						an existing file but it failed. There is a high chance that it is due to your servers permissions. Please confirm your 
						server will allow the php move_uploaded_file function.','Error','Critical',__LINE__,__FILE__,__FUNCTION__ );	
						eci_err( __('Upload Failed'),__('The plugin failed to move your new file into the correct directory. Please try again then seek support.') );
					}
				}
			}
		}
	}
}

# Processes CSV File Upload
function eci_csvupload( $upload,$set )
{
	//$_POST = stripslashes_deep($_POST);
	
	$upload = $_FILES['file'];
	
	// check for errors
	if ( $upload['error'] != 0 ) 
	{
		eci_log(__('Upload Failed'),__('A new file upload attempt failed to start'),
		'You attempted to upload a new csv file for replacing an existin file, normally done to trigger a data
		update. However the upload failed, please investigate.','Error',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );	
		eci_err(__('Upload Failed To Start'),__('CSV file upload could not be started 
		at all as the file loader returned error, please try again.'));
	}
	elseif ( $upload['error'] == 0 ) 
	{	
		// get path data
		$pat = get_option('eci_pat');
		
		// if no path data or path not submitted use default
		if( !$pat || !isset( $_POST['eci_path'] ) )
		{
			$path = 'default';
			
			// install path array
			eci_install_paths();
			
			// now get paths data
			$pat = get_option('eci_pat');
		}
		else
		{
			$path = $_POST['eci_path'];
		}

		// now get the actual path
		foreach( $pat as $key=>$p )
		{
			if( $path == $key )
			{
				$path = $p['path'];
			}
		}
		
		// confirm path is valid else exit
		$openresult = opendir( $path );
		
		// if failed to open directory display error
		if( !$openresult )
		{
			// use directory name
			// add manual directory creation button to error message
			$createform = '
			<form method="post" name="eci_createdirectory" action=""> 
				<input name="eci_pathdir" type="hidden" value="'.$path.'" />
				<label>Enter Directory Name:<input name="eci_pathname" type="text" value="" size="15" maxlength="15" /></label>
				<input class="button-primary" type="submit" name="eci_createdirectory_submit" value="Create Directory" />
			</form>';
			
			eci_err(__('Failed To Open Path/Directory'),'The path being used for uploading your
					csv file does not appear to be a valid directory or a directory with
					permissions that will allow the upload. Your CSV file was not uploaded. 
					Here is the directory you are attempting to upload your csv file to and
					a button to create it manually.<br /><br />
					<strong>Required Path</strong>'.$path.'<br /><br />
					 '.$createform.'');
		}
		else
		{
			// build final file path
			$path = $path.$upload['name'];
			
			// if the final path already exists, delete the existing file then continue
			if ( file_exists( $path ) ) 
			{
				// change file exists switch to true
				$fileexisted = true;
				
				// get existing files datestamp
				$oldtime = filemtime( $path );

				// delete the existing file
				$deleted = unlink( $path );
				
				if( $deleted )
				{
					eci_err(__('Existing File Deleted'),__('A matching csv filename was found in the same directory you are uploaded to. It has been deleted as part of the upload process.') );
				}
			}
			else
			{
				$fileexisted = false;
				$deleted = true;// set variable only, has no purpose
			}
									
			// if file could not be delete do not continue and let user know
			if( !$deleted && $fileexisted === true )
			{
				// $deleted or $fileexists do not equal true, both must be true to avoid this
				eci_err(__('File Name Exists Already'),__('You already have a CSV file with the same name
						in the selected directory. The plugin could not delete it. You will need to
						delete it manually then try again.'));
			}
			elseif( $fileexisted === true && $deleted == true || $fileexisted === false )
			{
				// move temp upload to its final path
				$moveresult = move_uploaded_file( $upload['tmp_name'], $path );
				
				// alert user if file move failed
				if( !$moveresult )
				{
					eci_err(__('File Failed To Upload'),'There is no clear reason for the failure.
							The plugin confirm no file with the same name exists. Please check
							the directory permissions and ensure you are uploading a correctly
							formatted CSV file. '. $moveresult .' and report this problem so we 
							can investigate. Please considering using FTP.');
				}
				else
				{
					// confirm file has uploaded to the correct directory and path exists
					if ( file_exists( $path ) ) 
					{
						eci_mes(__('CSV Upload Success'),'You uploaded '.$upload['name'].' and
						can now use it to create a new project or update an existing one
						using the file name.');

					}
					else
					{
						eci_err(__('Possible Upload Error'),__('The plugin detected that the upload was a success but on double checking that the uploaded file is 
								now in place, the plugin could not locate your file. Please investigate and report this if the problem persists.'));
					}
				}
			}
		}
	}
}

# Deletes WP Options And Returns Result
function eci_deleteoptions()
{
	$opts = func_get_args();
	$count = count( $opts );

	if ( $count == 1 ) 
	{
		return (delete_option($opts[0]) ? true : false );
	}
	elseif ( count( $opts ) > 1 )
	{
		foreach ( $opts as $option ) 
		{
			if ( ! delete_option( $option ))
			{
				return false;
			}
		}
		return true;
	}
	return false;
}

# Load CSS And JS For WYSIWYG Editor
function eci_wysiwygeditor() 
{
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}	

# Output: Checks If A Value Is A Valid URL And Returns True Or False
# Input:  The url or any other value if checking to make sure a value is not a url
function eci_valid_url($str)
{
	if (!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',$str))
	{
	  return false;
	} 
	else 
	{
	  return true;
	}
}

# checks if a projects table already exists or not
function eci_istable( $filename )
{
	global $wpdb;
	
	// get table name
	$table_name = eci_wptablename( $filename );
	
	// check if table name exists in wordpress database
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
	{
		return false;
	}
	else
	{
		return true;
	}
}


// Create the function to output the contents of our Dashboard Widget

function eci_dashboard_rsswidget_function() 
{
	echo '<script src="http://feeds.feedburner.com/easycsvimporter?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/easycsvimporter"></a><br/>Powered by FeedBurner</p> </noscript>';
} 

// Create the function use in the action hook

function eci_add_dashboard_rsswidgets() 
{
	wp_add_dashboard_widget('eci_rssdashboard_widget', 'Easy CSV Importer Updates', 'eci_dashboard_rsswidget_function');	
} 

function eci_updateposts($filename,$csv,$number,$use)
{}

/**
 * Changes none published posts to published
 * Has a limit of posts to publish
 * Only looks for the post type saved in project
 * @param filename $filename
 */
function eci_publishall($filename)
{
	$limit = 500;
	$published = 0;
	
	$csv = get_option('eci_'.$filename);
	
	// get projects post type
	
	// do draft status posts
	$args = array(
    'numberposts'     => $limit,
    'offset'          => 0,
    //'category'        => ,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    //'include'         => ,
    //'exclude'         => ,
    'meta_key'        => 'eci_filename',
    'meta_value'      => $filename,
    'post_type'       => $csv['posttype'],
    //'post_mime_type'  => ,
    //'post_parent'     => ,
    'post_status'     => 'draft' );
	
	$drafts = get_posts( $args );
	
	$loopone = 0;
	foreach($drafts as $thepost)
	{
		wp_publish_post( $thepost->ID );
		++$loopone;
		unset($thepost);
	}
	
	// recalculate a new limit for next get
	$limit = $limit - $loopone;
	
	// do pending status posts
	$args = array(
    'numberposts'     => $limit,
    'offset'          => 0,
    //'category'        => ,
    //'orderby'         => 'post_date',
    'order'           => 'DESC',
    //'include'         => ,
    //'exclude'         => ,
    'meta_key'        => 'eci_filename',
    'meta_value'      => $filename,
    'post_type'       => $csv['posttype'],
    //'post_mime_type'  => ,
    //'post_parent'     => ,
    'post_status'     => 'pending' );
	
	$drafts = get_posts( $args );
	
	$looptwo = 0;
	foreach($drafts as $post)
	{
		wp_publish_post( $thepost->ID );
		++$looptwo;
		unset($thepost);
	}	
	
	$published = $loopone + $looptwo;
	
	eci_mes('Publishing Complete','A total of '.$published.' posts or pages were set to publish status');
}

function eci_get_tables()
{
	global $wpdb;
	return mysql_query('SHOW TABLES FROM '.$wpdb->dbname);
}

function eci_get_dbcolumns($table)
{
	global $wpdb;
	return mysql_query("SHOW COLUMNS FROM " . $table);	
}

function eci_saveusermetakeys($filename)
{
	$i = 0;
	$used = 0;
	
	$csv = get_option( 'eci_'.$filename );
		
	###############  IMPROVE THIS FUNCTION TO SHOW EXAMPLES OF DATA    ################
	
	// delete current custom fields - removes any no longer entered on form
	if( isset( $csv['usermetakeys'] ) )
	{
		unset( $csv['usermetakeys'] );
	}
	
	while( $i < $_POST['ukcount'] )
	{		
		if( empty( $_POST['uk_'.$i.''] ) )
		{
			// do nothing
		}
		elseif( !empty( $_POST['uk_'.$i.''] ) && $_POST['col_'.$i.''] == 'NA' && !empty( $_POST['def_'.$i.''] ) )
		{
			// save default
			$csv['usermetakeys'][$_POST['uk_'.$i.'']]['def'] = $_POST['def_'.$i.''];	
		}
		elseif( !empty( $_POST['uk_'.$i.''] ) && $_POST['col_'.$i.''] != 'NA' )
		{
			// save column
			$csv['usermetakeys'][$_POST['uk_'.$i.'']]['col'] = $_POST['col_'.$i.''];
			$csv['usermetakeys'][$_POST['uk_'.$i.'']]['fun'] = $_POST['fun_'.$i.''];
			
			// then check if default set - used if column values are null
			if( !empty( $_POST['def_'.$i.''] ) )
			{
				$csv['usermetakeys'][$_POST['uk_'.$i.'']]['def'] = $_POST['def_'.$i.''];				
			}
			
			++$used;
		}
		
		++$i;
	}

	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$filename,$csv ) )
	{
		eci_mes( __('User Meta Keys Saved'),'You have created '.$used.' User Meta Keys for adding meta data to your accounts. You may now go to the
		Finish page where you will get an overview of your project and can begin creation blog accounts.' );
	}
	else
	{
		eci_err( __('No User Meta Keys Changed'),__('Wordpress did not detect any changes made to your User Meta Keys') );
	}
}


/**
 * Generates a username using a single value by incrementing an appended number until a none used value is found
 * @param string $username_base
 */
function eci_generateusername_singlevalue($username_base)
{
	$attempt = 0;
	$limit = 500;// maximum trys - would we ever get so many of the same username with appended number incremented?
	$exists = true;// we need to change this to false before we can return a value
	
	// ensure giving string does not already exist as a username else we can just use it
	$exists = username_exists( $username_base );
	if( $exists == false )
	{
		return $username_base;
	}
	else
	{
		// if $suitable is true then the username already exists, increment it until we find a suitable one
		while( $exists != false )
		{
			++$attempt;
			$username = $username_base.$attempt;
			
			// username_exists returns id of existing user so we want a false return before continuing
			$exists = username_exists( $username );
			
			// break look when hit limit or found suitable username
			if($attempt > $limit || $exists == false )
			{
				break;
			}
		}
		
		// we should have our login/username by now
		if ( $exists == false ) 
		{
			return $username;
		}	
	}
}
?>