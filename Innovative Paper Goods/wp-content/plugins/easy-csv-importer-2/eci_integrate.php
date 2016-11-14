<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
$theme = get_current_theme();
?>      

<div class="wrap">

    <?php @eci_pagetitle('ECI Integration Support',$pro['current']); ?>
    
    <p><?php _e('View a list of commonly integrated plugins or themes. User and technical information will be updated to this
    page later when most plugins and themes are fully integrated. The Easy Project system created June 2011 requires much
    of the integration, sometime later in the year more details will be put on this page.');?></p>   
	
	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Wordpress Itself</span></h3>
				<div class="inside">
				<p><?php _e('There are ways to increase the efficiency of Easy CSV Importer but many of them require coding knowledge.');?></p>
				<h4>Post Revisions In Posts Table</h4>
				<p>You can prevent post revisions by placing two lines of code in your wp-config.php file. See here for more information </p>
                </div>
            </div>  
             
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Google</span></h3>
				<div class="inside">
				<h4>+1</h4>
				<p>For more information about the plus one button please visit <a href="http://www.google.com/+1/button/" target="_blank">About Google +1 button</a>
				and if you want to add it to your page you can get the snippets from <a href="http://www.google.com/webmasters/+1/button/" target="_blank">Google +1 Webmasters</a>. The ECI 
				plugin provides snippets on the Designs page for quickly adding the button to your design.</p>
				<object width="640" height="390"><param name="movie" value="http://www.youtube.com/v/4RyY2-ofP4g?version=3&amp;hl=en_GB&amp;hd=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/4RyY2-ofP4g?version=3&amp;hl=en_GB&amp;hd=1" type="application/x-shockwave-flash" width="640" height="390" allowscriptaccess="always" allowfullscreen="true"></embed></object>
                </div>
            </div>  
            
            <?php 
            $classipresstheme = '';
            if($theme == 'ClassiPress'){$classipresstheme = '(Your Current Theme)';}
            ?>         
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('ClassiPress Theme');?> <?php echo $classipresstheme;?></span></h3>
				<div class="inside">
				<p>Easy CSV Importer will eventually adapt to work with ClassiPress when it detects the theme installed. The interface
				will change and forms will even be complete automatically to speed up the creation of a project and make it Easy for you!</p>
				<p><a href="http://www.appthemes.com/cp/go.php?r=438&i=b1"><img src="http://www.appthemes.com/ads/cp-468x60a.gif" border=0 alt="ClassiPress Premium WordPress Theme" width=468 height=60></a></p>
                </div>
            </div>  

             <?php 
            $shopperpresstheme = '';
            if($theme == 'V.I.P ShopperPress Theme'){$shopperpresstheme = '(Your Current Theme)';}
            ?>              
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('ShopperPress Theme');?>  <?php echo $shopperpresstheme;?></span></h3>
				<div class="inside">
				<p>Easy CSV Importer will eventually adapt to work with ShopperPress when it detects the theme installed. The interface
				will change and forms will even be complete automatically to speed up the creation of a project and make it Easy for you!</p>
				<p><a href="https://secure.avangate.com/order/cart.php?PRODS=2929632&QTY=1&AFFILIATE=8691"><img src="http://shopperpress.com/inc/images/banners/468x60.gif" border="0"></a></p>
                </div>
            </div>

             <?php 
            $couponpresstheme = '';
            if($theme == 'CouponPress'){$couponpresstheme = '(Your Current Theme)';}
            ?>              
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('CouponPress Theme');?>  <?php echo $couponpresstheme;?></span></h3>
				<div class="inside">
				<p>Easy CSV Importer will eventually adapt to work with ShopperPress when it detects the theme installed. The interface
				will change and forms will even be complete automatically to speed up the creation of a project and make it Easy for you!</p>
				</div>
            </div>
                       
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('PopUpDomination Plugin');?></span></h3>
				<div class="inside">
					<p><?php _e('This is a great plugin for offering page overlay popups. I plan to keep my eye on it for a 
					long time and have ideas how to combine its power with Easy CSV Importer. If your creating sites using affiliate
					data and you want people to return, you must use a plugin such as PopUpDomination to offer opt-in mailing lists to compete.
					There are some free plugins but if your serious about creating a list the service backing this plugin will make all the difference.')?></p>
					<a href="http://wtg2011.PopDom.hop.clickbank.net"><img src="http://www.popupdomination.com/affiliate/images/aff-images/468-60.jpg" alt="Popup Domination" border="0" /></a>
                </div>
            </div>          
                    
            <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Related Items Plugins</span></h3>
				<div class="inside">
				<p><?php _e('It is important to display related posts/items/products to reduce bounce rate. Bounce rate is the number of
				visitors that leave your site in search of another similiar site because their search dried up. To reduce this, you need
				to offer up more content and local links. Offering similiar posts alone will increase your sales or affiliate clicks.');?></p>
				<h4>Yet Another Related Posts Plugin</h4>
				<p>"<?php _e('Yet Another Related Posts Plugin (YARPP) gives you a list of posts and/or pages related to the current entry, introducing the reader to other relevant content on your site');?>"</p>
				<p><a href="http://wordpress.org/extend/plugins/yet-another-related-posts-plugin/" title="Yet Another Related Posts Plugin">Click here to view this plugin</a></p>
                </div>
            </div>                    
                    
			<?php 
			$aioseoplugin = 'not active';
			if(eci_is_plugininstalled('all-in-one-seo-pack'))
			{
				$aioseoplugin = 'Active';
			}?>
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>All In One SEO Plugin (<?php echo $aioseoplugin;?>)</span></h3>
				<div class="inside">
				
					<h4>Coming soon</h4>
					<p>Information coming later, currently preparing integration.</p>
				
				</div>
            </div>  

			<?php 
			$wpecommerceplugin = 'not active';
			if(eci_is_plugininstalled('wp-e-commerce'))
			{
				$wpecommerceplugin = 'Active';
			}?>
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>WP e-Commerce Plugin (<?php echo $wpecommerceplugin;?>)</span></h3>
				<div class="inside">
				
					<h4>Coming soon</h4>
					<p>Information coming later, currently preparing integration.</p>
				
				</div>
            </div>  

			<?php 
			$jigoshopplugin = 'not active';
			if(eci_is_plugininstalled('jigoshop'))
			{
				$wpecommerceplugin = 'Active';
			}?>
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>JigoShop Plugin (<?php echo $jigoshopplugin;?>)</span></h3>
				<div class="inside">
				
					<h4>Coming soon</h4>
					<p>Information coming later, currently preparing integration.</p>
				
				</div>
            </div>  
            
			<?php 
			$estoreplugin = 'not active';
			if(eci_is_plugininstalled('wp-cart-for-digital-products'))
			{
				$wpecommerceplugin = 'Active';
			}?>
             <div id="dashboard_recent_comments" class="postbox closed" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>eStore Plugin (<?php echo $estoreplugin;?>)</span></h3>
				<div class="inside">
				
					<h4>Coming soon</h4>
					<p>Information coming later, currently preparing integration.</p>
				
				</div>
            </div>                        
    	<div class="clear"></div>
    </div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>