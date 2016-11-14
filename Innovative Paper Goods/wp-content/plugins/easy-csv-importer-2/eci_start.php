<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;

// list of processing functions
if( isset( $_POST['eci_newproject_submit'] ) )
{	
	eci_mes('Change Separator Or Quote','
	<form method="post" name="eci_seperatorchange_form" action="">  
		<strong>Try A Different Separator</strong>
		<input class="button-primary" type="submit" name="eci_changesep" value="," /> 
		<input class="button-primary" type="submit" name="eci_changesep" value=";" />			
		<input class="button-primary" type="submit" name="eci_changesep" value=":" /> 
		<input class="button-primary" type="submit" name="eci_changesep" value="|" />
		<input class="button-primary" type="submit" name="eci_changesep" value="^" /> 
		<br />
		<strong>Try A Different Quote</strong>
		<input class="button-primary" type="submit" name="eci_changequote" value="Double Quote" /> 
		<input class="button-primary" type="submit" name="eci_changequote" value="Single Quote" />  									
	</form>');
}		                   		
                   		
// upload csv file
if( isset( $_POST['eci_csvupload_submit'] ) )
{
	eci_csvupload( $_FILES['file'],$set );
}   
	
// create folder and save path as csv file directory
if( isset( $_POST['eci_createdirectory_submit'] ) )
{
	eci_createdirectory( $_POST['eci_pathname'],$_POST['eci_pathdir'] );		
}

// save existing folder path as new csv file directory
if( isset( $_POST['eci_savedirectory_submit'] ) )
{
	eci_savedirectory( $_POST['eci_pathname'],$_POST['eci_pathdir'] );		
}
                   		
// delete or remove directory
if( isset( $_POST['eci_undodirectory_submit'] ) )
{
	eci_undodirectory( $_POST['eci_path'],$_POST['eci_action'] );		
}                    

if( isset( $_POST['eci_printcsv_submit'] ) )
{
	eci_printcsv( $_POST['eci_filepath'],$set,$_POST['eci_seperator'], stripslashes( $_POST['eci_quote'] ),$_POST['eci_displayrows'] );		
}                    
	
// change seperator
if( isset( $_POST['eci_changesep'] ) )
{	
	$csv = get_option('eci_'.$pro['current']);
	$csv['format']['seperator'] = $_POST['eci_changesep'];
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$pro['current'], $csv ) )
	{
		eci_mes(__('Seperator Saved'),__('Your new separator is now being applied to your csv file.') );
		eci_mes(__('Change Separator Or Quote'),'
		<form method="post" name="eci_seperatorchange_form" action="">  
			<strong>Try A Different Separator</strong>
			<input class="button-primary" type="submit" name="eci_changesep" value="," /> 
			<input class="button-primary" type="submit" name="eci_changesep" value=";" />			
			<input class="button-primary" type="submit" name="eci_changesep" value=":" /> 
			<input class="button-primary" type="submit" name="eci_changesep" value="|" />
			<br />
			<strong>Try A Different Quote</strong>
			<input class="button-primary" type="submit" name="eci_changequote" value="Double Quote" /> 
			<input class="button-primary" type="submit" name="eci_changequote" value="Single Quote" />  									
		</form>');
		eci_printcsv( $pro[$pro['current']]['filepath'],$set,$csv['format']['seperator'],stripslashes( $csv['format']['quote'] ),5 );				
	}
	else
	{
		eci_err(__('Separator Failed To Save'),__('Your separator has not been changed, please try again.') );
	}
}

