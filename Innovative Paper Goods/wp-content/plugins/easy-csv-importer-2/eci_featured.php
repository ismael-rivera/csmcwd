<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>       

<div class="wrap">

    <?php @eci_pagetitle('ECI Featured Projects',$pro['current']); ?>
    
    <p><?php _e('I need permission to display sites here. If you would like your site included in the showcase of
Easy CSV Importer projects please email webmaster@webtechglobal.co.uk. This will be seen on the free download of the
plugin and will be a source of extra traffic to your home page!');?></p>   
	
	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('ClassiPress Example Project')?></span></h3>
				<div class="inside">
				Get your site here, contact webmaster@webtechglobal.co.uk
                </div>
            </div>

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('ShopperPress Example Project')?></span></h3>
				<div class="inside">
				Get your site here, contact webmaster@webtechglobal.co.uk
                </div>
            </div>
           
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Car Sales Example Project')?></span></h3>
				<div class="inside">
				Get your site here, contact webmaster@webtechglobal.co.uk
                </div>
            </div>          
            
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Tourism Example Project')?></span></h3>
				<div class="inside">
				Get your site here, contact webmaster@webtechglobal.co.uk
                </div>
            </div>  
                      
    	<div class="clear"></div>
    </div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>