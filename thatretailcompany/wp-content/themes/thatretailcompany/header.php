<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage thatretailcompany
 * @since thatretailcompany 1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 5]><html id="ie5" class="ie"><![endif]-->
<!--[if IE 5.5000]><html id="ie55" class="ie"><![endif]-->
<!--[if IE 6]><html id="ie6" class="ie"><![endif]-->
<!--[if IE 7]><html id="ie7" class="ie"><![endif]-->
<!--[if IE 8]><html id="ie8" class="ie"><![endif]-->
<!--[if IE 9]><html id="ie9" class="ie"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 * We filter the output of wp_title() a bit -- see
	 * twentyten_filter_wp_title() in functions.php.
	 */
	wp_title( '|', true, 'right' );

	?></title>    
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/allotherbrowsers.css" />
<link href='http://fonts.googleapis.com/css?family=Play:400,700' rel='stylesheet' type='text/css'>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<style type="text/css">
			@import url('<?php bloginfo( 'template_url' ); ?>/css/c-css.php');			
		</style>
<?php 
    $css = get_post_meta($post->ID, 'css', true);
    if (!empty($css)) { ?>
        <style type="text/css">
        <?php echo $css; ?>   
        </style>
<?php  } ?>

<?php wp_enqueue_script("jquery"); ?>

<?php

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<?php flush(); ?>
<body <?php body_class(); ?>>

<script>
if (navigator.platform) {
  var p = navigator.platform;
  //document.write( navigator.platform );
  if (p == 'Win32') {
	  document.body.className += ' win32';
	  }
  else if (p == 'MacIntel') {
	  document.body.className += ' macintel';
	  }
  else if (p === 'iPad' || p === 'iPhone' || p === 'iPod' ) {
	  document.body.className += ' ios';
	  }	     	   
}

/*if (navigator.userAgent) {
	document.write( '</br>' + navigator.userAgent );
	}*/

  //alert(document.body.className);
</script>
<div id="header" class="grid">
<!--[if lte IE 6]><a href="<?php bloginfo( 'template_url' ); ?>/warnings/ie6/lt-ie6-warning.html" class="fancybox fancybox-iframe" id="fancybox-auto"></a><![endif]-->
<h1>
	  <a class="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
  </h1>
		<div id="phone_number"><span class="tele">1300 26 46 86</span><br/><span class="cs">24/7 Customer Service</span></div>
        <div id="top_menu">
	  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
		<!--<a href="#content" title="<?php /*esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' );*/ ?></a>-->
		<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
		<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
	</div><!-- #access -->
    
  </div> <!--#header-->   