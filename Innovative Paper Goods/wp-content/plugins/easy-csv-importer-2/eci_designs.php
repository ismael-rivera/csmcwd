<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>  

<?php 		
// processing functions
if( isset( $_POST['eci_design_submit'] ) )
{	
	if(!isset($_POST['eci_aioseotitle'])){$_POST['eci_aioseotitle'] = '';}
	if(!isset($_POST['eci_aioseodescription'])){$_POST['eci_aioseodescription'] = '';}
	if(!isset($_POST['eci_wpseokeywords'])){$_POST['eci_wpseokeywords'] = '';}
	if(!isset($_POST['eci_wpseofockey'])){$_POST['eci_wpseofockey'] = '';}
	if(!isset($_POST['eci_wpseotitle'])){$_POST['eci_wpseotitle'] = '';}
	if(!isset($_POST['eci_wpseodescription'])){$_POST['eci_wpseodescription'] = '';}
	
	$_POST = stripslashes_deep($_POST);
	eci_savedesign($_POST['eci_name'],$_POST['eci_title'],$_POST['eci_content'],' ',
	$_POST['eci_aioseotitle'],$_POST['eci_aioseodescription'],
	$_POST['eci_wpseokeywords'],$_POST['eci_wpseofockey'],$_POST['eci_wpseotitle'],$_POST['eci_wpseodescription'] );
}       
	                            
// is user requesting design test
if( isset( $_POST['eci_testlayoutspage_submit'] ) || isset( $_POST['eci_testlayoutspost_submit'] ) )
{
	eci_designtest(	$_POST['eci_design'],$_POST['eci_project'] );
}

// process snippet generator - image button - tokens
if( isset( $_POST['eci_snip_imagebut_t'] ) )
{
	eci_snippetgenerator('imagebutton','t');
}

// process snippet generator - image button - shortcodes
if( isset( $_POST['eci_snip_imagebut_s'] ) )
{
	eci_snippetgenerator('imagebutton','s');
}

// process snippet generator - image only - tokens
if( isset( $_POST['eci_snip_image_t'] ) )
{
	eci_snippetgenerator('image','t');
}

// process snippet generator - image only - tokens
if( isset( $_POST['eci_snip_image_s'] ) )
{
	eci_snippetgenerator('image','s');
}

// process snippet generator - link - tokens
if( isset( $_POST['eci_snip_link_t'] ) )
{
	eci_snippetgenerator('link','t');
}

// process snippet generator - link - shortcodes
if( isset( $_POST['eci_snip_link_s'] ) )
{
	eci_snippetgenerator('link','s');
}			

$filesnippet = get_getsnippet();

// get data arrays
$set = get_option('eci_set');
$que = get_option('eci_que');
$pro = get_option( 'eci_pro' );
@$csv = get_option( 'eci_' . $pro['current'] );
$des = get_option('eci_des');

// if no current project do not display anything on this page
if( isset( $pro['current'] ) && $pro['current'] != 'None Selected' )
{
	// ensure profile has been saved, this has been added to due a bug on 16th July 2011
	// project is being created, but not everything is being saved
	if(!isset($csv['format']))
	{
		eci_err('CSV Profile Missing','It appears the plugin has not been able to save your CSV files format.
		This is required for the plugin to operate. Please ensure you have a valid CSV file by opening it in Excel.
		If your sure your CSV file is not at fault please contact the webmaster@webtechglobal.co.uk');
	}
	else
	{
?>

<div class="wrap">
	<div id="icon-index" class="icon32"><br /></div>

	<?php eci_pagetitle('ECI WYSIWYG Designs',$pro['current']); ?>
		<div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">

		<?php	
		// open current projects design by default else check if user is working and submitting another design
		if( isset( $csv['design'] ) && !isset( $_POST['eci_selectdesign_submit'] ) && !isset( $_POST['eci_newdesign_submit'] ) ) 
		{
			$d = $csv['design'];
		}		
		elseif( isset( $_POST['eci_newdesign_submit'] ) ) 
		{
			$d = '';
			$des[$d]['title'] = '';
			$des[$d]['content'] = '';
			$des[$d]['shortcodedesign'] = '';
			$des[$d]['seotitle'] = '';
			$des[$d]['seodescription'] = '';
     		$des[$d]['wpseokeywords'] = '';
	        $des[$d]['wpseofockeyword'] = '';
	        $des[$d]['wpseotitle'] = '';
	        $des[$d]['wpseodescription'] = '';
		}
		elseif( isset( $_POST['eci_selectdesign_submit'] ) || isset( $_POST['eci_design_submit'] ) )
		{
			$d = $_POST['eci_name'];// open submitted design name to continue working on it
		}
		else
		{
			$d = 'Default';// open default installed template when creating new design
		}
		?>		
        <div class="postbox">
            <div class="handlediv" title="Click to toggle"><br /></div>
            <h3><?php _e('WYSIWYG Editor')?></h3>
            <div class="inside">
             	<?php eci_help_icon(ECIBLOG,'eci-wysiwyg-editor','');?>
        		<br />
                <form method="post" name="eci_design_form" action="">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <label for="designname"><strong><?php _e('Current Design Name')?>: </strong></label>
                            <input type="text" name="eci_name" size="12" maxlength="12" value="<?php echo $d;?>" id="designname" readonly /> (cannot be changed in free edition)
                        </div>
                    </div>
                                                                
                    <div id="titlediv">
                        <div id="titlewrap">
                            <strong><?php _e('Post Title')?></strong><input type="text" name="eci_title" size="30" value="<?php echo $des[$d]['title']; ?>" id="title" />
                        </div>
                    </div>     
                    
                    <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
                            <?php the_editor( $des[$d]['content'], 'eci_content'); ?>
                        <div id="post-status-info">
                            <span id="wp-word-count" class="alignleft"></span>
                        </div>
                    </div>     		

			        <div class="postbox">
			            <div class="handlediv" title="Click to toggle"><br /></div>
			            <h3>Tokens</h3>
			            <div class="inside">
			            	<?php eci_help_icon(ECIBLOG,'eci-tokens-list','');?>
			            	<p>Copy and paste these tokens into the editor. Tokens are replaced by actual data. 
			            	Styling you apply to your tokens will be applied to the data that replaces them.</p>
			                <?php eci_displaytokenlist(); ?>
			            </div>
			        </div>
                                            
                    <input class="button-primary" type="submit" name="eci_design_submit" value="Save" />
                </form>
            </div>
        </div>
		       

	<div class="clear"></div>
	</div><!-- dashboard-widgets-wrap -->
</div><!-- wrap -->

<?php
	}// end if $csv format not set
}

eci_footer(); ?> 
                            
                            