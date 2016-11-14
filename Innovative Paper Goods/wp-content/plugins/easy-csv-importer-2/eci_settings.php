<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>      

<?php
// uninstall plugin options data
if( isset( $_POST['eci_uninstallcomplete'] ) )
{
	eci_deleteoptionarrays();	
	eci_mes( __('Plugin Data Arrays Un-Installed'),__('The plugin deleted its arrays of data from the Wordpress options table. To finish the removal of the plugin, please delete it on the Wordpress plugin screen.') );
}

// reinstall plugin options data
if( isset( $_POST['eci_reinstall'] ) )
{
	eci_deleteoptionarrays();	
	eci_install_options();
	eci_mes( __('Plugin Data Arrays Re-Installed'),__('Your next step should be configuring the plugins settings and the interface to your requirements. Then go to the Project Start page to create a project as any previous project data will now be deleted.') );
}

// global post settings
if( isset( $_POST['eci_postsettings_submit'] ) )
{
	$set['tagschars'] = $_POST['tagschars'];
	$set['tagsnumeric'] = $_POST['tagsnumeric'];
	$set['tagsexclude'] = $_POST['tagsexclude'];
	$set['excerptlimit'] = $_POST['excerptlimit'];
	
	if( update_option('eci_set',$set) )
	{
		eci_mes( __('Post Settings Saved'),__('Posts or pages you create from here on will be effected by the new settings.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('You made no changes to your post/page settings.') );
	}
}

// save encoding settings
if( isset( $_POST['eci_encoding_submit'] ) )
{
	$set['titleencoding'] = $_POST['eci_encoding_title'];
	$set['contentencoding'] = $_POST['eci_encoding_content'];
	$set['categoryencoding'] = $_POST['eci_encoding_category'];
	$set['permalinkencoding'] = $_POST['eci_encoding_permalink'];
	if( update_option('eci_set',$set) )
	{
		eci_mes( __('Encoding Saved'),__('Your encoding settings have been saved, it is recommended that you test them before building your blog.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('No settings were changed so Wordpress did not attempt to make a save.') );
	}
}
	
// developer settings
if( isset( $_POST['eci_developer_submit'] ) )
{
	$set['displayarrays'] = $_POST['displayarrays'];
	$set['recordlastid'] = $_POST['recordlastid'];
	$set['selectescapedata'] = $_POST['selectescapedata'];
	$set['insertescapedata'] = $_POST['insertescapedata'];
	
	if( update_option('eci_set',$set) )
	{
		eci_mes( __('Developer Settings Saved'),__('You have made changes to developer settings.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('No settings were changed on the developer form compared to settings already saved.') );
	}
}	
	
// advanced settings
if( isset( $_POST['eci_advanced_submit'] ) )
{	
	$set['postupdating'] = $_POST['postupdating'];	
	$set['querylimit'] = $_POST['querylimit'];
	$set['acceptabledrop'] = $_POST['acceptabledrop'];
	$set['createtest'] = $_POST['createtest'];
	$set['log'] = $_POST['log'];
	$set['allowduplicaterecords'] = $_POST['allowduplicaterecords'];
	$set['allowduplicateposts'] = $_POST['allowduplicateposts'];
	$set['editpostsync'] = $_POST['editpostsync'];
					  
	if( update_option('eci_set',$set) )
	{
		eci_mes( __('Advanced Settings Saved'),__('You have made changes to advanced settings.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('No settings were changed.') );
	}
}			

// save interface settings
if( isset( $_POST['eci_interface_submit'] ) )
{
	if( isset( $_POST['aboutpanels'] ) ){$set['aboutpanels'] = true;}else{$set['aboutpanels'] = false;}
	if( isset( $_POST['updating'] ) ){$set['updating'] = true;}else{$set['updating'] = false;}
	if( isset( $_POST['scheduling'] ) ){$set['scheduling'] = true;}else{$set['scheduling'] = false;}
	if( isset( $_POST['allinoneseo'] ) ){$set['allinoneseo'] = true;}else{$set['allinoneseo'] = false;}
	if( isset( $_POST['posttypes'] ) ){$set['posttypes'] = true;}else{$set['posttypes'] = false;}
	
	if( isset( $csv['conditions']['switches']['dropposts'] ) && $csv['conditions']['switches']['dropposts'] == true ){ $dpc = 'checked="checked"'; }else{ $dpc = ''; }

	if( update_option('eci_set',$set) )
	{
		eci_mes( __('Interface Settings Saved'),__('The changes you made to your interface settings will cause parts of the interface to be hidden or become visible.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('You made no changes to your interface settings.') );
	}
}

// save random date settings
if( isset( $_POST['eci_randomdatesettings_submit'] ) )
{
	$set = get_option( 'eci_set' );
	$set['rd_yearstart'] = $_POST['eci_yearstart'];
	$set['rd_monthstart'] = $_POST['eci_monthstart'];
	$set['rd_daystart'] = $_POST['eci_daystart'];
	$set['rd_yearend'] = $_POST['eci_yearend'];
	$set['rd_monthend'] = $_POST['eci_monthend'];
	$set['rd_dayend'] = $_POST['eci_dayend'];   
	if( update_option( 'eci_set', $set ) )
	{
		eci_mes(__('Random Date Settings Saved'),__('Your settings have been updated.'));
	}
	else
	{
		eci_err(__('Random Date Settings Failed'),__('Your submitted settings did not save.'));
	}	
}
				
// save incremented date settings				
if( isset( $_POST['eci_incrementaldate_submit'] ) && is_user_logged_in() )
{
	$set = get_option( 'eci_set' );
	$set['incrementyearstart'] = $_POST['eci_incrementyearstart'];
	$set['incrementmonthstart'] = $_POST['eci_incrementmonthstart'];
	$set['incrementdaystart'] = $_POST['eci_incrementdaystart'];
	$set['incrementstart'] = $_POST['eci_incrementstart'];
	$set['incrementend'] = $_POST['eci_incrementend'];  
	if( update_option( 'eci_set', $set ) )
	{
		eci_mes(__('Incremental Date Settings Saved'),__('Your settings have been updated.'));
	}
	else
	{
		eci_err(__('Incremental Date Settings Failed'),__('Your submitted settings did not save.'));
	}
}
				
// get data array after processing functions
$pro = get_option('eci_pro');
$set = get_option('eci_set');
$deb = get_option('eci_deb');
?>

<div class="wrap">

	<?php @eci_pagetitle('ECI Plugin Settings',$pro['current']); ?>

	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Interface Configuration</span></h3>
				<div class="inside">
					
					<?php eci_help_icon(ECIBLOG,'eci-interface-settings','');?>
				
                    <p><?php _e('These settings allow you to disable or enable features on the interface. If your sure there is something you will never need to use, it might make 
                    the plugin easier to understand by hiding it.');?></p>
                    <form method="post" name="eci_interface_form" action="">
						<?php 
                        if( isset( $set['aboutpanels'] ) && $set['aboutpanels'] == 1 ){ $ic1 = 'checked="checked"'; }else{ $ic1 = ''; }
                        if( isset( $set['updating'] ) && $set['updating'] == 1 ){ $ic2 = 'checked="checked"'; }else{ $ic2 = ''; }
                        if( isset( $set['scheduling'] ) && $set['scheduling'] == 1 ){ $ic3 = 'checked="checked"'; }else{ $ic3 = ''; }
                        if( isset( $set['allinoneseo'] ) && $set['allinoneseo'] == 1 ){ $ic4 = 'checked="checked"'; }else{ $ic4 = ''; }
                        if( isset( $set['posttypes'] ) && $set['posttypes'] == 1 ){ $ic5 = 'checked="checked"'; }else{ $ic5 = ''; }?>
                        <table class="widefat post fixed">
                            <tr>
                                <td width="125"><strong>Feature</strong></td><td><strong>Description</strong></td><td><strong>Disable/Enable</strong></td>
            				</tr>                  	
                            <tr>
                                <td>About Panels</td><td><?php _e('Various panels containing help text and video tutorials.');?></td><td><input type="checkbox" name="aboutpanels" value="1" <?php echo $ic1; ?> id="1" /></td>
                            </tr>
                            <tr>
                                <td>Updating</td><td><?php _e('You may hide features and options for data plus p');?>ost updating.</td><td><input type="checkbox" name="updating" value="1" <?php echo $ic2; ?> id="2" /></td>
                            </tr>
                            <tr>
                                <td>Scheduling</td><td><?php _e('If all importing and post creation is done manually you do not need scheduling.');?></td><td><input type="checkbox" name="scheduling" value="1" <?php echo $ic3; ?> id="3" /></td>
                            </tr>
                            <tr>
                                <td>All In One SEO</td><td><?php _e('Various features and options related to the All In One SEO plugin.');?></td><td><input type="checkbox" name="allinoneseo" value="1" <?php echo $ic4; ?> id="4" /></td>
                            </tr>
                            <tr>
                                <td>Post Types</td><td><?php _e('Ability to use multiple post types other than just post.');?></td><td><input type="checkbox" name="posttypes" value="1" <?php echo $ic5; ?> id="5" /></td>
                            </tr>  
                      </table>
                      <br />
                      <input class="button-primary" type="submit" name="eci_interface_submit" value="Save" /></td>
                  </form>
                </div>
            </div>

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Global Post Settings</span></h3>
				<div class="inside">
				
					<?php eci_help_icon(ECIBLOG,'eci-post-settings','');?>
				
					<p><?php _e('These settings override all project settings and should be used to get the results your entire blog needs.');?></p>
                                        
                    <form method="post" name="eci_postsettings_form" action="">            
                        <table class="widefat post fixed">
                            <tr><td width="100"><b>Setting</b></td><td><b>Description</b></td><td></td></tr>
                            <tr>
                                <td><?php _e('Tags String Length');?></td>
                                <td><?php _e('Restrict the total length of all tags for a single post by number of characters. Good for 
                                keeping in with the design of your theme.');?></td>
                                <td><input name="tagschars" type="text" value="<?php echo $set['tagschars'];?>" size="3" maxlength="3" /></td>
                            </tr>
                            <tr>
                                <td><?php _e('Tags Numeric');?></td>
                                <td><?php _e('You can exclude numeric tags,handy if your automatically generating them');?></td>
                                <td><select name="tagsnumeric" size="1">
                  					<option value="Exclude" <?php eci_selected( $set['tagsnumeric'],'Exclude' ); ?>>Exclude</option>
                 					<option value="Include" <?php eci_selected( $set['tagsnumeric'],'Include' ); ?>>Include</option>
                					</select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Tags Exclude');?></td>
                                <td><?php _e('Comma seperated list of key values to be excluded from being used as tags');?></td>
                                <td><textarea name="tagsexclude" cols="35" rows="10" wrap="hard"><?php echo $set['tagsexclude']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td><?php _e('Excerpt Length Limit');?></td>
                                <td><?php _e('Control the character length of your excerpt');?></td>
                                <td><input name="excerptlimit" type="text" value="<?php echo $set['excerptlimit'];?>" size="3" maxlength="3" /></td>
                            </tr>
                        </table><br />
                        <input class="button-primary" type="submit" name="eci_postsettings_submit" value="Save" /></td>
                    </form>                       
                    
                </div>
            </div>

            <div class="postbox closed">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3><?php _e('Random Publish Date Settings');?></h3>
                <div class="inside">

					<?php eci_help_icon(ECIBLOG,'eci-random-dates','');?>

                    <form method="post" name="eci_randomdatesettings_form" action="">            
                        <p><?php _e('If you want to use a random publish date on your posts set the date range here. If your month or
                        day only has one digit&nbsp;please enter a zero first i.e. 05,09. Your posts will not be displayed 
                        on your blog in the order of them being created using this method. If you make your start date in the future all your posts will be scheduled
                        for future publish. If your start date is in the past and end date is in the future then only some of your posts will be scheduled.');?></p>
                        Year Start:<input name="eci_yearstart" type="text" value="<?php echo $set['rd_yearstart']; ?>" size="4" maxlength="4" />
                        <br />    		
                        Month Start:<input name="eci_monthstart" type="text" value="<?php echo $set['rd_monthstart']; ?>" size="2" maxlength="2" />
                        <br />    		
                        Day Start:<input name="eci_daystart" type="text" value="<?php echo $set['rd_daystart']; ?>" size="2" maxlength="2" />
                        <br />    		
                        Year End:<input name="eci_yearend" type="text" value="<?php echo $set['rd_yearend']; ?>" size="4" maxlength="4" />
                        <br />    		
                        Month End:<input name="eci_monthend" type="text" value="<?php echo $set['rd_monthend']; ?>" size="2" maxlength="2" />
                        <br />    		
                        Day End:<input name="eci_dayend" type="text" value="<?php echo $set['rd_dayend']; ?>" size="2" maxlength="2" />
                        <br />   
                        <br />           
                        <input class="button-primary" type="submit" name="eci_randomdatesettings_submit" value="Save" />
                    </form>	
                </div>
            </div>
            
            <div class="postbox closed">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3><?php _e('Incremental Publish Date Settings');?></h3>
                <div class="inside">
                	
					<?php eci_help_icon(ECIBLOG,'eci-incremental-dates','');?>
                
                    <form method="post" name="eci_incrementaldate_form" action="">            

                        <h4>Start Date</h4>
                        Year Start:<input name="eci_incrementyearstart" type="text" value="<?php echo $set['incrementyearstart']; ?>" size="4" maxlength="4" />
                        <br />    		
                        Month Start:<input name="eci_incrementmonthstart" type="text" value="<?php echo $set['incrementmonthstart']; ?>" size="2" maxlength="2" />
                        <br />    		
                        Day Start:<input name="eci_incrementdaystart" type="text" value="<?php echo $set['incrementdaystart']; ?>" size="2" maxlength="2" />
                        <br /><br />
                        <h4>Increment Random Value</h4>
                        Increment Start:<input name="eci_incrementstart" type="text" value="<?php echo $set['incrementstart']; ?>" size="6" maxlength="6" /> least allowed seconds
                        <br />    		
                        Increment End:<input name="eci_incrementend" type="text" value="<?php echo $set['incrementend']; ?>" size="6" maxlength="6" /> longest allowed seconds
                        <br />   
                        <br />           
                        <input class="button-primary" type="submit" name="eci_incrementaldate_submit" value="Save Incremental Date Settings" />
                    </form>      
                    <br />
                   <br />
                    <h4>Seconds  Guide</h4>
                    <ul>
                      <li><strong>
                      1 Hour:</strong> 3600 </li>
                      <li><strong>3 Hours:</strong> 10800</li>
                      <li><strong>10 Hours:</strong> 36000</li>
                      <li><strong>24 Hours:</strong> 86400</li>
                    </ul>     
                    <br />
                    <h4><?php _e('Projected Dates (examples based on above settings)');?></h4>
                    <?php echo eci_projecteddates_incremental( $set );?>             
                </div>
            </div>

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Encoding</span></h3>
                <div class="inside">
                	
                    <?php eci_help_icon(ECIBLOG,'eci-encoding','');?>
                
                    <p><?php _e('You may apply encoding to various parts of your post.');?></p>
                     
                    <form method="post" name="eci_encoding_form" action="">            
                        <table class="widefat post fixed">
                			<tr>
                            	<td><?php _e('Title Encoding');?></td>
                                <td><?php _e('Post/page title encoding, applied once title created');?></td>
                                <td><?php eci_encodingmenu( $set,$set['titleencoding'],'title' );?></td>
                            </tr>
                			<tr>
                            	<td><?php _e('Content Encoding');?></td>
                                <td><?php _e('Encoding is applied to post content once content is prepared');?></td>
                                <td><?php eci_encodingmenu( $set,$set['contentencoding'],'content' );?></td>
                            </tr>
                			<tr>
                            	<td><?php _e('Category Encoding');?></td>
                                <td><?php _e('Applies encoding to blog categories');?></td>
                                <td><?php eci_encodingmenu( $set,$set['categoryencoding'],'category' );?></td>
                            </tr>
                			<tr>
                            	<td><?php _e('Permalink Encoding');?></td>
                                <td><?php _e('Applies encoding to a posts permalink before post is created');?></td>
                                <td><?php eci_encodingmenu( $set,$set['permalinkencoding'],'permalink' );?></td>
                            </tr>
                        </table>
                        <br />
                        <input class="button-primary" name="eci_encoding_submit" type="submit" value="Save" />
                    </form>              
                </div>
            </div>
            
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Advanced Settings');?></span></h3>
                <div class="inside">
                
                	<?php eci_help_icon(ECIBLOG,'eci-advanced-settings','');?>
                
                    <p><?php _e('Some of these default settings are set to values that ensure proper operation on most servers
                    and hosting or simply help to make the plugin easy to use for beginners.');?></p>

                    <form method="post" name="eci_advanced_form" action="">            
                        <table class="widefat post fixed">

                            <tr>
                                <td><?php _e('Global Post Updating')?></td>
                                <td><?php _e('Switch post updating on and off for all projects using this setting')?></td>
                                <td><select name="postupdating" size="1">
                  					<option value="Yes" <?php eci_selected( $set['postupdating'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['postupdating'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>
                            
                			<tr>
                            	<td><?php _e('Query Limit')?></td><td><?php _e('The query limit applys to some administrational
                                operations within the plugin.')?></td>
                                <td><input name="querylimit" type="text" value="<?php if( isset( $set['querylimit'] ) ){ echo $set['querylimit']; }?>" size="6" maxlength="6" /></td>
                            </tr>
                			<tr>
                            	<td><?php _e('Acceptable Drop')?></td><td><?php _e('When creating product, if the table already exists, the plugin
                                will simply delete it providing it contains no more than the Acceptable Drop amount.');?></td>
                                <td><input name="acceptabledrop" type="text" value="<?php if( isset( $set['acceptabledrop'] ) ){ echo $set['acceptabledrop']; }?>" size="6" maxlength="6" /></td>
                            </tr>
                			<tr>
                            	<td><?php _e('Post Creation Test')?></td><td><?php _e('The number of posts to create when you initiate a Post Creation Test.');?></td>
                                <td><input name="createtest" type="text" value="<?php if( isset( $set['createtest'] ) ){ echo $set['createtest']; }?>" size="6" maxlength="6" /></td>
                            </tr>


                            <tr>
                                <td><?php _e('Use Log File')?></td>
                                <td><?php _e('You can save your automated events and scheduling results to a log file')?></td>
                                <td><select name="log" size="1">
                  					<option value="Yes" <?php eci_selected( $set['log'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['log'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>   
                            
                            <tr>
                                <td><?php _e('Allow Duplicate Data Records')?></td>
                                <td><?php _e('Tell the plugin to import rows of data even if they match exactly to existing rows in the project table')?></td>
                                <td><select name="allowduplicaterecords" size="1">
                  					<option value="Yes" <?php eci_selected( $set['allowduplicaterecords'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['allowduplicaterecords'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td><?php _e('Allow Duplicate Posts')?></td>
                                <td><?php _e('If your blog requires posts with duplicate titles, you will need to set this to Yes')?></td>
                                <td><select name="allowduplicateposts" size="1">
                  					<option value="Yes" <?php eci_selected( $set['allowduplicateposts'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['allowduplicateposts'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>
                                                        
                            <tr>
                                <td><?php _e('Edit Post Sync (beta)')?></td>
                                <td><?php _e('Selecting yes will make the plugin update the project database table with manual changes you make 
                                to your posts or pages. This feature still requires much testing before being relied on.')?></td>
                                <td><select name="editpostsync" size="1">
                  					<option value="Yes" <?php eci_selected( $set['editpostsync'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['editpostsync'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>
                            
                        </table>
                        <br />
                        <input class="button-primary"  name="eci_advanced_submit" type="submit" value="Save" />
                    </form>              
                </div>
            </div>
                        
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Developer Settings');?></span></h3>
                <div class="inside">
                	
                	<?php eci_help_icon(ECIBLOG,'eci-developer-settings','');?>
                
                    <p><?php _e('These features are aimed at supporting developers, please contact me if you feel something
                                could be added her to help you do your job.');?></p>

                    <form method="post" name="eci_developer_form" action="">            
                        <table class="widefat post fixed">

                            <tr>
                                <td><?php _e('Display Plugins Data Arrays')?></td>
                                <td><?php _e('Displays various php array saved in the Wordpress options table by this plugin')?></td>
                                <td><select name="displayarrays" size="1">
                  					<option value="Yes" <?php eci_selected( $set['displayarrays'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['displayarrays'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>
                      
                             <tr>
                                <td><?php _e('Record Last ID')?></td>
                                <td><?php _e('Stores the project table record ID before attempting to create post')?></td>
                                <td><select name="recordlastid" size="1">
                  					<option value="Yes" <?php eci_selected( $set['recordlastid'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['recordlastid'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>                     
 
                              <tr>
                                <td><?php _e('SELECT Query Escape')?></td>
                                <td><?php _e('Apply security escape to values before they are added to the SELECT query')?></td>
                                <td><select name="selectescapedata" size="1">
                  					<option value="Yes" <?php eci_selected( $set['selectescapedata'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['selectescapedata'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>      
 
                              <tr>
                                <td><?php _e('INSERT Query Escape')?></td>
                                <td><?php _e('Apply security escape to values before they are added to the INSERT query')?></td>
                                <td><select name="insertescapedata" size="1">
                  					<option value="Yes" <?php eci_selected( $set['insertescapedata'],'Yes' ); ?>>Yes</option>
                 					<option value="No" <?php eci_selected( $set['insertescapedata'],'No' ); ?>>No</option>
                					</select>
                                </td>
                            </tr>                           	
	                                 
                        </table>
                        <br />
                        <input class="button-primary"  name="eci_developer_submit" type="submit" value="Save" />
                    </form>              
                </div>
            </div>
                        
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Install &amp; Uninstall');?></span></h3>
				<div class="inside">
					
                	<?php eci_help_icon(ECIBLOG,'eci-uninstall-reinstall','');?>
				
					<p><?php _e('WARNING - the actions these tools perform cannot be reversed. Please take your time and 
                    watch support videos or read Settings Page documentation to make sure you understand.')?></p>
                    
                    <form method="post" name="eci_installuninstall_form" action="">            
                        <table class="widefat post fixed">
                            <tr><td><b>Action</b></td><td><b>Description</b></td></tr>
                            <tr>
                                <td><input class="button-primary" type="submit" name="eci_reinstall" value="Re-Install" /></td>
                                <td><?php _e('Using this button will delete all data arrays associated with the plugin. That includes your project configuration and history. The plugin will then install the original
                                values installed when activating the plugin.');?>
                                </td>
                            </tr>
                            <br />
                            <tr>
                                <td><br />
								<input class="button-primary" type="submit" name="eci_uninstall" value="Complete Un-Install" /></td>
                                <td><br />
								<?php _e('Delete all data arrays from the Wordpress options table. Use this if you are removing the plugin and want no trace left
                                in the Wordpress options table.');?>'
                                </td>
                            </tr>
                        </table>
                    </form>              
                            
                </div>
            </div>
           
    	<div class="clear"></div>
    </div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>