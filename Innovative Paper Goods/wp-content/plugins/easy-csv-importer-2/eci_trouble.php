<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>     

<div class="wrap">

    <?php @eci_pagetitle('ECI Troubleshooting',$pro['current']); ?>
    
	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

            <div id="dashboard_recent_comments" class="postbox" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Problems and Solutions')?></span></h3>
				<div class="inside">
          		
	          		<iframe src="http://www.webtechglobal.co.uk/blog/help/easy-csv-importer-troubleshooting" width="100%" height="600">
					  <p>Your browser does not support iframes.</p>
					</iframe>

                </div>
            </div>

    	<div class="clear"></div>
    </div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>