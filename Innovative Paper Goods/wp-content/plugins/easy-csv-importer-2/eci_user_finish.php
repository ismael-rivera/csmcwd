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

// creates a single post by default
if( isset( $_POST['eci_testuser_submit'] ) ){eci_createusers($csv,$pro,$spe,$set,$des,$set['createtest'],true,$pro['current']);}

// runs post creation event based on projects speed profile create integer
if( isset( $_POST['eci_usercreation_submit'] ) ){eci_createusers($csv,$pro,$spe,$set,$des,$spe[$pro[$pro['current']]['speed']]['create'],true,$pro['current'] );}

// delete all project posts	
if( isset( $_POST['eci_deleteprojectusers_submit'] )  ){eci_deleteprojectposts( $pro,$pro['current'],$csv,$set );}

// processing functions
if( isset( $_POST['eci_deletetable_submit'] ) ){eci_deletetable( $pro['current'] );}

if( isset( $_POST['eci_createtable_submit'] ) ){eci_createtable( $pro['current'],$set );}

if( isset( $_POST['eci_seperatorchange_form'] ) )
{
	$csv = get_option('eci_'.$pro['current']);
	$csv['format']['seperator'] = $_POST['eci_seperator'];
	$csv['format']['quote'] = stripslashes( $_POST['eci_quote'] );
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$pro['current'], $csv ) )
	{
		eci_mes( __('Seperator &amp; Quote Saved'),__('Your project will now use the new values, please ensure they are correct by reviewing the tokens list on this screen. If each of your column titles are no on a new line it may indicate incorrect seperator being used.') );
	}
	else
	{
		eci_err( __('Seperator &amp; Quote Failed To Save'),__('Wordpress coule not save the change to the options table at this time, please try again then seek support if the problem continues.') );
	}
}

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
	
        <?php eci_pagetitle('ECI User Import Finish',$pro['current']); ?>
                               
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
        
               <!-- START OF BOX -->
               <div class="postbox">
                   <div class="handlediv" title="Click to toggle"><br /></div>
                   <h3>Final Actions</h3>
                   <div class="inside">
                        <?php require_once('includes/eci_i_finalusers.php');?>    
                   </div>
               </div>  
               <!-- END OF BOX -->		

				<div class='postbox-container' style='width:49%;'>
					<div id="normal-sortables" class="meta-box-sortables">	
					
						<!-- START OF BOX -->
						<div id="dashboard_right_now" class="postbox" >
							<div class="handlediv" title="Click to toggle"><br /></div>
							
							<h3 class='hndle'><span><?php _e('CSV File Format');?></span></h3>
							<div class="inside">
								<div class="table table_content">
									<p class="sub"><?php _e('Change Separator');?></p>
															
									<table>
										<tr class="first">
											<td class="first b b-posts"><a href='#'>Separator:</a></td>
											<td class="t posts"><a href='#'><?php echo $csv['format']['seperator'];?></a>
									</td>
										</tr>
										<tr>
											<td class="first b b-posts"><a href='#'>Quote:</a></td>
											<td class="t posts"><a href='#'><?php echo $csv['format']['quote'];?></a></td>
										</tr>
										<tr>
											<td class="first b b-posts"><a href='#'>Columns:</a></td>
											<td class="t posts"><a href='#'><?php echo $csv['format']['columns'];?></a></td>
										</tr>
									</table>
								</div>
														
								<div class="versions">   	
									<form method="post" name="eci_seperatorchange_form" action="">  
										<select name="eci_seperator" size="1">
											<option value=",">Comma (,)</option>					
											<option value=";">Semi-Colon (;)</option>
											<option value=":">Colon (:)</option>
											<option value="|">Tab (|)</option>
										</select> 
                                        <br />
                                        <select name="eci_quote" size="1">
											<option value='"'>"</option>
											<option value="'">'</option>
										</select>

										<input name="eci_csvfile" type="hidden" value="estorewithpages" /><input class="button-primary" type="submit" name="eci_seperatorchange_form" value="Save" />     
										
									</form>   								
                                    <br class="clear" />
								</div>
								
							</div>
						</div>
						<!-- END OF BOX -->
		
						<!-- START OF BOX -->
						<div id="dashboard_right_now" class="postbox" >
							<div class="handlediv" title="Click to toggle"><br /></div>
							
							<h3 class='hndle'><span>Configuration</span></h3>
							<div class="inside">
								<div class="table table_content">
									<p class="sub"><?php _e('View Help');?></p>
															
									<table>
										<tr class="first">
											<td class="first b b-posts"><a href='#'><?php _e('Created');?>:</a></td>
											<td class="t posts"><a href=''><?php echo date("M j, Y, g:i a",$pro[$pro['current']]['created']);?></a>
											</td>
										</tr>
                                        
										<tr>
											<td class="first b b-posts"><a href='#'><?php _e('Path');?>:</a></td>
											<td class="t posts"><a href='#' title="<?php echo $pro[$pro['current']]['filepath'];?>"><?php _e('View Path');?></a></td>
										</tr>
                                        
										<tr>
											<td class="first b b-posts"><a href='#'><?php _e('Speed Profile Name');?>:</a></td>
											<td class="t posts">
                                                <a href='#'>
													<?php 
													if( isset( $pro[$pro['current']]['speed'] ) )
													{
                                                    	echo $pro[$pro['current']]['speed'];
													}
													else
													{
														echo 'Not Set';
													}
													?>
                                                </a>
                                            </td>
										</tr>										
                                        
									</table>
								</div>
														
								<div class="versions">   	
										<br class="clear" />
								</div>
								
							</div>
						</div>
						<!-- END OF BOX -->
							
                            
						<!-- START OF BOX -->
						<div id="dashboard_right_now" class="postbox" >
							<div class="handlediv" title="Click to toggle"><br /></div>
							
							<h3 class='hndle'><span>Project Database Table</span></h3>
							<div class="inside">
							
								<?php eci_help_icon(ECIBLOG,'eci-project-database-table','');?>
							
								<div class="table table_content">
									<p class="sub">View Help</p>
									<table>
										<tr class="first">
											<td class="first b b-posts"><a href='#'>Table Created:</a></td>
											<td class="t posts">
                                                <form method="post" name="eci_projecttable_form" action=""> 
													<?php 
                                                    if( eci_istable( $pro['current'] ) )
                                                    { 
                                                        echo '<input class="button-primary" type="submit" name="eci_deletetable_submit" value="Yes, Delete Table" />'; 
                                                    }
                                                    else
                                                    { 
                                                        echo '<input class="button-primary" type="submit" name="eci_createtable_submit" value="No, Create Table" />'; 
													}
                                                    ?>
                                                </form>
                                            </td>
										</tr>
                                        
										<tr class="first">
											<td class="first b b-posts"><a href='#'>Table Name:</a></td>
											<td class="t posts"><a href='#'><?php echo $csv['sql']['tablename']; ?></a></td>
										</tr>										
                                        
                                        <tr class="first">
											<td class="first b b-posts"><a href='#'>Column Names</a></td>
											<td class="t posts"><a href='#'></a></td>
										</tr>

										<?php
										$query = "SHOW COLUMNS FROM " . $csv['sql']['tablename'];
										$rs = mysql_query($query);
										$i = 0;
										
										if( $rs )
										{
										
											while ($row = mysql_fetch_array($rs)) 
											{
												?>
												<tr class="first">
													<td class="first b b-posts"><a href='#'><?php echo $i; ?></a></td>
													<td class="t posts"><a href='#'><?php echo $row[0]; ?></a></td>
												</tr>
											<?php 
												++$i;
											}
										}
                                        ?>
        
									</table>
								</div>
														
								<div class="versions">   	
										<br class="clear" />
								</div>
								
							</div>
						</div>
						<!-- END OF BOX -->
                        
                                                   
                            			
					</div>	
				</div>
		
				<!-- LEFT COLUMN START -->
				
				<div class='postbox-container' style='width:49%;'>
					<div id="side-sortables" class="meta-box-sortables">
		
						<!-- START OF BOX -->
						<div id="dashboard_right_now" class="postbox" >
							<div class="handlediv" title="Click to toggle"><br /></div>
							
							<h3 class='hndle'><span>Progress</span></h3>
							<div class="inside">
								<div class="table table_content">
									<p class="sub">View Help</p>
									<table>
										<tr class="first">
											<td class="first b b-posts"><a href='#'>Users Created:</a></td>
											<td class="t posts"><a href=''><?php echo $pro[$pro['current']]['postscreated'];?></a></td>
											<td class="first b b-posts"><a href='#'>Users Failed:</a></td>
											<td class="t posts"><a href=''><?php echo $pro[$pro['current']]['postsfailed'];?></a></td>
										</tr>							
									</table>
								</div>
														
								<div class="versions">   	
										<br class="clear" />
								</div>
								
							</div>
						</div>
						<!-- END OF BOX -->			
									
                        <!-- START OF BOX -->
                       <div class="postbox">
                            <div class="handlediv" title="Click to toggle"><br /></div>
                            <h3>User Meta Keys</h3>
                            <div class="inside">
                            	<p>Not currently available. I will add this upgrade when interest has been shown due to the user import being
                            	a new feature. If you would like to use the user import and want it to be improved please go to the <a href="http://forum.webtechglobal.co.uk">WebTechGlobal Forum</a> and register your interest.</p>
                            </div>
                        </div>  
                        <!-- END OF BOX -->				
								
					<!-- END OF COLUMN -->			
					</div>	
				</div>
			</div>
	
		<div class="clear"></div>
        
		</div><!-- dashboard-widgets-wrap -->
        
    
        <div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">
                <div class="postbox closed">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3>Project SQL Queries</h3>
                    <div class="inside">
                        <h4>Pre-prepared Insert Query</h4>
                        <form>
                            <textarea rows="10" cols="78"><?php if( isset(  $csv['sql']['insertstart'] ) ){echo $csv['sql']['insertstart'];} ?></textarea> 
                        </form>  
                        <br />
                        <br />
                        <h4>Last Insert Query Used</h4>
                        <form>
                            <textarea rows="10" cols="78"><?php if( isset(  $csv['sql']['lastinsert'] ) ){echo $csv['sql']['lastinsert'];} ?></textarea> 
                        </form>  
                        <br />
                        <br />
                        <h4>Last Update Query Used</h4>
                        <form>
                            <textarea rows="10" cols="78"><?php if( isset(  $csv['sql']['lastupdate'] ) ){echo $csv['sql']['lastupdate'];} ?></textarea> 
                        </form>  
                    </div>
                </div>
            </div>
                      
</div><!-- wrap -->
			
<?php
}
?>

<?php eci_footer(); ?>