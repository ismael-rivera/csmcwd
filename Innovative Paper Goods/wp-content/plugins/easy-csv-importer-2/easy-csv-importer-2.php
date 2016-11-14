<?php
/*
	Plugin Name: Easy CSV Importer
	Version: 6.2.5
	Plugin URI:http://www.webtechglobal.co.uk/services/wordpress-support/premium-plugins/easy-csv-importer
	Description: Import CSV files and auto blog your data into posts,pages,custom post types,custom fields,categories,custom taxonomy,update,export and much more
	Author: WebTechGlobal
	Author URI: http://www.webtechglobal.co.uk
*/			

eci_debugmode(false,false);// true,true will use strict errors. true,false will be standard error display

// global current version is used to ensure users installation is ready for new changes
define("WP_EASYCSVIMPORTER_VERSION", "6.2.5");
global $ecicurrentversion;
$ecicurrentversion = 625;// i.e  if( $version >= 603 )

/**
 * Displays errors, Wordpress hides them by default, $i must equal true or false
 * @param unknown_type $i
 */
function eci_debugmode($i,$errorreporting = false)
{
	if( $i == true )
	{
		global $wpdb;
		ini_set('display_errors',1);
		if($errorreporting == false)
		{
			error_reporting(E_ALL);
		}
		else 
		{
			error_reporting(-1);
			define('WP_DEBUG', true);
		}
		$wpdb->show_errors();
	}
}

$set = get_option( 'eci_set' );
$pro = get_option( 'eci_pro' );

// installation trigger
register_activation_hook( __FILE__ ,'eci_activate');
function eci_activate()
{
	require_once('functions/eci_f_install.php');
	eci_install_options();
}

// include efficiency controller 
require_once('includes/eci_i_efficiency.php');

// include variables
require_once('includes/eci_i_variables.php');

// load hooks - defined in effeciancy file
if( ECI_EFF_HOOKSFILE ){require_once('includes/eci_i_hooks.php');}

// load resources specific to user - this allows us to seperate admin only functions or public only
if( is_admin() )
{	
	// include more data arrays for using globally - usually called at points of interface beginning or pre processing
	$spe = get_option('eci_spe');
	$des = get_option('eci_des');

	// hook new project submissions - processing before menu output required as menu changes to suit project
	add_action('admin_menu', 'eci_newproject',1);

	// get functions for admin users
	require_once('script/eci_interfacescripts.php');
	require_once('functions/eci_f_global.php');
	require_once('functions/eci_f_admin.php');	
	require_once('functions/eci_f_data.php');
	require_once('functions/eci_f_interface.php');
	require_once('functions/eci_f_process.php');
	require_once('functions/eci_f_install.php');
	require_once('functions/eci_f_widget.php');
	
	// Hook into the 'wp_dashboard_setup' action to register our other functions
	add_action('wp_dashboard_setup','eci_add_dashboard_rsswidgets');
	
	// add actions/hooks for none automated processes (those go in hooks file)
	add_action('init','eci_export_finish');	
										
	// add menu
	add_action('admin_menu', 'eci_menu');

	// Loads WP Styles If On Test Page
	if(isset($_SERVER['REQUEST_URI']))
	{
		if(strpos($_SERVER['REQUEST_URI'],'eci_testpage') > 0)
		{
			add_action('admin_head','eci_wp_add_styles');
		}
	}		
	
	if( isset( $_GET['page'] ) && $_GET['page'] == 'eci_designs')
	{
		add_action('admin_head','eci_wysiwygeditor');
	}	
	
	// process Cloaked URL Click
	add_action('status_header','eci_cloakedurlsubmission');

	// add theme support for thumbnails required for has_post_thumbnail on post test
	add_theme_support('post-thumbnails');
}
else
{
	// get functions
	require_once('functions/eci_f_data.php');
	require_once('functions/eci_f_widget.php');
	require_once('functions/eci_f_global.php');
}

/**
 * Includes Function Files For Public Visitors That May Not Normally Be Included
 */
function eci_loadallfunctions()
{
	require_once('functions/eci_f_process.php');
}

/**
 * include pear csv functions
 */
function eci_pearcsv_include()
{
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
	{
		ini_set('include_path',rtrim(ini_get('include_path'),';').';'.dirname(__FILE__).'/pear/');
	} 
	else
	{
		ini_set('include_path',rtrim(ini_get('include_path'),':').':'.dirname(__FILE__).'/pear/');
	}
	require_once 'File/CSV.php';
}	
?>