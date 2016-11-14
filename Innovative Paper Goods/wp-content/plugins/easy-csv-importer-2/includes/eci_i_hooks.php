<?php
function eci_webtechglobal_hooks()
{
	/*
	 * Developed by Ryan Bayne
	 * Site: www.webtechglobal.co.uk
	 * Package: Easy CSV Importer
	 * Free Edition: most hooked functions are not available in the free edition
	 */
}

/**
 * Easy CSV Importer Edit Post Sync For Updating Project Table With Manual Edits
 * When post data sync is activated,the plugin will apply changes done on the Edit Post page to the projects
 * database table. All records in the table will reflect what is in the posts.
 * @return NA
 * @global $wpdb,$post
 * @param NA
 */
function eci_editpostsync( $content )
{	
	// paid edition only
}
//add_action('edit_post', 'eci_editpostsync');
//add_filter('content_save_pre', 'eci_editpostsync');

/*
 * Register Widget One - basic widget
 */
function eci_registerwidget_one()
{
	// if user has activated widgets - include function files normally only included
	global $eciwidgets;
	if( $eciwidgets )
	{
		eci_loadallfunctions();
	}
	
	$widget_ops = array('classname' => 'eci_widget_one', 'description' => "Display a random item" );
	wp_register_sidebar_widget('eci_widget_one', 'ECI Random Item', 'eci_widget_one', $widget_ops);
}
add_action('plugins_loaded','eci_registerwidget_one');

# Updates Project Table - Black Marks A Record When User Manually Deletes Post
function eci_voidrecord( $postid )
{
	// paid edition only
}
//add_action('trashed_post','eci_voidrecord');

# triggers scheduled events - the function decides which speed is due,then which project,then action
//add_action('init', 'eci_eventtrigger');
function eci_eventtrigger()
{
	// paid edition only
}
?>