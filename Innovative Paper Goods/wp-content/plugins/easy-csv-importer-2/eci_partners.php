<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;
?>         

<div class="wrap">

    <?php @eci_pagetitle('ECI Affiliate Partners',$pro['current']); ?>
   
	<!-- POST BOXES START -->
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

            <div id="dashboard_recent_comments" class="postbox" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span><?php _e('Information')?></span></h3>
				<div class="inside">
                    <p><?php _e('Earn 50% commission by promoting Easy CSV Importer. I have created a range of banners to help
                    you sell and more resources are planned. You can provide voucher codes to your customers
                    that guarantee commission even if the cookie is deleted from their computer.');?></p>
                    <p>Visit our <a href="http://www.webtechglobal.co.uk/partners">Partners resources area</a> to download the original PNG files and edit them to suit
                    your needs.</p>
                </div>
            </div>

            <div id="dashboard_recent_comments" class="postbox" >
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class='hndle'><span>Banner</span></h3>
				<div class="inside">
					<p>You can copy and paste the html snippets here to use the images displayed. Please remember
					to add your WebTechGlobal affiliate username to the url. You may also download the original
				    PNG files and other resources by visiting our <a href="http://www.webtechglobal.co.uk/partners">
				    Partners page</a>. Add your own voucher codes to the 
				    banners and change them to suit your site.</p>
                    <form>  
	                    <table class="widefat post fixed">
	                    
	                        <tr><td width="70"><b>Banner</b></td><td width="200"><b>Size</b></td><td><b>Snippet</b></td></tr>
	                        
	                        <?php 
	                        $affid = 'YOURID';
	                        $image = '';
	                        function eci_affiliatesnippet($size,$affid)
	                        {
	                        	$snippet = '<a href="http://www.webtechglobal.co.uk/wordpress-easy-csv-importer?ap_id='.$affid.'"><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/'.$size.'jpg" /></a>';	                      	
	                         	echo $snippet;
	                        }
	                        ?>
	                        
	                        <tr>
	                            <td>468 x 60</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('468x60',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/468x60.jpg" /></td>
	                        </tr>
	                        
	                        <tr>
	                            <td>120 x 600</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('120x600',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/120x600.jpg" /></td>
	                        </tr>	                        
	                        
                        	<tr>
	                            <td>160 x 600</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('160x600',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/160x600.jpg" /></td>
	                        </tr>	    	                        
	                        
                        	<tr>
	                            <td>200 x 200</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('200x200',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/200x200.jpg" /></td>
	                        </tr>	    	                        
	                        
                   			<tr>
	                            <td>250 x 250</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('250x250',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/250x250.jpg" /></td>
	                        </tr>	    	                        
	                        
                   			<tr>
	                            <td>300 x 250</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('300x250',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/300x250.jpg" /></td>
	                        </tr>	    	                        
	                        	                        	                        
                   			<tr>
	                            <td>336 x 280</td>
	                            <td><textarea rows="8" cols="21"><?php eci_affiliatesnippet('336x280',$affid);?></textarea> </td>	                            	                        
	                            <td><img src="http://www.webtechglobal.co.uk/wp-content/gallery/easy-csv-importer-affiliate-banners/336x280.jpg" /></td>
	                        </tr>	    	                                               	  
	                        	  	                        
	                   </table><br />
	     
                   </form>   
                 
                 
                
                
                
                </div>
            </div>

            <div class="postbox closed">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3>Your Earnings</h3>
                <div class="inside">
                <p>If the WebTechGlobal Partners system is a success we will create an API so that your own
                personal earnings can be viewed here. For now please let us know if this would help. We feel
                that our affiliates are also users.</p>
                </div>
            </div>
           
    	<div class="clear"></div>
    </div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php eci_footer(); ?>