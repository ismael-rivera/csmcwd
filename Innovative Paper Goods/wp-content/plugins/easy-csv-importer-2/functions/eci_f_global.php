<?php

# Processes The Submission Of A Cloaked URL - Forwarding Visitor To Real One
function eci_cloakedurlsubmission() 
{	

}


/**
 * Easy CSV Importer - Creates Wordpress Users Using Data In Table
 * @param unknown_type $csv
 * @param unknown_type $pro
 * @param unknown_type $spe
 * @param unknown_type $set
 * @param unknown_type $des
 * @param unknown_type $posts
 * @param unknown_type $output
 * @param unknown_type $filename
 */
function eci_createusers( $csv,$pro,$spe,$set,$des,$users,$output,$filename )
{
	// begin script timer
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    $scriptstarttime = $mtime;	
    	
	global $wpdb;

	// remove hooks/filters meant for manual actions - we can add entries here for other plugins also
	remove_filter('content_save_pre', 'eci_editpostsync');

	// increase events counter for campaign
	++$pro[$filename]['events'];
	
	// execute sql SELECT query to get records
	$records = $wpdb->get_results( 'SELECT * FROM '. $csv['sql']['tablename'] .' WHERE ecipostid IS NULL OR ecipostid = 0 LIMIT '. $users .'' );
	
	if( !$records )
	{
		if( $output ){eci_mes(__('No Records Available'),__('Either your project table does not have any imported data or all records have already been used.'));}
	}
	elseif( !isset($csv['usermetakeys']['user_email']['col']) )
	{
		// user email should not be set if user has not applied a column - we do not save NA not applicable selections
		if( $output ){eci_mes(__('Email Address Column Not Saved'),__('Please go back to the configuration page and select the column that holds email address. You must do this on the row already setup with user_email in the field.'));}
	}
	else
	{
		eci_log(__('Create Users'),
		'User creation procedure started',
		'Your project appears to have new database records, the plugin will not attempt to create users',
		$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	

		// prepare progress variables for output only - $pro array is updated as events happen
		$progress = array();
		$progress['createsuccess'] = 0;// number of users created and completed by update
		$progress['createfailed'] = 0;// number of users that did not complete at any stage
		$progress['adoptsucces'] = 0;// number of users that were adopted
		$progress['adoptfailed'] = 0;// number of users that were attempted to be adopted but failed

		// begin looping through all records
		foreach( $records as $record )
		{	
			// create switch to indicate failed user creation attempt
			$failed = false;
			
			// username_exists should change this to false else we need to generate a username
			$exists = true;
			
			// capture failure reason for output
			$failreason = '';
			
			// put the records data into an array with the key being column names
			$recordarray = eci_recordarray( $csv,$record );
			
			// get the email address from current record
			$user_email = $recordarray[ eci_cleansqlcolumnname($csv['usermetakeys']['user_email']['col']) ];
			
			// ensure email address is not already in use
			$user_id = email_exists($user_email);
			
			if( $user_id == false)
			{
				// create username
				if( isset($csv['usermetakeys']['user_login']['col']) )
				{
					// check if username in data exists already or not
					$user_login_sql = eci_cleansqlcolumnname($csv['usermetakeys']['user_login']['col']);
					$username = $recordarray[$user_login_sql];
					$exists = username_exists( $username );
					if($exists != false)
					{
						$username = eci_generateusername_singlevalue($username);	
					}
				}
				else
				{
					$username = preg_replace('/([^@]*).*/', '$1', $user_email );
					$username = eci_generateusername_singlevalue($username);
				}
				
				$user_id = wp_create_user( $username, wp_generate_password( 12, false ), $user_email );
			}// end is email used
						
			// start column counter
			$col = 0;
	
			// only continue if $user_id is not false and no error returned by wp_create_user
			if( !$user_id || is_wp_error($user_id) )
			{				
				eci_log( __('Create User Failed'),
				'user account create failed',
				'The plugin attempted to create a new user but failed',
				$filename,$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );	
	
				// creating base post failed			
				++$progress['createfailed'];
				++$pro[$filename]['postsfailed'];
				update_option( 'eci_pro',$pro );
			}
			else
			{				
				eci_log( __('Created User'),
				'New user ID '.$user_id.'',
				'A user with ID '.$user_id.' was created',
				$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	
	
				#############  TO BE COMPLETE   ###################
				// add required custom fields
				//eci_addusermetadata( $user_id,$recordarray,$csv,$filename,$set );
				
				// update this record with user id in the project database table - we can use the ecipostid column 
				$wpdb->query('UPDATE '. $csv['sql']['tablename'] .' SET ecipostid = '.$user_id.',eciapplied = NOW() WHERE eciid = '.$record->eciid.'');

				// creating base post failed			
				++$progress['createsuccess'];
				++$pro[$filename]['postscreated'];
				
				// update progress array
				update_option( 'eci_pro',$pro );
			}
			
			// clear cache etc then store memory usage - our aim is to minimise the memory usage on a single loop
			$wpdb->flush();
			eci_status('memuse_create', memory_get_usage(), 0);
		
			// if a single post has been requested do not unset variables, if many have then unset them
			if( $users > 1 )
			{
				unset($my_post);
				unset($recordarray);
				unset($duplicatearray);
				unset($uniquearray);
			}

			// check timer
			$mtime = microtime(); 
	   		$mtime = explode(" ",$mtime); 
	   		$mtime = $mtime[1] + $mtime[0]; 
	   		$endtime = $mtime; 
	   		$scripttotaltime = ($endtime - $scriptstarttime); 
	   		if($scripttotaltime > ECIMAXEXE){$stopscript = true;}else{$stopscript = false;}		
			
			// if were breaking due to hitting limit we will log it
			if($stopscript == true)
			{
				eci_log( 'Execution Limit','ECI reached execution limit on user creation',
				'ECI reached the execution limit of '.ECIMAXEXE.' seconds during a user creation event and stopped',
				$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );			
				break;		
			}			
		}// end of each record
		
		eci_log( __('Create Users'),
		'Users Created:'.$progress['createsuccess'].' Create Failed:'.$progress['createfailed'].' Adopted:'.$progress['adoptsucces'].' Adopt Failed:'.$progress['adoptfailed'].'',
		'Users Created:'.$progress['createsuccess'].' Create Failed:'.$progress['createfailed'].' Adopted:'.$progress['adoptsucces'].' Adopt Failed:'.$progress['adoptfailed'].'',		
		$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	
		
		// if a single post is created, add some of the posts details to the output
		$singlepost = '';
		if( $users == 1 )
		{	
			$singlepost = '<h4>User Details</h4>
			Coming soon<br />';
		}
		
		// complete output if requested
		if( $output )
		{						
			$resultmes = '<h4>Project Progress (all events)</h4>
			Users Created Success: '.$pro[$filename]['postscreated'].'<br />
			Users Created Failed: '.$pro[$filename]['postsfailed'].' (usually because email address already used)<br />
			<p>See the log for details on any failed results</p>';
			
			eci_mes( 'User Creation Complete',$resultmes . $singlepost );
		}
		
		// return the last user ID
		return $user_id;
	}
}

function eci_formatfilesize($size) 
{
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) 
      { 
      	return('n/a'); 
      } 
      else 
      {
      	return (round($size/pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[$i]); 
      }
}										

/**
 * used to display ads on interface, parameters include minimum ad level required and what type of ad
 * Upgrade: make it work with webtechglobal ad server api and plugin
 * @param string $type (adsense,custom,random)
 * @param integer $min (minimum ad level triggered for this ad to begin showing)
 * @param string $place (term used to determine where the ad is and this then determines what ads to show)
 */
function eci_displayads($type,$min,$place)// $name may be set to false, it is only used when a specific ad is to be called, the rest will use random ads etc
{
	$adlevel = eci_getadlevel();
	
	if( $adlevel['switch'] == true && $adlevel['lev'] > 0 && $adlevel['lev'] >= $min )
	{
		if( $adlevel['lev'] == 3 )
		{
			// when api system is in place this will be done using a query to find suitable ads
			if( $place == 'homeblog2blog' )
			{
				// adsense horizontal links
				echo '<script type="text/javascript"><!--
				google_ad_client = "ca-pub-4923567693678329";
				/* ECI Home Blog2Blog */
				google_ad_slot = "5604643468";
				google_ad_width = 234;
				google_ad_height = 60;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>';
			}
			elseif( $place == 'homesupport' )
			{
				// adsense vertical links
			}
		}
		elseif( $adlevel['lev'] == 6 )
		{
		
		}
		elseif( $adlevel['lev'] == 12 )
		{
			
		}		 
		 
	}

}



// creates custom fields - used in update procedures and post creation
function eci_addcustomfields( $id,$recordarray,$csv,$filename,$set )
{		
	// loop through custom fields - get
	if( isset( $csv['customfields'] ) )
	{
		// loop through custom fields
		foreach( $csv['customfields'] as $key => $cf )
		{
			if( isset( $cf['col'] ) && empty( $recordarray[ eci_cleansqlcolumnname($cf['col']) ] ) && !isset( $cf['def'] ) )
			{
				$value = ' ';
			}
			elseif( isset( $cf['col'] ) && !empty( $recordarray[ eci_cleansqlcolumnname($cf['col']) ] ) )
			{
				$value = $recordarray[ eci_cleansqlcolumnname($cf['col']) ];
			}
			else
			{
				// custom field has no column but must have a default value
				$value = $cf['def'];
			}
			
			// apply data functions
			if( $cf['fun'] != 'NA' )// use column value to create keywords for custom field
			{
				// apply the required functon
				if( $cf['fun'] == 'keywordgenerator' )
				{
					$value = eci_createkeywords( $value,10,$set['tagsexclude'] );
				}
				elseif( $cf['fun'] == 'classipressimages' )
				{
					// generate a string of comma seperated url by storing images local
					$value = eci_localmediaurlstring($value,$id);
				}
				elseif( $cf['fun'] == 'shopperpressimages' )
				{
					// generate a string of comma seperated url by storing images local
					$value = eci_localmediaurlstring($value,$id);
				}
			}

			if( !add_post_meta($id, $key, $value))
			{
				eci_log( __('Update Post Meta'),
				'Failed for post ID:'.$id.' Key:'.$key.' Value:'.$value.'',
				'The plugin attempted to update custom fields/post meta but failed, this mustb e investigated. The key being updated is '.$key.' and for post '.$id.'',
				$filename,$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );	
			}
			
		}
	}
}

// creates custom fields - used in update procedures and post creation
function eci_updatecustomfields( $id,$recordarray,$csv,$filename,$set )
{		
}

function eci_relativeitems_display($method,$style,$itemsarray)
{
	// loop through $itemsarray build display with table,form etc
	foreach($itemsarray as $postid => $post)
	{
		echo $post->ID;
	}
}

function eci_gettitlearray( $csv, $pro, $filename )
{
	if( $pro[ $filename ]['filesettype'] == 'multifile' )
	{			
		if( $pro[$filename]['multifilelev'] == 'parent' )
		{
			return $csv['format']['titlesmulti'];
		}
		else
		{
			$csvtemp = get_option('eci_'.basename($pro[$filename]['multifileset'][0]));
			return $csvtemp['format']['titlesmulti'];
		}
	}
	else
	{
		return $csv['format']['titles'];			
	}			
}

/**
 * Checks if automatic post updating is active for the giving project
 * Checks global $set value and $csv project value
 * @param unknown_type $filename
 */
function eci_is_updating_active($filename)
{
	$set = get_option( 'eci_set' );
	$updatingstatus = false;	
	return $updatingstatus;
}

/**
 * Similiar to the eci_createposts function but this updates, not all parts of a post will be changed in this
 * @param object $posts
 */
function eci_updatethepost($post){}

# Returns Required Design Based On Conditional Criteria
function eci_postcreation_getlayouts( $parentcat,$des,$csv,$recordarray )
{	
	if( isset( $csv['conditions']['categorydesigns'][$parentcat] ) ) 
	{
		// category is in conditional design array
		if( isset( $des[ $csv['conditions']['categorydesigns'][$parentcat] ]) )
		{
			return $des[ $csv['conditions']['categorydesigns'][$parentcat] ];
		}
	}
	else
	{
		// does the user want value designs
		if( isset($csv['valdesign']) && isset($csv['valdesigncolumn']) &&
		isset($csv['conditions']['switches']['valdesign']) && $csv['conditions']['switches']['valdesign'] == true )
		{
			// we cannot assume that the user has paired every value in the selected column with a design
			// check if the current record exists within the value design configuration saved by the user
			if( isset( $csv['valdesign'][ $recordarray[$csv['valdesigncolumn']] ] ) )
			{
				if( isset( $des[ $csv['valdesign'][ $recordarray[$csv['valdesigncolumn']] ] ] ) )
				{
					return $des[ $csv['valdesign'][ $recordarray[$csv['valdesigncolumn']] ] ];
				}
			}
		}
		
		// default to the projects main selected design
		if( isset( $des[ $csv['maindesign'] ] ) )
		{
			return $des[ $csv['maindesign'] ];
		}
	}
	
    return false;	
}

function eci_datamodify_createposts( $csv,$recordarray )
{
	// apply numeric modifier - is numeric mod switched on ?
	if( isset( $csv['conditions']['switches']['numericmod'] ) && $csv['conditions']['switches']['numericmod'] == true )
	{
		if( isset( $csv['conditions']['numericmod']['modifier'] ) && is_numeric( $csv['conditions']['numericmod']['modifier'] )  
		&& isset( $csv['conditions']['numericmod']['symbol_1'] ) 
		&& isset( $csv['conditions']['numericmod']['columnid_1'] ) )
		{
			// apply required sum
			if( $csv['conditions']['numericmod']['symbol_1'] == 'multiply' )
			{
				$answer = $csv['conditions']['numericmod']['modifier'] * $recordarray[ $csv['conditions']['numericmod']['columnid_1'] ];
			}
			elseif( $csv['conditions']['numericmod']['symbol_1'] == 'subtract' )
			{
				$answer = $csv['conditions']['numericmod']['modifier'] * $recordarray[ $csv['conditions']['numericmod']['columnid_1'] ];				
			}					
			elseif( $csv['conditions']['numericmod']['symbol_1'] == 'add' )
			{
				$answer = $csv['conditions']['numericmod']['modifier'] * $recordarray[ $csv['conditions']['numericmod']['columnid_1'] ];
			}
			elseif( $csv['conditions']['numericmod']['symbol_1'] == 'divide' )
			{
				$answer = $csv['conditions']['numericmod']['modifier'] * $recordarray[ $csv['conditions']['numericmod']['columnid_1'] ];	
			}
			
			// change old numeric value to the answer we get
			$recordarray[ $csv['conditions']['numericmod']['columnid_1'] ] = $answer;
		}
	}
	
	return $recordarray;
}

/**
 * Easy CSV Importer - Creates Wordpress Post Using Data In Table
 * @param unknown_type $csv
 * @param unknown_type $pro
 * @param unknown_type $spe
 * @param unknown_type $set
 * @param unknown_type $des
 * @param unknown_type $posts
 * @param unknown_type $output
 * @param unknown_type $filename
 */
function eci_createposts( $csv,$pro,$spe,$set,$des,$posts,$output,$filename )
{
	// code
	$posts = '5123';
	
	// begin script timer
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    $scriptstarttime = $mtime;		
		
	global $wpdb;

	// remove hooks/filters meant for manual actions - we can add entries here for other plugins also
	remove_filter('content_save_pre', 'eci_editpostsync');

	// increase events counter for campaign
	++$pro[$filename]['events'];
	
	// execute sql SELECT query to get records
	$records = $wpdb->get_results( 'SELECT * FROM '. $csv['sql']['tablename'] .' WHERE ecipostid IS NULL OR ecipostid = 0 LIMIT '. $posts .'' );
	
	if( !$records )
	{
		eci_log(__('Create Posts'),__('No posts created as no records were available'),'Your projects table does not have any none used records so no more posts were created in this event',$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );if( $output ){eci_mes(__('No Records Available'),__('Either your project table does not have any imported data or all records have already been used.'));}
	}
	else
	{
		eci_log(__('Create Posts'),'Post creation procedure started','Your project appears to have new database records, the plugin will not attempt to create posts',$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );$progress = array();$progress['createsuccess'] = 0;$progress['createfailed'] = 0;$progress['adoptsucces'] = 0;$progress['adoptfailed'] = 0;$posts = $posts - '3123';

		// begin looping through all records
		foreach( $records as $record )
		{	
			// record the $record id as last record used to attempt post creation - used in debugging when a failure happens
			if(isset($set['recordlastid']) && $set['recordlastid'] == 'Yes')
			{
				$pro[$pro['current']]['lastrecordid'] = $record->eciid;
				update_option( 'eci_pro',$pro );
			}

			// put the records data into an array with the key being column names
			$recordarray = eci_recordarray( $csv,$record );
			
			// modify data values based on conditions
			$recordarray = eci_datamodify_createposts( $csv,$recordarray );
			
			// start column counter
			$col = 0;
			
			// get category id array
			if(isset($csv['categories']) || isset($csv['singlecategory']) && $csv['singlecategory'] != NULL && $csv['singlecategory'] != 'NA')
			{
				$cats = eci_categorise( $csv,$pro,$spe,$set,$filename,$record,$output,$recordarray );
			}
			
			// if no $category set the default as 1 Uncategorised
			if( !isset( $cats ) || $cats == NULL || empty( $cats ) )
			{
				$cats[0] = 1; 
			}
								
			// get design templates in an array
			$designarray = eci_postcreation_getlayouts( $cats[0],$des,$csv,$recordarray );

			// create post and return the post object
			$my_post = eci_createbasepost(date("Y-m-d h:i:s"),gmdate("Y-m-d h:i:s"),$designarray,'pending',$recordarray,$csv);
	
			// only continue if $my_post is not false
			if( !$my_post )
			{
				// creating base post failed			
				++$progress['createfailed'];
				++$pro[$filename]['postsfailed'];
				update_option( 'eci_pro',$pro );
			}
			elseif( $my_post )
			{
				eci_log( __('Created Post'),
				'Base post ID '.$my_post['ID'].' created',
				'The plugin requires a base post then updates it as part of the post creation process. A new base post with ID '.$my_post['ID'].' was created',
				$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	
				
				// add categories to my_post object
				$my_post['post_category'] = $cats;
				
				// process design code and insert data values over tokens - return design object again
				$designarray = eci_populatedesign( $designarray, $recordarray, $csv );		
				
				// build the rest of $my_post
				$my_post['post_title'] = $designarray['title'];
				$my_post['post_content'] = $designarray['content'];
				
				// populate $my_post object with any further data required by special functions
				$my_post = eci_populatemypost_specialfunctions( $my_post,$recordarray,$csv,$set );

				// add required custom fields
				eci_addcustomfields( $my_post['ID'],$recordarray,$csv,$filename,$set );
			
				// add SEO meta
				eci_seo($csv,$recordarray,$designarray,$my_post['ID']);
									
				// apply encoding to $my_post object - based on user settings - should have final $my_post content values before here
				$my_post = eci_postencoding( $csv,$my_post,$set );

				// establish publish date and add to $my_post object
				$my_post = eci_postdate( $csv,$my_post,$recordarray,$set,$filename );
				
				// if user has not set own post name - create it now
				if( !isset( $my_post['post_name'] ) )
				{
					$my_post['post_name'] = sanitize_title( $my_post['post_title'] );
				}	
				
				// apply publish date to last publish date value for tracking incremental publishing
				$pro[$filename]['lastpublishdate'] = $my_post['post_date'];		
	
				// using final publish date - make final decision on post status (users selected or future for schedule posts)
				$my_post = eci_poststatus( $csv,$my_post,$pro );
				
				// conditions content replace
				$my_post = eci_conditionreplace( $my_post, $csv );

				// duplication check
				$duplicatearray = eci_duplicateposts( $csv,$my_post,$filename,$set );

				// process post adoption - give false for no use or post id for adopted post
				eci_adopt( $adoptpostid,$my_post );
				
				// add eci custom fields
				eci_postbuildmeta( $my_post['ID'], $filename, $record->eciid, $csv['sql']['tablename'] );
				
				// update this record with its matching post id in the project database table
				$wpdb->query('UPDATE '. $csv['sql']['tablename'] .' SET ecipostid = '.$my_post['ID'].',eciapplied = NOW() WHERE eciid = '.$record->eciid.'');
				
				// update post - apply users selected status - ID returned on success
				//$my_post['post_status'] = $csv['poststatus'];
				$my_post['ID'] = wp_update_post( $my_post ); 		
				
				// update project progress if success
				if( $my_post['ID'] )
				{
					++$pro[$filename]['postscreated'];
					++$progress['createsuccess'];
				}		
				elseif( !$my_post['ID'] )
				{
					eci_log( 'Create Post Fail','No post id returned by update',
					false,
					$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	
					++$pro[$filename]['postsfailed'];
					++$progress['createfailed'];
				}
				
				// update progress array
				update_option( 'eci_pro',$pro );
			}
			
			// put the post id into variable for returning as the $my_post object is destroyed
			$postid = $my_post['ID'];
			
			// clear cache etc then store memory usage - our aim is to minimise the memory usage on a single loop
			$wpdb->flush();
			eci_status('memuse_create', memory_get_usage(), 0);
		
			// if a single post has been requested do not unset variables, if many have then unset them
			if( $posts > 1 )
			{
				unset($my_post);
				unset($recordarray);
				unset($duplicatearray);
				unset($uniquearray);
			}	

			// check timer
			$mtime = microtime(); 
	   		$mtime = explode(" ",$mtime); 
	   		$mtime = $mtime[1] + $mtime[0]; 
	   		$endtime = $mtime; 
	   		$scripttotaltime = ($endtime - $scriptstarttime); 
	   		if($scripttotaltime > ECIMAXEXE){$stopscript = true;}else{$stopscript = false;}		
			
			// if were breaking due to hitting limit we will log it
			if($stopscript == true)
			{
				eci_log( 'Execution Limit','ECI reached execution limit on post creation',
				'ECI reached the execution limit of '.ECIMAXEXE.' seconds during a post creation event and stopped',
				$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );
				break;					
			}			
		}// end of for each record
		
		eci_log( __('Create Posts'),
		'Posts Created:'.$progress['createsuccess'].' Create Failed:'.$progress['createfailed'].' Adopted:'.$progress['adoptsucces'].' Adopt Failed:'.$progress['adoptfailed'].'',
		'Posts Created:'.$progress['createsuccess'].' Create Failed:'.$progress['createfailed'].' Adopted:'.$progress['adoptsucces'].' Adopt Failed:'.$progress['adoptfailed'].'',		
		$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	
		
		// if a single post is created, add some of the posts details to the output
		$singlepost = '';
		if( $posts == 1 )
		{		
			if( !isset( $my_post['tags_input'] ) )
			{
				$my_post['tags_input'] = 'No Tags Added';
			}
			
			$singlepost = '<h4>Post Details</h4>
			Title: '.$my_post['post_title'].'<br />
			ID: '.$my_post['ID'].'<br />
			Publish Date: '.$my_post['post_date'].'<br />
			Tags: '.$my_post['tags_input'].'<br />';
			
			$singlepost	.= '<h4>Featured Image</h4>';
			$singlepost	.= 'No Featured Images In Free Edition';
			

			$singlepost	.= '<h4>Post Content Dump</h4>
			'.$my_post['post_content'].'<br />';
		}
		
		// complete output if requested
		if( $output )
		{
			$resultmes = '<h4>Post Creation Event Results</h4>';
			
			if( isset( $progress['createsuccess'] ) && $progress['createsuccess'] != 0 )
			{
				$resultmes .= 'Post Create Success: '.$progress['createsuccess'].'<br />';
			}
						
			if( isset( $progress['createfailed'] ) && $progress['createfailed'] != 0 )
			{
				$resultmes .= 'Post Create Failed: '.$progress['createfailed'].'<br />';
			}
						
			if( isset( $progress['adoptsucces'] ) && $progress['adoptsucces'] != 0 )
			{
				$resultmes .= 'Post Adopt Success: '.$progress['adoptsucces'].'<br />';
			}
						
			if( isset( $progress['adoptfailed'] ) && $progress['adoptfailed'] != 0 )
			{
				$resultmes .= 'Post Adopt Failed: '.$progress['adoptfailed'].'<br /><br />';
			}
			
			$resultmes .= '<h4>Project Progress (all events)</h4>
			Posts/Pages Created: '.$pro[$filename]['postscreated'].'<br />
			Categories Created: '.$pro[$filename]['catscreated'].'<br />
			Tags Created: '.$pro[$filename]['tagscreated'].'<br />
			Project Records: '.$pro[$filename]['rowsinsertsuccess'].'<br /><br />';
			
			eci_mes( 'Post creation event complete for '.$filename.'',$resultmes . $singlepost );
		}	
		
		// return the last POST ID - for use when plugin requests a single post can confirm its ID to the user
		return $postid;
	}
}

# Count And Saves Current CSV Files Rows For Giving Project And Path
function eci_countcsvrows( $filename,$csv )
{}

# Displays A Message Only If Visitor Is Admin
function eci_adminmes( $title,$message,$type )
{
	if( is_admin() && $type == 'mes' )
	{
		eci_mes( $title,$message );
	}
	elseif( is_admin() && $type == 'err' )
	{
		eci_err( $title,$message );
	}
}

# Calculates Current Progress For Update - Both Insert And Update Counters Reset For Update
function eci_progress( $filename )
{
	$pro = get_option( 'eci_pro' );
	$progress = $pro[$filename]['rowsupdatesuccess'] + $pro[$filename]['rowsupdatefail'] + $pro[$filename]['rowsinsertsuccess'] + $pro[$filename]['rowsinsertfail'];
	return $progress;
}

/**
 * Gets and echos the total lines in a csv file
 * @uses eci_csvfileexists
 * @param filename $filename
 * @param array $pro
 */
function eci_displaycsvlines($filename,$pro)
{
    $exist = eci_csvfileexists($filename, $pro);                    	
    if($exist)
    {	
       echo count(file($pro[$filename]['filepath']));
    }
}
                                
/**
 * Checks if a file exists using giving path, if not exist sets giving project to paused
 * @param string $filepath
 * @param array $pro
 */
function eci_csvfileexists($filename,$pro)
{
	$exists = file_exists($pro[$filename]['filepath']);
	if($exists)
	{
		return true;
	}
	else
	{
		$pro[$filename]['status'] = 'Paused';
		update_option('eci_pro',$pro);
		return false;
	}
}

/**
 * Returns filetime but also currently resets project project and renames file if pre-cleaned file exists
 * we should really split this into seperate functions but nothing else would use them right now
 * @param filename $filename
 * @param array $pro
 * @param array $csv
 * @param array $set
 */
function eci_filetime( $filename,$pro,$csv,$set )
{
	// create array for return
	$filedateresults = array();

	// is the project name/csv file name a cleaned name (old one will be stored in $csv profile)
	if( isset( $csv['precleanfilepath'] ) )
	{			
		// check for a file existing with the old name
		if ( file_exists( $csv['precleanfilepath'] ) ) 
		{		
			$newpathexists = true;
			$newpathdeleted = false;
			
			// delete existing project file - the cleaned one - making ready for cleaning the new one
			if( !unlink( $pro[$filename]['filepath'] ) )
			{
				eci_log( __('File Delete Failure'),
				'Rename and replace file failed',
				'A project appears to have a new csv file that needs renamed to match the projects name but the old file could not be deleted. This is often caused by server permissions and must be resolved.',
				$filename,$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
			}
			else
			{
				// now do the rename, use the pre clean file path in $csv and the permanent path from $pro
				if( !rename( $csv['precleanfilepath'], $pro[$filename]['filepath'] ) )
				{
					eci_log( __('File Rename Failure'),
					'File rename failure',
					'Your project has a new file with an old filename (previously renamed for suitability with the plugin), it must be renamed but it could not be. Please investigate this.',
					$filename,$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
				}		
			}
		}		
	}
	
	// ensure project file still exists
	$exists = eci_csvfileexists($filename,$pro);// this function pauses project if file missing
	
	if( $exists == true )
	{
		// get project file path and get the files time
		$stamp['current'] = filemtime( $pro[$filename]['filepath'] );
		$stamp['changed'] = 'No';
		
		// when stored time does not equal time on file, we reset progress, this is part of triggering automated events if activated
		if( isset( $csv['format']['currentfiledate'] ) && $stamp['current'] != $csv['format']['currentfiledate'] )
		{					
			// add old date to array before it is changed
			$stamp['previous'] = $csv['format']['currentfiledate'];
			
			// currentfiledate as stored in array (not new one) now becomes previousfiledate
			$csv['format']['previousfiledate'] = $csv['format']['currentfiledate'];
			
			// new file date now becomes currentfiledate - this difference between two triggers updating events
			$csv['format']['currentfiledate'] = $stamp['current'];
			$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
			update_option( 'eci_'.$filename, $csv );
			
			// must also count number of rows in csv file
			eci_countcsvrows( $filename,$csv );
			
			// we also need to reset import/update progress to allow events to continue
			eci_resetprogress( $filename,$pro,'eci_filetime detected new file',$set );
			
			// change the changed value in array
			$stamp['changed'] = 'Yes';
		}	
		
		return $stamp;
	}
	else
	{
		eci_log('Missing File', 'Time stamp check attempt but no file', 
		'A project is active and the plugin needed to check the projects csv file datestamp but
		the file appears to be missing.', $filename, $set, 'Critical', __LINE__, __FILE__, __FUNCTION__);
	}
	
	return false;
}

# Resets Progress Counters For Project In Order To Make Auto Import or Update Continue
function eci_resetprogress( $filename, $pro, $reason, $set )
{
	$pro[$filename]['rowsupdatesuccess'] = 0;
	$pro[$filename]['rowsupdatefail'] = 0;
	$pro[$filename]['rowsinsertsuccess'] = 0;
	$pro[$filename]['rowsinsertfail'] = 0;
	$pro[$filename]['reset'] = time();
	if( update_option( 'eci_pro', $pro ) )
	{
		eci_log( 'Schedule','Progress reset complete. Reason: '.$reason.'',
		'Project progress statistics have been reset and all counters will start from zero. The reason: '.$reason.'',
		'Operation',$set,'Low',__LINE__,__FILE__,__FUNCTION__ );
	}
	else
	{
		eci_err( 'Schedule','Failed to reset progress for reason: '.$reason.'','Operation',$set,'Critical' );
	}
	
	return $pro;
}

# Adds Final Publish Date To $my_post Object
function eci_postdate( $csv,$my_post,$recordarray,$set,$filename )
{					
	if( isset( $csv['specials']['col']['dates_col'] ) && isset( $csv['specials']['state']['dates_col'] ) 
	&& $csv['specials']['state']['dates_col'] == 'On' 
	&& isset( $csv['datemethod'] ) && $csv['datemethod'] == 'data' )
	{		
		// if string to time could not be done, output some help information
		if ( ( $timestamp = strtotime( $recordarray[ $csv['specials']['col']['dates_col'] ] ) ) === false ){} 
		else 
		{
			$my_post['post_date'] =  date("Y-m-d H:i:s", $timestamp);
			$my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $timestamp );
		}
	}
	elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'random' )
	{	
		$time = rand(
		mktime( 23, 59, 59, $set['rd_monthstart'], $set['rd_daystart'], $set['rd_yearstart'] ),
		mktime( 23, 59, 59, $set['rd_monthend'], $set['rd_dayend'], $set['rd_yearend'] ) );// end of rand 
		$my_post['post_date'] = date("Y-m-d H:i:s", $time);
		$my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
	}		
	elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'increment' )
	{
		if( isset( $pro['lastpublishdate'] ) )
		{
			$lastdate = strtotime( $pro[$filename]['lastpublishdate'] );
			$increment = rand( $set['incrementstart'], $set['incrementend'] );
			$time = $lastdate + $increment;
			$my_post['post_date'] = date("Y-m-d H:i:s", $time);	
			$my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
		}
		if( !isset( $pro['lastpublishdate'] ) )
		{
			$time = mktime( 23, 59, 59, $set['incrementmonthstart'], $set['incrementdaystart'], $set['incrementyearstart'] );
			$my_post['post_date'] = date("Y-m-d H:i:s", $time);
			$my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
		}
	}
	elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'default' )
	{
		$my_post['post_date'] = date( "Y-m-d H:i:s",time() );
		$my_post['post_date_gmt'] = gmdate( "Y-m-d H:i:s",time() );
	}					
					
	return $my_post;
}

# Ensures Pre-Made Tags Are Valid 
function eci_createtags_premade($str) 
{
	// split passed value - expecting a comma delimited string of values including phrases
	$splitstr = @explode(",", $str);
	$new_splitstr = array();
	foreach ($splitstr as $spstr) 
	{
		// ensure individual value is not already in tags array
		if (strlen($spstr) > 2 && !(in_array(strtolower($spstr), $new_splitstr))){$new_splitstr[] = strtolower($spstr);}
	}
	return @implode(", ", $new_splitstr);
}

# Adds Values Too Giving $my_post Object Based On Special Functions Settings
function eci_populatemypost_specialfunctions( $my_post,$recordarray,$csv,$set )
{ 
	// wordpress post excerpt value
	if( isset( $csv['specials']['col']['excerpt_col'] ) && isset( $csv['specials']['state']['excerpt_col'] ) && $csv['specials']['state']['excerpt_col'] == 'On' )
	{							
		$my_post['post_excerpt'] = eci_createexcerpt( $recordarray[ eci_cleansqlcolumnname($csv['specials']['col']['excerpt_col']) ], $set['excerptlimit'] ); 
	}		
	
	// tags - premade tags override generated tags
	if( isset( $csv['specials']['col']['madetags_col'] ) && isset( $csv['specials']['state']['madetags_col'] ) && $csv['specials']['state']['madetags_col'] == 'On'   )
	{ 	
		$my_post['tags_input'] = eci_createtags_premade( $recordarray[ eci_cleansqlcolumnname($csv['specials']['col']['madetags_col']) ] );
	}		
	elseif( isset( $csv['specials']['col']['tags_col'] ) && isset( $csv['specials']['state']['tags_col'] ) && $csv['specials']['state']['tags_col'] == 'On' )
	{
		$my_post['tags_input'] = eci_createtags( $recordarray[ eci_cleansqlcolumnname($csv['specials']['col']['tags_col']) ],$set['tagschars'], $set['tagsnumeric'], $set['tagsexclude'] );
	}

	// post name/permalink
	if( isset( $csv['specials']['col']['permalink_col'] ) && isset( $csv['specials']['state']['permalink_col'] ) && $csv['specials']['state']['permalink_col'] == 'On' )
	{
		$my_post['post_name'] = $recordarray[ eci_cleansqlcolumnname($csv['specials']['col']['permalink_col']) ];
	}
	
	return $my_post;
}

# Extracts And Returns Comma Seperated Keywords From String Of Text
function eci_createtags($str, $length, $tagsnumeric, $exclude) 
{
	// replace full stops and commas with a space
	$str = str_replace(array(",",".")," ", $str );

	// strip html
	$str = eci_striphtmltags( $str );

	// shorten string
	$str = eci_truncatestring( $str, $length);

	// explode words into array - we'll loop through these
	$splitstr = explode(" ",$str);

	// start new array
	$new_splitstr = array();

	// explode exclusions into array
	$exclude = explode(",", $exclude);

	// loop through each word
	foreach ( $splitstr as $spstr ) 
	{
		if( $tagsnumeric == 'Exclude' )
		{	// numeric only values will be removed
			if (strlen($spstr) > 2 
			&& !(in_array(strtolower($spstr), $new_splitstr)) 
			&& !(in_array(strtolower($spstr), $exclude ) ) 
			&& !is_numeric($spstr)) 
			{
				#####  IMPROVE TO ALLOW FORMAT SUCH AS CAPTIAL FIRST LETTER,ALL LOWER CASE ETC #####
				//$new_splitstr[] = strtolower($spstr);
				$new_splitstr[] = $spstr;
			}
		}
		elseif($tagsnumeric == 'Include')
		{	
			// numeric only values will be included
			if ( strlen( $spstr ) > 2 
			&& !( in_array( strtolower( $spstr ), $new_splitstr ) ) 
			&& !( in_array( strtolower( $spstr ), $exclude ) ) ) 
			{
				#####  IMPROVE TO ALLOW FORMAT SUCH AS CAPTIAL FIRST LETTER,ALL LOWER CASE ETC #####
				//$new_splitstr[] = strtolower($spstr);
				$new_splitstr[] = $spstr;
			}
		}
	}

	return @implode(",", $new_splitstr);
}

# Creates Comma Seperated String Of Keywords Based On Settings Including Exclusions
function eci_createkeywords( $str, $words, $exclude ) 
{
	// replace fukll stops and commas with a space so they are not included in keywords
	$str = str_replace( array(",",".")," ", $str );

	// strip html
	$str = eci_striphtmltags( $str );

	// explode words using explode which puts each seperate word in array
	$splitstr = @explode(" ", $str);
	
	// start new array
	$new_splitstr = array();
	
	$exclude = @explode(",", $exclude);
	
	// loop through each word
	foreach ($splitstr as $spstr) 
	{	// ensure word is more than 2 characters, is not already in string and is not in exclude array
		if (strlen($spstr) > 2 
		&& !(in_array(strtolower($spstr), $new_splitstr)) 
		&& !(in_array(strtolower($spstr), $exclude))) 
		{
			//$new_splitstr[] = strtolower($spstr);
			$new_splitstr[] = strtolower($spstr);
		}
	}
	
	// implode array of words into a string then return
	return @implode(",", $new_splitstr);
}

# Prepares A String For Use As An SQL Column Name (removes spaces etc)
function eci_cleansqlcolumnname( $column )
{
	global $wpdb;
	$column = $wpdb->escape( $column );
	return str_replace( array( ",","/","'\'"," ",".",'-','#','_'),"", strtolower($column) );
}

# Takes A SQL Returned Record And Puts It Into Array With Key Being Column Titles		
function eci_recordarray( $csv,$record )
{
	$recordarray = array();
	foreach($csv['format']['titles'] as $column)
	{
		$sqlcolumn = eci_cleansqlcolumnname( $column );
		
		eval('$data = $record->$sqlcolumn;');
		
		$recordarray[$sqlcolumn] = $data;
	}
	return $recordarray;
}			

# Replaces Tokens In Post Title With Actual Data
function eci_strreplacetokens($value,$data,$column)
{
	$token1 = '+'. $column .'+';
	$value = str_replace($token1, $data, $value);
	$token2 = 'x'. $column .'x';
	$value = str_replace($token2, $data, $value);

	return $value;
}

# Cleans And Returns Filenames
function eci_cleanfilename( $filename )
{
	#################### APPLY ARRAY FOR SEARCHED VALUES ON ONE LINE ###################
	$filename = str_replace ( '-' , '' ,  $filename );
	$filename = str_replace ( '_' , '' ,  $filename );
	$filename = str_replace ( ' ' , '' ,  $filename );
	$filename = str_replace ( ')' , '' ,  $filename );
	$filename = str_replace ( '(' , '' ,  $filename );
	############ ENSURE CLEANUP DONE ON AUTO FILE CECKIN   #################
	return $filename;
}

# Replaces Values In Post Content or Post
function eci_conditionreplace( $my_post, $csv )
{
	// is value swap active
	if( isset( $csv['conditions']['switches']['valueswap'] ) && $csv['conditions']['switches']['valueswap'] == true )
	{
		// are any value swap conditions entered?
		if( isset( $csv['conditions']['valueswap'] ) )
		{
			// loop through each value swap
			foreach( $csv['conditions']['valueswap'] as $key => $part )
			{
				if( $part['type'] == 'content' )
				{
					$my_post['post_content'] = str_replace( $part['old'],$part['new'], $my_post['post_content'] );
				}
				elseif( $part['type'] == 'title' )
				{
					$my_post['post_title'] = str_replace( $part['old'],$part['new'], $my_post['post_title'] );
				}				
				elseif( $part['type'] == 'titlecontent' )
				{
					$my_post['post_content'] = str_replace( $part['old'],$part['new'], $my_post['post_content'] );
					$my_post['post_title'] = str_replace( $part['old'],$part['new'], $my_post['post_title'] );
				}				
				elseif( $part['type'] == 'everything' )
				{
					$my_post['post_content'] = str_replace( $part['old'],$part['new'], $my_post['post_content'] );
					$my_post['post_title'] = str_replace( $part['old'],$part['new'], $my_post['post_title'] );
				}
			}
		}
	}

	return $my_post;
}				

// Returns Details About Where And When An Array Is Being Changed
function eci_arraychange( $line, $file )
{
	$change = array();
	$change['date'] = date("Y-m-d H:i:s");
	$change['time'] = time();
	$change['line'] = $line;
	$change['file'] = $file;
	return $change;
}

# Populates Giving Design Code With Records Data And Returns The designarray
function eci_populatedesign( $designarray, $recordarray, $csv )
{
	// loop through columns
	foreach( $csv['format']['titles'] as $column )
	{			
		// get database table version of csv file column name
		$sqlcolumn = eci_cleansqlcolumnname( $column );	
		
		//var_dump($recordarray);
		
		// put columns data value for current record into $data
		$data = $recordarray[ $sqlcolumn ];// record array is built using original column titles not sql version	

		// perform all token string replace actions here - standard post first
		$designarray['title'] = eci_strreplacetokens( $designarray['title'],$data,$column );
		$designarray['content'] = eci_strreplacetokens( $designarray['content'],$data,$column );
				
		// check if aioseo values were saved also - user may not have entered templates
		if( isset( $designarray['seotitle'] ) && !empty( $designarray['seotitle'] ) )
		{
			$designarray['seotitle'] = str_replace('+'. $column .'+', $data, $designarray['seotitle']);
			$designarray['seotitle'] = str_replace('x'. $column .'x', $data, $designarray['seotitle']);
		}
		
		if( isset( $designarray['seodescription'] ) && !empty( $designarray['seodescription'] ) )
		{
			$designarray['seodescription'] = str_replace('+'. $column .'+', $data, $designarray['seodescription']);	
			$designarray['seodescription'] = str_replace('x'. $column .'x', $data, $designarray['seodescription']);	
		}
	}				

	return $designarray;
}

# Checks If Giving Speed Profile Name Is In Use By Project And Returns False Or Project Name
function eci_speedprofile_inuse( $speedname,$pro )
{
	if( !$pro )
	{
		return false;
	}
	else
	{		
		$match = false;
		
		foreach( $pro as $key => $p )
		{		
			// find a project that is active and uses the giving speedprofile
			if( $key != 'records' && $key != 'current' && $key != 'arraydesc' && isset( $p['speed'] ) 
			&& $p['speed'] == $speedname && $p['status'] == 'Active' )
			{
				// we found a project using the speed profile with an overdue event so return it now
				return $key;
			}
			elseif( !isset( $p['speed'] ) )
			{
				$match = false;
			}
			else
			{
				$match = false;
			}
		}

		// return false if no suitable project found
		return $match;
	}
}

/**
 * Adds an entry to the ecilog.csv file
 * @param string $action (short title of what type of event happened)
 * @param string $textshort (short sentence of the result of the event, for developer debug )
 * @param string $textlong (long description for users, less developer terms) set to false to avoid the log being shown to users
 * @param string $type (Operation, project name, Failure)
 * @param array $set (options array)
 * @param string $priority (low, high, critical)
 * @param intiger $line (__LINE__)
 * @param file $line (__FILE__ )
 * @param string $function (__FUNCTION__ or false if not within a function)
 */
function eci_log( $action,$textshort,$testlong,$type,$set,$priority,$line,$file,$function )
{
	if( isset( $set['log'] ) && $set['log'] == 'Yes' )
	{		
		if ( !file_exists( ECIPATH.'ecilog.csv' ) ) 
		{
			$header = array();
			$header = array( 'DATE','ACTION','SHORT_DESCRIPTION','LONG_DESCRIPTION','PROJECT','PRIORITY','LINE','FILE','FUNCTION' );
			@$fp = fopen( ECIPATH.'ecilog.csv', 'w');
			
			if( !$fp )
			{	
				// use wordpress email system to email details of this issue to WTG			
			}
			else
			{
				@fputcsv($fp, $header );
			}
			@fclose($fp);
		} 
		else
		{
			// check file size - delete if over 300kb as stated on Log page
			if( filesize( ECIPATH.'ecilog.csv' ) > 307200 )
			{
				// delete file
				unlink( ECIPATH.'ecilog.csv' );
			}
		}

		$write = array();
		$write = array( date("Y-m-d H:i:s",time() ),$action, $textshort,$testlong, $type, $priority,$line,$file,$function );
		@$fp = fopen( ECIPATH.'ecilog.csv', 'a');	
		@fputcsv($fp, $write);
		@fclose($fp);	
	}
}

/**
 * Starting process to create categories outside of the post creation process
 * queries database/applicable columns, passes $record result to eci_categorise which extracts values
 * THEN builds an array of data values structured to match category set array
 * @param array $csv
 * @param array $pro
 * @param array $spe
 * @param array $set
 * @param filename $filename
 * @param integer $output - controls if interface output is used or not
 * @uses eci_categorise
 */
function eci_categoriseearly( $csv,$pro,$spe,$set,$filename,$output )
{
	// paid edition only
}

/**
 * Creates categories
 * @param unknown_type $csv
 * @param unknown_type $pro
 * @param unknown_type $spe
 * @param unknown_type $set
 * @param unknown_type $filename
 * @param unknown_type $record
 * @param unknown_type $output
 * @param unknown_type $recordarray
 */
function eci_categorise( $csv,$pro,$spe,$set,$filename,$record,$output,$recordarray )
{
	global $wpdb;
	
	// create array to hold our final category ID's, not the ID's applied to posts (this changed 7th July 2011)
	// this array is used within the script to extract the previous ID for using as parent
	$finalcat_array = array();
	
	// array to hold only the ID's that will be applied to posts - will show on post edit screen as checked
	$appliedcat_array = array();

	// set default category now - must be overwritten if default not being used
	### don't think we need to do this anymore $finalcat_array is not the returned array and $applied will default to zero at the end
	//$finalcat_array[0] = 1;// 1 is Uncategorised
	//$appliedcat_array[0] = 1;
		
	// set number of new categories created in total for all records all posts in this event
	// this is categories that need to be created, not those used (may already exist)
	$event_cats_created_count = 0;
	
	// Number of category ID added to finalcat_array
	$event_cats_used_count = 0;
	
	// apply Single Default Category if it is set, it overrides any other category settings
	if( isset( $csv['singlecategory'] ) && !empty( $csv['singlecategory'] ) && $csv['singlecategory'] != 'NA' )
	{
		$finalcat_array[0] = $csv['singlecategory'];
	}
	else
	{		
		// Was A Category Splitter Activated - This Takes Priority Over Normal Categories ($csv['specials']['col']['cats_col'])
		if( isset( $csv['specials']['col']['catsplitter_col'] ) && isset( $csv['specials']['state']['catsplitter_col'] ) && $csv['specials']['state']['catsplitter_col'] == 'On'
		|| isset( $csv['specials']['col']['catsplitter2_col'] ) && isset( $csv['specials']['state']['catsplitter2_col'] ) && $csv['specials']['state']['catsplitter2_col'] == 'On' )
		{
			if( $csv['specials']['state']['catsplitter_col'] == 'On' ){$splitcategory = $csv['specials']['state']['catsplitter_col'];$sep = '/';}
			elseif( $csv['specials']['state']['catsplitter2_col'] == 'On' ){$splitcategory = $csv['specials']['state']['catsplitter2_col'];$sep = '>';}
			
			// explode resulting string - pass data value of the single column
			$splitcategory = eci_cleansqlcolumnname( $splitcategory );
			$splitcats = explode($sep,$recordarray[ $splitcategory ],3);
			
			// loop through resulting array and do wp_create_category for each
			$foreach = 0;
			foreach( $splitcats as $id=>$catname )
			{	
				// put the resulting category id into array replacing old value
				$arg = array('description' => $categorydesc, 'parent' => $parent);
				$appliedcat_array[$foreach] = wp_insert_term(eci_categoryencoding($catname), "category", $arg);
								
				// do category apply check
				### current build not possible, will need interface that allows selection of separate category levels
				
				++$foreach;
				++$event_cats_created_count;
			}
		}
		elseif(!isset($csv['categories']))
		{
			// categories have not been set at all - return default
			return $finalcat_array; 
		}
		elseif(isset($csv['categories']))
		{
			// Possible Columns/Levels Per Cat Set
			// Currently the interface allows 3 levels of cat, this can be increased but allows control of loop limit
			$maxlevels = 3;
						
			// Get the number of cat sets there are
			$catsets_total = count($csv['categories']);
			
			// Loop Through Category Sets		
			for ($setsprocessed_count = 0; $setsprocessed_count < $catsets_total; $setsprocessed_count++) 
			{
				// Column Level - the current column level being processed - this is part of key only,begins with 1
				$collev = 1;
				
				// store cats used within set - reset per set - used to track if new cat is parent or needs a parent
				$set_cats_used = 0;
				
				// Loop Number Of Columns/Levels Possible
				// Will not always add a new column, user may have only 2 levels in a set and 3 or more in another
				for ($columnsprocessedcount = 0; $columnsprocessedcount < $maxlevels; $columnsprocessedcount++) 
				{
					if( isset( $csv['categories'][$setsprocessed_count]['cat'.$collev.''] ) 
					&& !empty( $csv['categories'][$setsprocessed_count]['cat'.$collev.''] ) 
					&& $csv['categories'][$setsprocessed_count]['cat'.$collev.''] != 'NA' )
					{
						// set default current cat id
						$catid = 0;
						
						// Get Category Term
						$cleansqlcolumn = eci_cleansqlcolumnname($csv['categories'][$setsprocessed_count]['cat'.$collev.'']);
												
						$catterm = $recordarray[$cleansqlcolumn];

						// if the value is null then make entry in log and the post or category set is cut short even if there are more levels
						if($catterm == NULL || empty($catterm) || !isset($catterm))
						{
							eci_log('Create Categories', 
							'Category creation found blank value for record ID:'. $record->eciid.'', 
							'While creating categories the plugin encountered a record with a blank value, this effects any levels expected to come after
							the blank category. This happened for record with ECI ID: '.$record->eciid.'', 
							$filename, $set, 'High', __LINE__, __FILE__, __FUNCTION__);
							break;
						}
						else 
						{		

								$categorydesc = '';
				
	
							// put value through encoding based on users category encoding settings
							$cat_encoded = eci_categoryencoding( $catterm, $csv,'category' );
							
							// check if the $cat_encoded value from data is to be replaced with a paired value
							if(isset($csv['categoriespaired']['data']) && isset($csv['categoriespaired']['blog']))
							{
								if(isset($csv['categoriespaired']['data'][$cat_encoded]))
								{
									// using the key id in data array we can get the meta id in blog array
									$catid = $csv['categoriespaired']['blog'][$csv['categoriespaired']['data'][$cat_encoded]];
									$pairedcatidfound = true;
								}							
							}

							// determine parent ID if not first category
							if($set_cats_used != 0)
							{
								$par = $set_cats_used - 1;
								$parent = $finalcat_array[$par];
							}
							else
							{						
								$parent = 0;
							}								
						
							// if catidfound is not set we look for the category term already in blog
							// if this is a child, we need to look for existing category with same parent as this
							// if this category does not exist under the current parent, then create it, else use the existing ID
							if(!isset($pairedcatidfound))
							{
								if( $parent == 0)
								{ 	
									$catid = get_cat_ID( $cat_encoded );
								}
								elseif( $parent != 0)// is a child
								{									
									// the goal here is to set $catid with an existing category id
									// if one exists with the exact same name
									// and it has the exact same parent
								    
									// is there a term matching our child cat and has our intended parent id? if not then we need to create it
									$selectresult = $wpdb->get_row('SELECT 
									$wpdb->terms.term_id,$wpdb->terms.name,$wpdb->term_taxonomy.parent
									FROM $wpdb->terms JOIN $wpdb->term_taxonomy
									WHERE $wpdb->terms.name = "'.$cat_encoded.'"
									AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
									AND $wpdb->term_taxonomy.parent = "'.$parent.'" 
									LIMIT 1');
									
									if(!$selectresult)
									{
										// no results so we do not set $catid, triggering new category creation
									}
									else 
									{
										$catid = $selectresult->term_id;
									}
								}
							}			
							unset($pairedcatidfound);
							
							// add cat meta id to catarray or create a new one				
							if( $catid != 0 )// category already exists
							{	
								// add cat meta id to - $set_cats_used is array key for set
								$finalcat_array[$set_cats_used] = $catid;
								
					
									$appliedcat_array[] = $catid;
								
								
								++$event_cats_used_count;
								++$event_cats_created_count;
								++$set_cats_used;
							}		
							elseif( $catid == 0 )// if zero returned then no matching category exists, create it now
							{		

								
								$arg = array('description' => $categorydesc, 'parent' => $parent);
								$insertcatid = wp_insert_term($cat_encoded, "category", $arg);

								if($insertcatid)
								{
									$finalcat_array[$set_cats_used] = $insertcatid;
	
									$appliedcat_array[] = $insertcatid;
									
								}
								else
								{
									eci_log('Category Creation', 'wp_insert_category failed', 
									'A Wordpress core function (wp_insert_category) failed to create a category during post creation', 
									$filename, $set, 'Critical', __LINE__, __FILE__, __FUNCTION__);
								}
								
								//$catarray[$catsadded] = wp_create_category( $cat );
								++$event_cats_used_count;
								++$event_cats_created_count;
								++$set_cats_used;						
							}															
						}// end if catterm null		
					}		
					else 
					{
						// this level is not set within the current category set
						//exit this loop to prevent further levels being checked
						break;
					}	
					
					// Increase Column Level - this is used to accessed the cat# array key
					++$collev;	
					
					// Unset Last Category Term
					unset($catterm);
				}
			}// end for loop - category sets
			
			// unset $lastcatid so that first category of new set is a parent, does not use a parent
			unset($lastcatid);
			
			// unset $set_cats_used so that the new set starts at zero
			unset($set_cats_used);
			
		}// end if cat split set else run normal cat
	}// end if single cat else run normal cat				
	
	// return results
	//return $finalcat_array;
	return $appliedcat_array;// we now only return an array of categories user wants to apply
}	
						
# Replaces URL In Post Content With Cloak - Real URL Stored In Custom Field
function eci_applyurlcloaking( $csv,$recordarray,$my_post )
{
	return $my_post;
}
// add eci custom fields
function eci_postbuildmeta( $id, $filename, $recordid, $tablename )
{
	// original, updated, seperated
	update_post_meta($id, 'eci_poststate', 'original', true);// used to indicate post is related to ECI - also allows special treatment
	update_post_meta($id, 'eci_filename', $filename, true);// can use this in systematic post update to retrieve the right profile
	update_post_meta($id, 'eci_tablename', $tablename, true);// with this we don't need the csv file name to work with the table
	update_post_meta($id, 'eci_recordid', $recordid, true);// for linking back to the correct database record, used in updating
	update_post_meta($id, 'eci_lastupdate', date("Y-m-d H:i:s"), true);// used to compare to database table timestamp for auto updating	
}

// create excerpt
function eci_createexcerpt($str, $length)
{
	$eci_createexcerpt = eci_truncatestring( eci_striphtmltags($str), $length);
	if (strlen($str) > strlen($eci_createexcerpt)) {$eci_createexcerpt .= "...";}
	return $eci_createexcerpt;
}

# strip html tags
function eci_striphtmltags( $str )
{
	$untagged = "";
	$skippingtag = false;
	for ($i = 0; $i < strlen($str); $i++) 
	{
		if (!$skippingtag) 
		{
			if ($str[$i] == "<") 
			{
				$skippingtag = true;
			} 
			else
			{
				if ($str[$i]==" " || $str[$i]=="\n" || $str[$i]=="\r" || $str[$i]=="\t") 
				{
					$untagged .= " ";
				}
				else
				{
					$untagged .= $str[$i];
				}
			}
		}
		else
		{
			if ($str[$i] == ">") 
			{
				$untagged .= " ";
				$skippingtag = false;
			}		
		}
	}	
	
	$untagged = preg_replace("/[\n\r\t\s ]+/i", " ", $untagged); // remove multiple spaces, returns, tabs, etc.
	
	if (substr($untagged,-1) == ' ') { $untagged = substr($untagged,0,strlen($untagged)-1); } // remove space from end of string
	if (substr($untagged,0,1) == ' ') { $untagged = substr($untagged,1,strlen($untagged)-1); } // remove space from start of string
	if (substr($untagged,0,12) == 'DESCRIPTION ') { $untagged = substr($untagged,12,strlen($untagged)-1); } // remove 'DESCRIPTION ' from start of string
	
	return $untagged;
}

# truncate string to a specific length
function eci_truncatestring( $string, $length )
{
	if (strlen($string) > $length) 
	{
		$split = preg_split("/\n/", wordwrap($string, $length));
		return ($split[0]);
	}
	return ( $string );
}

# connect to online blog and returns connection object
function eci_dbconnection($host,$username,$password,$database)
{
	return mysql_connect($host,$username,$password,$database);
}

// Character Encoding Input
$eci_o42chars['in'] = array(
    chr(196), chr(228), chr(214), chr(246), chr(220), chr(252), chr(223)
);
$eci_o42chars['ecto'] = array(
    'Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß'
);
$eci_o42chars['html'] = array(
    '&Auml;', '&auml;', '&ouml;', '&ouml;', '&Uuml;', '&uuml;', '&szlig;'
);
$eci_o42chars['utf8'] = array(
    utf8_encode('Ä'), utf8_encode('ä'), utf8_encode('Ö'), utf8_encode('ö'),
    utf8_encode('Ü'), utf8_encode('ü'), utf8_encode('ß')
);
$eci_o42chars['perma'] = array(
    'Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss'
);

// Character Encoding Ouput
$eci_o42chars['post'] = array(
    'Ä', 'ä', 'Ö', 'ö', 'Uuml;', 'ü', 'ß'
);
$eci_o42chars['feed'] = array(
    '&#196;', '&#228;', '&#214;', '&#246;', '&#220;', '&#252;', '&#223;'
);

# Applys Encoding To Entire $my_post Object Based On Settings
function eci_postencoding( $csv,$my_post,$set )
{	
	// title encoding
	if( isset( $set['titleencoding'] ) && $set['titleencoding'] != 'None' )
	{
		if( $set['titleencoding'] == 'UTF8Standard' )
		{
			$my_post['title'] = utf8_encode( $my_post['title'] );
		}
		elseif( $set['titleencoding'] == 'UTF8Full' )
		{
			$my_post['title'] = eci_encodingclean_content( $my_post['title'] );
		}
	}
	
	// post content encoding
	if( isset( $set['contentencoding'] ) && $set['contentencoding'] != 'None' )
	{
		if( $set['contentencoding'] == 'UTF8Standard' )
		{
			$my_post['title'] = utf8_encode( $my_post['title'] );
		}
		elseif( $set['contentencoding'] == 'UTF8Full' )
		{
			$my_post['title'] = eci_encodingclean_content( $my_post['title'] );
		}
	}
	
	// permalink encoding
	if( isset( $set['permalinkencoding'] ) && $set['permalinkencoding'] != 'None' )
	{
		if( $set['permalinkencoding'] == 'UTF8Standard' )
		{
			$my_post['title'] = utf8_encode( $my_post['title'] );
		}
		elseif( $set['permalinkencoding'] == 'UTF8Full' )
		{
			$my_post['title'] = eci_encodingclean_permalinks( $my_post['title'] );
		}
	}
	
	return $my_post;
}

# Converts Special Characters Using Correct Encoding Values For Permalinks
function eci_encodingclean_permalinks($title) 
{
    global $eci_o42chars;
    
    if ( seems_utf8($title) ) 
	{
		$invalid_latin_chars = array(chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(160) => 'S', chr(197).chr(189) => 'Z', chr(197).chr(161) => 's', chr(197).chr(190) => 'z', chr(226).chr(130).chr(172) => 'E');
		$title = utf8_decode(strtr($title, $invalid_latin_chars));
    }
    
    $title = str_replace($eci_o42chars['ecto'], $eci_o42chars['perma'], $title);
    $title = str_replace($eci_o42chars['in'], $eci_o42chars['perma'], $title);
    $title = str_replace($eci_o42chars['html'], $eci_o42chars['perma'], $title);
    $title = sanitize_title_with_dashes($title);
    return $title;
}

# Converts Special Characters Using Correct Encoding Values For Content
function eci_encodingclean_content( $content) 
{
    global $eci_o42chars;


#############################  TO BE TESTED   



    if ( strtoupper( get_option('blog_charset' )) == 'UTF-8') 
	{
 		$content = str_replace($eci_o42chars['utf8'], $eci_o42chars['feed'], $content);
    }
	
    $content = str_replace($eci_o42chars['ecto'], $eci_o42chars['feed'], $content);
    $content = str_replace($eci_o42chars['in'], $eci_o42chars['feed'], $content);

    return $content;
}

# Changes $my_post Object To Reflect The Adoption Of Existing Post Not A New Post Being Updated
function eci_adopt( $adoptpostid,$my_post )
{
	if( $adoptpostid )
	{						
		// delete the post we created
		wp_delete_post( $my_post['ID'], $force_delete = true );
	
		// unset my_post values that shouldnt be used to change existing post		
		unset($my_post['post_category']);
		unset($my_post['post_type']);
		unset($my_post['post_status']);
		unset($my_post['comment_status']);
		unset($my_post['post_author']);
		unset($my_post['to_ping']);
		unset($my_post['post_date']);
		unset($my_post['post_date_gmt']);
		unset($my_post['post_name']);					
		unset($my_post['comment_status']);	
		
		// replace the ID in $my_post object with adopted post id
		$my_post['ID'] = $adoptpostid;
	}	
	
	return $my_post;
}
			
# if duplicate posts found - attempts to adopt if user has activated adoption
function eci_postadoption( $duplicatearray,$recordarray,$csv )
{
	if( $csv['postadopt'] )
	{
		global $wpdb;
							
		// set switch to indicate if adoption has been applied or not
		$adopted = false;
		
		// get totaly number of keys in use
		$required = 0;
		if( isset( $csv['updating']['key1'] ) && $csv['updating']['key1'] != 'NA' ){ ++$required; }
		if( isset( $csv['updating']['key2'] ) && $csv['updating']['key2'] != 'NA' ){ ++$required; }
		if( isset( $csv['updating']['key3'] ) && $csv['updating']['key3'] != 'NA' ){ ++$required; }
		
		// loop through matched post id until we find a suitable post to adopt
		$postschecked = 0;
		
		// do key checks while $adopted is false
		$loop = 0;
		while( $loop != $required )
		{
			// get post id from duplicate array
			$id = $duplicatearray[$loop];
			
			// preset number of unique key parts found
			$found = 0;
		
			// loop through each key and attempt to get match for each one
			$loop = 1;
			while( $loop < $required )
			{
				// find unique key part within existing post
				if( isset( $csv['updating']['key'.$loop.''] ) && $csv['updating']['key'.$loop.''] != 'NA' )
				{
					// get data value for key
					$data = $recordarray[ $csv['updating']['key'.$loop.''] ];
					
					// check custom fields for a match
					$wpdb->query("SELECT meta_id FROM " .$wpdb->prefix . "postmeta WHERE meta_value = '$data' AND post_id = '$id'");
					
					// increase $found count if rows returned
					if( $wpdb->num_rows )
					{
						++$found;	
					}
					else
					{
						// try a content match
						$record = $wpdb->query("SELECT post_content FROM " .$wpdb->prefix . "posts WHERE ID = '$id'");
						
						// increase $found count if rows returned
						if( $record )
						{
							$locate = strpos(  $record->post_content,$data );
							
							// find key data value within content
							if( is_numeric( $locate ) && $locate != false )
							{
								++$found; 
							}
						}
					}
				}
				++$loop;
			}
			
			// did we get 3 key matches - if so adopt the current post being checked and exit 
			if( $found == $required )
			{
				// set adopted to true
				$adopted = true;
				
				// return the id of current post
				return $id;
			}		
				
			// increase count on posts checked
			++$postschecked;
			
		}// end of while
		
		return false;
	}
}
			
# Checks For Existing Posts That May Be A Duplicate And Avoids Duplicate Titles
function eci_duplicateposts( $csv,$my_post,$filename,$set )
{
	global $wpdb;
	
	// create array for storing duplicate post id
	$duplicatearray = array();
	
	// start variable for outputting to text file
	$text = 0;
	
	// start duplicate counter ( number of different posts )
	$dups = 0;
	
	// start duplicate parts counter ( number of check methods returning duplicate )
	$parts = 0;
	
	// variable for post name will print message to text file about outcome ( true or false)
	$postname = false;
	
	// variable used for numbering text file rows
	$rows = 0;
		
	$title = $my_post['post_title'];
	
	$postname = sanitize_title( $title );

	// if post name set - do a check to ensure that it is unique
	if( isset( $my_post['post_name'] ) )
	{	
		$name = $my_post['post_name'];
		
		// execute select query to select all records with current posts name
		$r = $wpdb->get_results("SELECT ID,post_title FROM " .$wpdb->prefix . "posts WHERE post_name = '$name'", OBJECT);
		
		if( $wpdb->num_rows > 0 )
		{		
		
			// count number of rows returned to equal duplicate matches
			$dups = $dups + $wpdb->num_rows;

			++$parts;
			
			// set $postname to true - puts message in txt file
			$postname = true;
	
			#############################       NOTIFICAITON FOR THE CSV FILE ROW CONFIRMING IT HAS POSSIBLE DUPLICATES     ######################################
			
			// add matching records to text file for troubleshooting
			foreach( $r as $p )
			{
				// add each id to array - later we use it for post adoption
				$duplicatearray[] = $p->ID;
				
				// add entry to text file with post id
				$text .= $rows . ' Post ID: '. $p->ID . ' Type: Post Name (custom)';
			}			
		}
	}
	else// run the same check by using posts title
	{		
		$r = $wpdb->get_results("SELECT ID,post_title FROM " .$wpdb->prefix . "posts WHERE post_name = '$postname'", OBJECT);

		if( $wpdb->num_rows > 0 )
		{
			$dups = $dups + $wpdb->num_rows;
			
			++$parts;
			
			// set $postname to true - puts message in txt file
			$postname = true;
	
			#############################       NOTIFICAITON FOR THE CSV FILE ROW CONFIRMING IT HAS POSSIBLE DUPLICATES     ######################################
			
			// add matching records to text file for troubleshooting
			foreach( $r as $p )
			{
				$duplicatearray[] = $p->ID;
				$text .= $rows . ' Post ID: ' . $p->ID . ' Type: Post Name (default)';
			}			
		}
	}
	
	// check for duplicate title (not permalink/post name)
	$records = $wpdb->get_results("SELECT ID,post_title FROM " .$wpdb->prefix . "posts WHERE post_name = '$postname'", OBJECT);

	if( $wpdb->num_rows > 0 )
	{
		$dups = $dups + $wpdb->num_rows;
		
		++$parts;
		
		// set $postname to true - puts message in txt file
		$postname = true;

		#############################       NOTIFICAITON FOR THE CSV FILE ROW CONFIRMING IT HAS POSSIBLE DUPLICATES     ######################################
		
		// add matching records to text file for troubleshooting
		foreach( $r as $p )
		{
			$duplicatearray[] = $p->ID;
			$text .= $rows . ' Post ID: ' . $p->ID . ' Type: Title';
		}			
	}	
	
	// if we have a possible duplicate write it to text file
	if( $dups > 0 )
	{
		// add the checks statistics
		$text .= date( "Y-m-d H:i:s", time() ).' - Existing Posts Matched: '. $dups .'<br />';
		$text .= date( "Y-m-d H:i:s", time() ).'Total Seperate Parts Matches: '. $parts .'<br />';
		
		eci_log( __('Create Post Duplicate Check'),
		'post created with id '.$my_post['ID'].'',
		'post created with id '.$my_post['ID'].' with title '.$title.' is considered a duplicate',
		$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	
		
		return true;
	}
	else
	{
		return false;
	}
}

# Decides Final Post Status
function eci_poststatus( $csv,$my_post )
{
	$timenow = strtotime( date("Y-m-d h:i:s") );
	$timeset = strtotime( $my_post['post_date'] );
	
	if( $timeset > $timenow )// if posts time is greater than current
	{
		$my_post['post_status'] = 'future';
	}
	elseif( $timeset < $timenow )// if posts time is less than current
	{
		$my_post['post_status'] = $csv['poststatus'];
	}		
	elseif( $timeset == $timenow )// if matching times
	{
		$my_post['post_status'] = $csv['poststatus'];		
	}	
	
	return $my_post;
}

# Returns Array Holding Unique Key Columns Data Values For Giving Record
function eci_uniquekey( $csv,$recordarray )
{
	$uniquearray = array();
	
	if( isset( $csv['updating']['key1'] ) ){ $uniquearray[0] = $csv['updating']['key1']; };
	if( isset( $csv['updating']['key2'] ) ){ $uniquearray[1] = $csv['updating']['key2']; };
	if( isset( $csv['updating']['key3'] ) ){ $uniquearray[2] = $csv['updating']['key3']; };
	
	return $uniquearray;
}
			

# If "allow_url_fopen" not allows on server by ini, we use this curl function instead
function eci_curlthefilecontents( $url ) 
{
    $c = curl_init();
	
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	
    curl_setopt($c, CURLOPT_URL, $url);
	
    $contents = curl_exec($c);
	
    curl_close($c);

    if ($contents) 
	{
        return $contents;
    }
    
    return false;
}

# Applys Encoding To Categories If Activated
function eci_categoryencoding( $category, $set )
{
	// category encoding
	if( isset( $set['categoryencoding'] ) && $set['categoryencoding'] != false )
	{
		if( $set['categoryencoding'] == 'UTF8Standard' )
		{
			$data = htmlentities2( $data );
			$my_post['title'] = utf8_encode( $my_post['title'] );
		}
		elseif( $set['categoryencoding'] == 'UTF8Full' )
		{
			$data = htmlentities2( $data );
			$my_post['title'] = eci_encodingclean_content( $my_post['title'] );
		}
	}
	return $category;
}
	
# returns full file name with blog prefix - requires current filename
function eci_wptablename( $filename )
{
	global $wpdb;
	$csvfileChunks = explode(".", $filename);
	$tablename = $csvfileChunks[0];
	$tablename = str_replace ( '-' , '' ,  $tablename );// remove hyphens
	$tablename = str_replace ( '_' , '' ,  $tablename );// remove underscores
	$tablename = str_replace ( ' ' , '' ,  $tablename );// remove underscores
	$tablename = strtolower( $tablename );
	return $wpdb->prefix . $tablename;
}

// creaqtes a temporary post - eventually is updated during post creation process

function eci_createbasepost($postdate,$postdategmt,$designarray,$status,$recordarray,$csv)
{
	$my_post = array();// start post array
	$columncounter = 0;// keep track of the column id
	// create an empty post starting with users wysiwyg creation - this is so we can use the POST ID from here on
	$my_post['post_date'] = $postdate;
	$my_post['post_date_gmt'] = $postdategmt;
	$my_post['post_title'] = 'ECI Base Post';
	$my_post['post_content'] = __('Easy CSV Importer createds a base post then updates it to complete the Post Creation procedure. 
	Please refresh the page to ensure that you never opened the post while ECI was creating it, if no changes happen after refresh
	simply delete this post. If you find many of these in your blog please report it to WebTechGlobal.');
	$my_post['post_status'] = $status;
	
	// decide on post type based on post filter settings
 	if(isset($csv['posttypestatus']) && $csv['posttypestatus'] == 'Active' 
 	&& isset($csv['posttypefiltercolumn']) && $csv['posttypefiltercolumn'] != 'NA'
 	&& isset($csv['posttypefiltermethod']) && $csv['posttypefiltermethod'] != 'NA')
 	{
  		$sqlcolumn = eci_cleansqlcolumnname($csv['posttypefiltercolumn']);
		eval('$data = $record->$sqlcolumn;');
					
 		if($csv['posttypefiltermethod'] == 'incolumn')
 		{	
 			$my_post['post_type'] = $data;
 		}
 		elseif($csv['posttypefiltermethod'] == 'pair')
 		{
			$my_post['post_type'] = $csv['posttypefilter'][$data];
 		}
 		else
 		{
 			$my_post['post_type'] = $csv['posttype'];
 		}
 	}
 	else
 	{
 		$my_post['post_type'] = $csv['posttype'];
 	}	
 	
 	// double check that post_type has been set
 	if(!isset($my_post['post_type']))
 	{
 		$my_post['post_type'] = $csv['posttype'];
 	}
	
	$my_post['ID'] = wp_insert_post( $my_post );
	
	if( $my_post['ID'] )
	{
		return $my_post;
	}
	else
	{
		return false;
	}
}

/**
 * Stores and retrieves status information such as script memory usage and times
 * @return a value if $action is 1 or false if no value exists, if $action is 0 true or false is returned from update_option result
 * @param string $stat (array key and name of the stat)
 * @param all $value (false if $action is 1, if action is 0 then $value must be what is being stored)
 * @param integer $action (0 = store or 1 = retrieve)
 */
function eci_status($statname,$value,$action)
{
	if($action == 1)// get
	{		
		$sta = get_option('eci_sta');
		if( $sta ){ return $sta[$statname]; }
	}
	elseif($action == 0)// store
	{
		$sta = get_option('eci_sta');	
		$sta[$statname]	= $value;
		return update_option('eci_sta',$sta);
	}
}

# Creates Custom Fields Meta For All In One SEO If User Has Activated
function eci_allinoneseo_meta( $csv,$recordarray,$designarray,$id )
{
	if( isset( $designarray['seotitle'] ) && !empty( $designarray['seotitle'] ) )
	{
		update_post_meta( $id, '_aioseop_title',$designarray['seotitle'], true );
	}
	
	if( isset( $designarray['seodescription'] ) && !empty( $designarray['seodescription'] ) )
	{
		update_post_meta( $id, '_aioseop_description',$designarray['seodescription'], true );
	}					
}

function eci_wordpressseoyoast_meta( $csv,$recordarray,$designarray,$id )
{
    if( isset( $designarray['wpseofockeyword'] ) && !empty( $designarray['wpseofockeyword'] ) )
    {
        update_post_meta( $id, '_yoast_wpseo_focuskw',$designarray['wpseofockeyword'], true );
    }
    
    if( isset( $designarray['wpseotitle'] ) && !empty( $designarray['wpseotitle'] ) )
    {
        update_post_meta( $id, '_yoast_wpseo_title',$designarray['wpseotitle'], true );
    }

    // Added Meta Description for Yoast Wordpress-SEO plugin
    if( isset( $designarray['wpseodescription'] ) && !empty( $designarray['wpseodescription'] ) )
    {
        update_post_meta( $id, '_yoast_wpseo_metadesc',$designarray['wpseodescription'], true );
    }

    // Added Meta Keywords for Yoast Wordpress-SEO plugin
    if( isset( $designarray['wpseokeywords'] ) && !empty( $designarray['wpseokeywords'] ) )
    {
        update_post_meta( $id, '_yoast_wpseo_metakeywords',$designarray['wpseokeywords'], true );
    }	
}
				
/**
 * Calls individual SEO functions for each intigrated plugin or theme providing seo ability
 * @param array $csv (project configuration)
 * @param array $recordarray (single project record)
 * @param array $designarray (token designs)
 * @param integer $id (post id)
 */
function eci_seo($csv,$recordarray,$designarray,$id)
{
	eci_allinoneseo_meta($csv,$recordarray,$designarray,$id);
	eci_wordpressseoyoast_meta($csv,$recordarray,$designarray,$id);
}

/**
 * Adds custom post type meta values for each post
 * @param array $recordarray (single record for creating post)
 * @param array $csv (project configuration)
 * @param filename $filename
 * @param array $set (plugin settings)
 * @param string $posttype (custom post type possible)
 * @param integer $postid (id of post being created)
 */
function eci_populatetaxonomy($recordarray,$csv,$filename,$set,$posttype,$postid)
{

}
?>