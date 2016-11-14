<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>  
 
<?php @eci_pagetitle('Easy CSV Importer',$pro['current']); ?>
<p>Thank you for downloading Easy CSV Importer. Please be aware that the <strong>free edition of ECI is tailored for small
projects</strong> only. The full edition has a lot of power and ability. The support requests this plugin generates is massive
and even more so when users have too much power to push their server to the point of causing problems. The current version
of the free edition is safer and perfect for the majority of users. I hope you enjoy it.</p>

    <h4>Special Offer 1</h4>    
    <p>Free domain and 1 year hosting with every purchase of the paid edition. Please request it after purchase. </p>

    <h4>Special Offer 2</h4>    
    <p>Free 1 year hosting if need hosting to run Wordpress and the free edition of Easy CSV Importer. Contact sales@webtechglobal.co.uk</p>

    <h4>Special Offer 3</h4>    
    <p>Free 1 years hosting when you get your first Easy CSV Importer Affiliate sale, you must request it.</p>

	<div id="dashboard-widgets" class="metabox-holder">
		<div class='postbox-container' style='width:49%;'>
			<div id="normal-sortables" class="meta-box-sortables">
			
				<div id="dashboard_plugins" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class='hndle'><span>Authors Support</span></h3>
					<div class="inside">
						<ul>
						  <li><strong>Current Version:</strong> <?php echo WP_EASYCSVIMPORTER_VERSION; ?></li>
						  <li><strong>Name:</strong> Ryan Bayne</li>
					      <li><strong>Location:</strong> Scotland</li>
					      <li><strong>Education:</strong> University of Abertay (BSc Web Design)</li>
					      <li><strong>Email:</strong> ryan@webtechglobal.co.uk</li>
					      <li> </li>
					  </ul>
					  <script type="text/javascript"><!--
						google_ad_client = "ca-pub-4923567693678329";
						/* ECI Free Home */
						google_ad_slot = "5819797188";
						google_ad_width = 336;
						google_ad_height = 280;
						//-->
						</script>
						<script type="text/javascript"
						src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
				  </div>
				</div>

				<div id="dashboard_plugins" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class='hndle'><span>Please Take A Few Minutes To Support ECI</span></h3>
					<div class="inside">
						<p>There are various ways to support the plugin that only take a few moments. If you use
						the plugin a lot please consider doing some of the following actions to contribute:</p>
						<p>
							<ul>
								<li>1. Donate to paypal@webtechglobal.co.uk and state clearly that it is for ECI</li>
								<li>2. Rate the plugin 5 star on Wordpress - <a href="http://wordpress.org/extend/plugins/easy-csv-importer-2/stats/">click here</a></li>
								<li>3. Consider Google AdSense Ads</li>
								<li>4. Subscribe to the plugins own RSS Feed</li>
								<li>5. Put a link on your site to www.wtg.co/eci</li>
								<li>6. Click on the Google +1 Button on www.wtg.co/eci</li>
								<li>7. Get your site added to the Examples page</li>
								<li>8. Seek help on the forum first and help others while there</li>
							</ul>
						</p>
				  </div>
				</div>				
												
			</div>	
		</div>
			
			<!-- COLUMN START -->

<div class='postbox-container' style='width:49%;'>
	<div id="side-sortables" class="meta-box-sortables">
	
		<div id="dashboard_quick_press" class="postbox " >
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class='hndle'><span>Plugin News<span class="postbox-title-action"></span></span></h3>
			<div class="inside">
				<p>All updates regarding new features is provided here. Please read the blog and comment there rather than emailing me. This saves me time
				which is best spent on the plugin and helps others to know how popular ECI is!</p>
				<script src="http://feeds.feedburner.com/easycsvimporter?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/easycsvimporter"></a><br/>Powered by FeedBurner</p> </noscript>
			</div>
		</div>
		
		<div id="dashboard_secondary" class="postbox " >
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class='hndle'><span>Plugins Links <span class="postbox-title-action"><a href="/wordpress-testing/eci/wp-admin/index.php?edit=dashboard_secondary#dashboard_secondary" class="edit-box open-box"></a></span></span></h3>
			<div class="inside">
			  <ul>
			    <li><a href="http://www.webtechglobal.co.uk/wp-content/plugins/wp-affiliate-platform/affiliates/login.php" target="_blank">Affiliates</a></li>
			    <li><a href="http://forum.webtechglobal.co.uk/" target="_blank">Forum</a></li>
			    <li><a href="http://www.webtechglobal.co.uk/category/blog/wordpress/easy-csv-importer" target="_blank">Blog</a></li>
			   <li><a href="http://www.webtechglobal.co.uk/wordpress-easy-csv-importer/" target="_blank">Sales Page</a></li>
			    <li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/eci-developer-guide" target="_blank">Developer Guide</a></li>
			  	<li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/easy-csv-importer-faq" target="_blank">FAQ</a></li>
			  	<li><a href="http://www.webtechglobal.co.uk/blog/help/easy-csv-importer-troubleshooting" target="_blank">Troubleshooting</a></li>
			  	<li><a href="http://www.webtechglobal.co.uk/blog/information/easy-csv-importer-pad-file" target="_blank">PAD File</a></li>
			  	<li><a href="http://www.webtechglobal.co.uk/blog/wordpress/eci-installation" target="_blank">Installation</a></li>			  	
			    <li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/easy-csv-importer-features-guide" target="_blank">Features Guide</a></li>			  				  
			 	<li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/easy-csv-importer-change-log" target="_blank">Change Log</a></li>	
			 	<li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/eci-beta-translation" target="_blank">Translation</a></li>			  				  
			 	<li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/eci-rapidshare-tutorial-downloads" target="_blank">RapidShare Tutorial Downloads</a></li>			  				  
			    <li><a href="http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/eci-tutorial-videos" target="_blank">Video Tutorials</a></li>
			    <li><a href="http://twitter.com/webtechglobal" target="_blank">Twitter</a></li>
			  </ul>
		    </div>
		</div>
				
	</div>	
</div>

</div><!-- wrap -->

<?php eci_footer(); ?>