// change quote
if( isset( $_POST['eci_changequote'] ) )
{	
	if( $_POST['eci_changequote'] == 'Single Quote' )
	{
		$quote = "'";
	}
	
	if( $_POST['eci_changequote'] == 'Double Quote' )
	{
		$quote = '"';		
	}	
	
	$csv = get_option('eci_'.$pro['current']);
	$csv['format']['quote'] = stripslashes( $_POST['eci_changequote'] );
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$pro['current'], $csv ) )
	{
		eci_mes(__('Quote Saved'),__('Your new quote is now being applied to your csv file.') );
		eci_mes('Change Separator Or Quote','
		<form method="post" name="eci_seperatorchange_form" action="">  
			<strong>Try A Different Separator</strong>
			<input class="button-primary" type="submit" name="eci_changesep" value="," /> 
			<input class="button-primary" type="submit" name="eci_changesep" value=";" />			
			<input class="button-primary" type="submit" name="eci_changesep" value=":" /> 
			<input class="button-primary" type="submit" name="eci_changesep" value="|" />
			<br />
			<strong>Try A Different Quote</strong>
			<input class="button-primary" type="submit" name="eci_changequote" value="Double Quote" /> 
			<input class="button-primary" type="submit" name="eci_changequote" value="Single Quote" />  									
		</form>');
		eci_printcsv($pro[$pro['current']]['filepath'],$set,$csv['format']['seperator'],stripslashes($quote),5);
	}
	else
	{
		eci_err( __('Quote Failed To Save'),__('Your new quote could not be saved,please try again.') );
	}
}

// get data array after processing functions
$pro = get_option('eci_pro');
?>

