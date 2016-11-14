<div id="footer_wrapper">
<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */
?>

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>
<table id="footr_tbl" class="grid" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <p>&copy; Copyright 2011 -
			<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">www.innovativepapergoods.com</a>
           - All Rights Reserved</p>
    <div id="footer_menu"><?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?></div>               
     </td>
    <td align="right"><p>Powered by <a href="http://wordpress.org/" title="Semantic Personal Publishing Platform" rel="generator">Wordpress</a></n><br/>Design by <a href="#" title="Webdesigner Website" rel="generator">Ismael Rivera</a></p></td>
  </tr>
</table>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</div>
</body>
</html>