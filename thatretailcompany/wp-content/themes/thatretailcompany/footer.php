<div>
</div>
<div id="footer_wrapper" class="wrapper">
    <div id="footer" class="grid">
    <?php
    /**
     * The template for displaying the footer.
     *
     * Contains the closing of the id=main div and all content
     * after.  Calls sidebar-footer.php for bottom widgets.
     *
     * @package WordPress
     * @subpackage thatretailcompany
     * @since thatretailcompany 1.0
     */
    ?>
    
     <p>&copy; 2012 That Retail Company. All rights reserved.</p>
     <div id="footer_menu" role="navigation">
		<?php wp_nav_menu( array( 'container_class' => 'menu-footer', 'theme_location' => 'footer') ); ?>
	</div><!-- #access -->     

</div> <!--#footer-->
</div> <!--#footer_wrapper-->
<?php $sliderArray = slidertabsArray(); 
$slide_tabs_str = implode (", ", $sliderArray);
//echo $slide_tabs_str;
?>
<?php //$slide_tabs_str = implode(":#:",$slide_tabs); ?>


<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/libs/jquery-ui-1.8.21.custom.min.js">
</script>
<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/libs/jquery.cycle.all.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/libs/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/libs/jquery.uniform.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/libs/jquery.selectbox-0.1.3.js"></script> 

<script type="text/javascript">
var slide_tabs_str = "<?php print $slide_tabs_str ?>";
</script>  

<script type="text/javascript" src="<?php bloginfo("template_url"); ?>/js/my_scripts.js"></script>   
    
	<?php
        /* Always have wp_footer() just before the closing </body>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to reference JavaScript files.
         */
    
        wp_footer();
    ?>
</body>
</html>