<div class="wrap">

	<?php @eci_pagetitle('ECI Start',$pro['current']); ?>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>1. <?php _e('Manage Data Sources');?></h3>
                <div class="inside">
                	
					<?php eci_help_icon(ECIBLOG,'eci-manage-data-sources','');?>
					
					<p><?php _e('This panel allows you to enter paths to as many folders as you want. Each folder can contain CSV files and all CSV files
					will then appear on the interface for creating projects with. Not everyone will need these functions but they can be handy if you
					already use CSV files with other software and do not want to move the files.');?></p><br />

                    <h4><?php _e('Upload CSV File')?> <?php echo ini_get( "upload_max_filesize").'B Limit';?></h4>
                   	<form method="post" enctype="multipart/form-data" name="uploadform" class="form-table">
                   		<input type="file" name="file" size="70" /><br /><br />
						<?php eci_pathsmenu(); ?>
                   		<input class="button-primary" type="submit" value="Upload CSV File" name="eci_csvupload_submit" />
                    </form>                   
                    <br /><br /><br /><br />
                    <h4><?php _e('Add Or Create New CSV File Directory');?></h4><br />
					<form method="post" name="eci_directory_form" action="">  
                        <input name="eci_pathdir" type="text" value="<?php echo WP_CONTENT_DIR; ?>" size="100" maxlength="100" /> <br /><br />
                        <input name="eci_pathname" type="text" value="Name" size="12" maxlength="12" />      <br /><br />
                   		<input class="button-primary" type="submit" value="Save Existing Directory" name="eci_savedirectory_submit" />
                   		<input class="button-primary" type="submit" value="Create New Directory" name="eci_createdirectory_submit" />
                    </form>    
                    <br /><br /><br /><br />
                    <h4><?php _e('Remove Or Delete CSV File Directory');?></h4><br />
					<form method="post" name="eci_undodirectory_form" action="">  
						<?php eci_pathsmenu(); ?>
                        <br /><br />
						<select name="eci_action">
							<option value="remove"><?php _e('Remove Directory From Plugin');?> </option>
							<option value="delete"><?php _e('Delete Directory And Contents From Server');?></option>
                        </select><br /><br />                        
                   		<input class="button-primary" type="submit" value="Submit" name="eci_undodirectory_submit" />
                    </form>   
                    <br />                    
                </div>
            </div>		
    
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>2. <?php _e('View &amp; Test Files');?></h3>
                <div class="inside">
                	
					<?php eci_help_icon(ECIBLOG,'eci-view-test-files','');?>
                
                	<p><?php _e('Using this test tool will not make any changes to your blog. It will print rows of data from your CSV file to the 
					page within a table. If you experiance problems, please test your file in Microsoft Excel and if it does not open in Excel then 
					the file is not ready for using, please seek advice on how to create a proper CSV file.');?></p>
					<form method="post" name="eci_printcsv_form" action=""> 
                    
                        <h4>Select Datasource</h4>
                		<?php eci_csvfilelist_newproject( $set,'singlefile','normal' ); ?>

                        <br /><br />
					
					<table>
						<tr><td>
                        <h4>Display</h4>
                                <select name="eci_displayrows" size="1">
                                    <option value="20">20 Rows</option>
                                    <option value="100">100 Rows</option>			
                                </select>
                            <br class="clear" /><br />
                        </td><td width="180">
                        <h4>Select Test Separator</h4>
                                <select name="eci_seperator" size="1">
                                    <option value=",">Comma (,)</option>                      
                                    <option value=";">Semi-Colon (;)</option>
                                    <option value=":">Colon (:)</option>
                                    <option value="|">Tab (|)</option>
                                    <option value="^">ASCII 2C5 (^)</option>                                    
                                </select>
                            <br class="clear" /><br />
                        </td><td>    
                        <h4>Select Test Quote</h4>
                                <select name="eci_quote" size="1">
                                    <option value='"'>"</option>                               
                                    <option value="'">'</option>
                                </select>
                            <br class="clear" /><br />
                        </td></tr>    
						</table>
						
							
                        <div class="versions">
                            <p><input class="button-primary" type="submit" name="eci_printcsv_submit" value="Test" /></p>
                            <br class="clear" />
                        </div>	
                        
                     </form>  
	
                </div>
            </div>
              
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>New Post Creation Project</h3>
                <div class="inside">
 				<?php eci_help_icon('http://www.webtechglobal.co.uk/blog/help/','eci-tutorial-start-new-project','');?>						
           		<p>This type of project requires you to use pages 1 to 5 to fully configure your project settings.</p>
					<form method="post" name="eci_newproject_form" action=""> 
						<input type="hidden" name="eci_filesettype" value="singlefile" />
						<input type="hidden" name="eci_projecttype" value="postcreation" />
						<input type="hidden" name="eci_approach" value="advanced" />
                		<?php eci_csvfilelist_newproject( $set,'singlefile','normal' ); ?>
                        <br />
                        <div class="versions">
                            <p><input class="button-primary" type="submit" name="eci_newproject_submit" value="Create" />
                            <br class="clear" />
                        </div>	
                     </form>  
                </div>
            </div>
            
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>New User Import/Creation Project (standard)</h3>
                <div class="inside">
                	
                	<?php eci_help_icon(ECIBLOG,'eci-user-creation-project','');?>
					
					<p>You can use this tool to import user data exported from another blog or import personal account information
					from any source. The Configuration page allows you to add user meta data, perfect for creating account information
					and populating fields for other plugins that manage users.</p>
					
					<form method="post" name="eci_newproject_form" action=""> 
						<input type="hidden" name="eci_filesettype" value="singlefile" />
						<input type="hidden" name="eci_projecttype" value="usercreation" />
                		<?php eci_csvfilelist_newproject( $set,'singlefile','normal' ); ?>
                        <br />
                        <div class="versions">
                            <p><input class="button-primary" type="submit" name="eci_newproject_submit" value="Create" />
                            <br class="clear" />
                        </div>	
                     </form>  
                </div>
            </div>

			<?php if(eci_is_plugininstalled('wp-e-commerce')){?>
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>WP e-Commerce Plugin (beta only, please support development)</h3>
                <div class="inside">
                	
                	<?php //eci_help_icon(ECIBLOG,'eci-user-creation-project','');?>
					
					<strong>This feature is mainly in the planning stage, please register your interest by contacting the webmaster@webtechglobal.co.uk</strong>
					
					<p>This plugin already has CSV import ability but it requires the CSV file to have specific column titles. ECI can offer the ability
					to keep your CSV file titles as they are provided, update products by overwriting the CSV file with a newer copy and automatic
					populate of SEO meta data plus more.</p>
					
					<h4>Developer Information</h4>
					<p>
						<ul>
							<li>Custom Post Types: ?</li>
							<li>Custom Fields: ?</li>
							<li>Table Names: ?</li>
							<li>WP Options: ?</li>
						</ul>
					</p>
					
                </div>
            </div>			
			<?php }?>
			
			<?php if(eci_is_plugininstalled('wp-cart-for-digital-products')){?>
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>eStore (beta only, please support development)</h3>
                <div class="inside">
                	
                	<?php //eci_help_icon(ECIBLOG,'eci-user-creation-project','');?>
					
					<strong>This feature is mainly in the planning stage, please register your interest by contacting the webmaster@webtechglobal.co.uk</strong>
					
					<p>More information coming later.</p>
					
					<h4>Developer Information</h4>
					<p>
						<ul>
							<li>Custom Post Types: ?</li>
							<li>Custom Fields: ?</li>
							<li>Table Names: ?</li>
							<li>WP Option Keys: ?</li>
						</ul>
					</p>
					
                </div>
            </div>			
			<?php }?>

			<?php if(eci_is_plugininstalled('jigoshop')){?>
			<div id="dashboard_right_now" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>JigoShop Project (beta only, please support development)</h3>
                <div class="inside">
                	
                	<?php //eci_help_icon(ECIBLOG,'eci-user-creation-project','');?>
					
					<strong>This feature is mainly in the planning stage, please register your interest by contacting the webmaster@webtechglobal.co.uk</strong>
					
					<p>More information coming later.</p>
					
					<h4>Developer Information</h4>
					<p>
						<ul>
							<li>Custom Post Types: product, shop_order</li>
							<li>Custom Fields: ?</li>
							<li>Table Names: ?</li>
							<li>WP Option Keys: ?</li>
						</ul>
					</p>
					
                </div>
            </div>			
			<?php }?>
						        	
        	<h2>Project History</h2>

            <?php
            $totalprojects = 0;
            // list existing projecta each one with their own post box
            if( isset( $pro ) && $pro != '' )
            {            	
                foreach( $pro as $key=>$item )
                {
                	$csv = get_option( 'eci_'.$key );
 
                	// ensure not array info etc and ensure is not a child of a multifile project
                	// multifile will not be displayed until controls can be changed to work with the parent $pro
                    if( isset( $pro[$key]['multifilelev'] ) && $pro[$key]['multifilelev'] != 'child' && $key != 'arraydesc' && $key != 'current' && $key != 'records' 
					|| !isset( $pro[$key]['multifilelev'] ) && $key != 'arraydesc' && $key != 'current' && $key != 'records' )
                    {
                    	++$totalprojects;
                    
                        // close all post boxes apart from the current project
                        if( $pro['current'] == $key )
                        {
                            $postbox = '';
                            $label = ' (current project)';
                        }
                        else
                        {
                            $postbox = 'closed';
                            $label = '';
                        }
                        
                        // add line to indicate multi file project
                        $filesetmessage = '';
                        if( isset( $pro[ $key ]['filesettype'] ) && $pro[ $key ]['filesettype'] == 'multifile' )
                        {
                        	$filesetmessage = '(advanced multi-file project)';
                        }
                        
                        // establish project type message
                        $projecttypemessage = '';
                        if(isset($pro[$pro['current']]['protype']))
                        {
                        	if($pro[$pro['current']]['protype'] == 'postcreation')
                        	{
                        		$projecttypemessage = '(post creation project)';
                        	}
                        	elseif($pro[$pro['current']]['protype'] == 'usercreation') 
                        	{
                        		$projecttypemessage = '(user creation project)';
                        	}
                        }
                        ?>
                        
                        <div id="dashboard_right_now" class="postbox <?php echo $postbox; ?>" >
                            <div class="handlediv" title="Click to toggle"><br /></div>
                            
                            <h3 class='hndle'><span><?php echo $key . ' ' . $label . ' ' . $filesetmessage . ' ' . $projecttypemessage; ?></span></h3>
                            <div class="inside">
                                <div class="table table_content">
                                	<br />
                                    <form>
	                                    <table class="widefat post fixed">
	                                       <tr>
	                                       		<td><b>Create Types</b></td>
	                                            <td>Post Created </td>
	                                            <td>Posts Updated</td>
	                                            <td>Categories Created</td>
	                                            <td>Tags Created</td>
	                                        </tr>                                    
	                                        <tr>
	                                        	<td><b>Create Statistics</b></td>
	                                            <td><?php echo $item['postscreated'];?></td>
	                                            <td><?php echo $item['postsupdated'];?></td>
	                                            <td><?php echo $item['catscreated'];?></td>
	                                            <td><?php echo $item['tagscreated'];?></td>
	                                        </tr>
	                                    </table>
                                    </form>
                                    
                                    <br />
                                    
                                    <table class="widefat post fixed">
                                        <?php 
                                        // if a multifile project we loop through the $pro statistics
                                        $insertsuccess = '';
                                        $updatesuccess = '';
                                        $insertfail = '';
                                        $updatefail = '';
                                        if( isset( $pro[ $key ]['filesettype'] ) && $pro[ $key ]['filesettype'] == 'multifile' && isset( $pro[ $key ]['multifileset'] )  )
				                        {
											// hold array of files
											$fileset = $pro[ $key ]['multifileset'];// $filename is always the parent
											
											// loop through the fileset deleting each product using base file name
											foreach( $fileset as $path )
											{
												$insertsuccess .= ' ' . $pro[ $key ]['rowsinsertsuccess'];												
												$updatesuccess .= ' ' .$pro[ $key ]['rowsupdatesuccess'];
												$insertfail .= ' ' . $pro[ $key ]['rowsinsertfail'];
												$updatefail .= ' ' . $pro[ $key ]['rowsupdatefail'];
											}
				                        }
				                        else
				                        {
											$insertsuccess .= ' ' . $pro[ $key ]['rowsinsertsuccess'];												
											$updatesuccess .= ' ' .$pro[ $key ]['rowsupdatesuccess'];
											$insertfail .= ' ' . $pro[ $key ]['rowsinsertfail'];
											$updatefail .= ' ' . $pro[ $key ]['rowsupdatefail'];				                        	
				                        }
				                        
                                        ?>
                                        <tr>
                                        	<td><b>Data Actions</b></td>
                                        	<td>Rows Inserted</td>
                                            <td>Rows Updated</td>
                                            <td>Rows Failed To Insert</td>
                                       		<td>Rows Failed To Update</td>
                                        </tr>                                        
                                        <tr>
                                        	<td><b>Data Statistics</b></td>
                                            <td><?php echo $insertsuccess;?></td>
                                            <td><?php echo $updatesuccess;?></td>
                                            <td><?php echo $insertfail;?></td>
                                            <td><?php echo $updatefail;?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <br />
                                
                                <div class="table table_discussion">
                                    <table class="widefat post fixed">
                                        <tr>
                                        	<td><b>Configuration Part:</b></td>
	                                        <td>Separator</td>
	                                        <td>Quote</td>
	                                        <td>Lines</td>
	                                        <td>Size</td>
                                        </tr>                                         
                                        <tr>
                                        	<td><b>Configuration Value:</b></td>
	                                        <td><?php echo @$csv['format']['seperator']; ?></td>
	                                        <td><?php echo @$csv['format']['quote']; ?></td>
	                                        <td><?php eci_displaycsvlines($key,$pro);?></td>
	                                        <td><?php echo eci_displayfilesize($pro[$key]['filepath'],$pro); ?></td>
                                        </tr>                                   
                                    </table>                                
                                </div>
            
                                <div class="versions">
                                    <form method="post" name="eci_projecthistory_form" action="">    
                                        <input name="eci_filename" type="hidden" value="<?php echo $key; ?>" />     
                                        <br />                 
                                                                                    
                                        <?php ########## TO BE COMPLETE WHEN SCHEDULING IS ADDED
                                        /*
                                        <input class="button-primary" type="submit" name="eci_enableproject_submit" value="Pause" />
                                        <input class="button-primary" type="submit" name="eci_disableproject_submit" value="Continue" />
                                        */
                                        ?>
                                      </form>   							
                                    <br class="clear" />
                                </div>
                                
                            </div>
                        </div><?php
                    }// end is
                }// end of loop each $pro
            }// end of is set $pro
            
            if($totalprojects == 0)
            {
            	_e('You do not have any projects');
            }
            ?>
        </div>	

<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>

