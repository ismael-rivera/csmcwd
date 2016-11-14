<?php
# Plugin Activation Installation Function - Calls Multiple Individual Functions
function eci_install_options()
{
	// first check for trace of free edition being installed and remove all options 
	if( get_option( 'eci_installpackage' ) == 'free' )
	{
		eci_deleteoptionarrays();
	}
	
	// save current version as the installed version
	global $ecicurrentversion;
	update_option('eci_installversion',$ecicurrentversion);
	update_option('eci_installpackage','paid');		
	update_option('eci_installdate',time());
	add_option('eci_activationdate',time());// used to store users firs time usage of ECI, avoid doing this in free edition	
	eci_install_paths();
	eci_install_projects();
	eci_install_designs();
	eci_install_settings();
	eci_install_questions();
	eci_install_speeds();
	eci_install_snippets();
	eci_install_status();
	eci_install_export();
	eci_install_autotokens();
}

# Deletes All Option Arrays
function eci_deleteoptionarrays()
{
	delete_option('eci_pat');
	delete_option('eci_pro');
	delete_option('eci_des');
	delete_option('eci_set');
	delete_option('eci_que');
	delete_option('eci_not');
	delete_option('eci_spe');
	delete_option('eci_deb');
	delete_option('eci_sni');
	delete_option('eci_sta');
	delete_option('eci_exp');
	delete_option('eci_tok');
}

# Settings Installation - general plugin settings, global to all projects
function eci_install_settings()
{
	$set = array();
	$set['arraydesc'] = 'General and global plugin options';

	// interface settings
	$set['aboutpanels'] = true;// hide or display about panels
	$set['updating'] = true;// hide or display updating features
	$set['scheduling'] = true;// hide or display scheduling
	$set['allinoneseo'] = true;// hide or display all in one seo	
	$set['posttypes'] = true;// hide or display post types configuration panel
	
	// global post settings
	$set['tagschars'] = 50;// tags string total characters length
	$set['tagsnumeric'] = true;// tags string total characters length
	$set['tagsexclude'] = 'what,where,who,when,how';// tags to exclude
	$set['excerptlimit'] = 150;// maximum wordpress excerpt size
		
	// encoding settings
	$set['titleencoding'] = 'None';// false or a type of encoding for post title
	$set['contentencoding'] = 'None';// false or a type of encoding for post content
	$set['categoryencoding'] = 'None';// false or a type of encoding for categories
	$set['permalinkencoding'] = 'None';// false or a type of encoding for categories

	// advanced settings
	$set['postupdating'] = 'No';// Global setting Yes or No to update posts as they are being viewed
	$set['querylimit'] = 1000;// limit to sql queries used in tools and admin actions
	$set['acceptabledrop'] = 100;// acceptable auto delete limit
	$set['createtest'] = 1;// number of posts to create in test
	$set['log'] = 'No';// log file Yes or No
	$set['allowduplicaterecords'] = 'No';// Yes or No	
	$set['allowduplicateposts'] = 'No';// Yes or No	
	$set['editpostsync'] = 'No';// Yes or No	- updates project table with Edit Post changes
	$set['voidrecords'] = 'No';// Yes or No - updates project table blackmarking a record

	// post date settings
	$set['rd_yearstart'] = 2009;
	$set['rd_monthstart'] = 11;
	$set['rd_daystart'] = 18;
	$set['rd_yearend'] = 2011;
	$set['rd_monthend'] = 10;
	$set['rd_dayend'] = 23;
	$set['incrementyearstart'] = 2008;
	$set['incrementmonthstart'] = 11;
	$set['incrementdaystart'] = 25;
	$set['incrementstart'] = 3600;
	$set['incrementend'] = 21600;		

	// developer settings
	$set['recordlastid'] = true;
	$set['selectescapedata'] = true;// apply escape to data before it is added to SELECT query string
	$set['insertescapedata'] = true;// apply escape to data before it is added to INSERT query string
	$set['displayarrays'] = true;// display data arrays in footer
	
	add_option('eci_set',$set);
}

# Installs Paths To CSV File Directories
function eci_install_paths()
{
	$pat = array();
	$pat['pluginfolder']['name'] = 'PluginFolder';
	$pat['pluginfolder']['path'] = ECIPATH;	
	$pat['wpcontent']['name'] = 'WPContent';
	$pat['wpcontent']['path'] = ECICSVFOLDER;
	$pat['default']['name'] = 'Default';
	$pat['default']['path'] = ECIPATH;			
	add_option('eci_pat',$pat);
}

# Projects Installation - holds project data
function eci_install_projects()
{
	$pro = array();
	$pro['arraydesc'] = 'Holds project progress data';
	add_option('eci_pro',$pro);
}

# Designs Installation - holds WYSIWYG designs
function eci_install_designs()
{	
	$des = array();
	$des['arraydesc'] = 'Holds wysiwyg created html';
	$des['Default']['id'] = time();
	$des['Default']['updated'] = time();
	$des['Default']['title'] = 'Remove: Please Visit Design Page';
	$des['Default']['content'] = 'Please Remove This Message: You have not visited the design page to complete your design. If you are using Easy Project then the plugin may need a template design added for your theme. Please contact WebTechGlobal.';
	$des['Default']['shortcodedesign'] = '';
	$des['Default']['seotitle'] = '';
	$des['Default']['seodescription'] = '';		
	add_option('eci_des',$des);
}

# Questions Installation - questions system is used to capture users needs
function eci_install_questions()
{
	$que = array();
	$que['arraydesc'] = 'Interface configuration questions';
	add_option('eci_que',$que);
}

# Event Speed Installation - settings that control project import etc
function eci_install_speeds()
{	
	$spe = array();
	$spe['arraydesc'] = 'Pre-configured operation variables which determine the rate of import and post creation';
	// fullspeed
	$spe['fullspeed']['label'] = 'Full Speed';
	$spe['fullspeed']['nextevent'] = time() + 3600;	
	$spe['fullspeed']['lastevent'] = time();
	$spe['fullspeed']['lastaction'] = 'Import';// Import,Update,Create
	$spe['fullspeed']['eventdelay'] = 3600;
	$spe['fullspeed']['create'] = 9999999;// number of posts/pages/ads to create per event
	$spe['fullspeed']['import'] = 9999999;// number of records to import per event
	$spe['fullspeed']['update'] = 9999999;// number of records to update per event
	$spe['fullspeed']['status'] = false;// use to stop all projects using the speed profile - false is paused
	$spe['fullspeed']['type'] = 'fullspeed';// determines interface requirements
	$spe['fullspeed']['filecheckdelay'] = 3600;// check for file datestamp change

	// events
	$spe['manualevents']['label'] = 'Manual Events';
	$spe['manualevents']['nextevent'] = time() + 3600;	
	$spe['manualevents']['lastevent'] = time();
	$spe['manualevents']['lastaction'] = 'Import';// Import,Update,Create
	$spe['manualevents']['eventdelay'] = 3600;// time to increase eventtime for reschedule
	$spe['manualevents']['create'] = 50;// number of posts/pages/ads to create per event
	$spe['manualevents']['import'] = 250;// number of records to import per event
	$spe['manualevents']['update'] = 100;// number of records to update per event
	$spe['manualevents']['status'] = false;// use to stop all projects using the speed profile - false is paused
	$spe['manualevents']['type'] = 'manualevents';// determines interface requirements
	$spe['manualevents']['filecheckdelay'] = 3600;// check for file datestamp change

		// scheduled
	$spe['scheduled']['label'] = 'Scheduled';
	$spe['scheduled']['nextevent'] = time() + 3600;
	$spe['scheduled']['lastevent'] = time();
	$spe['scheduled']['lastaction'] = 'Import';// Import,Update,Create
	$spe['scheduled']['eventdelay'] = 3600;// 1 hour delay by default
	$spe['scheduled']['create'] = 1;// number of posts/pages/ads to create per event
	$spe['scheduled']['import'] = 5;// number of records to import per event
	$spe['scheduled']['update'] = 5;// number of records to update per event
	$spe['scheduled']['status'] = false;// use to stop all projects using the speed profile - false is paused
	$spe['scheduled']['type'] = 'spreadout';// determines interface requirements
	$spe['scheduled']['filecheckdelay'] = 3600;// check for file datestamp change
	
	// spreadout (used to be named staggered)
	$spe['spreadout']['label'] = 'Spreadout';
	$spe['spreadout']['nextevent'] = time() + 3600;
	$spe['spreadout']['lastevent'] = time();
	$spe['spreadout']['lastaction'] = 'Import';// Import,Update,Create
	$spe['spreadout']['eventdelay'] = 3600;// 1 hour delay by default
	$spe['spreadout']['create'] = 1;// number of posts/pages/ads to create per event
	$spe['spreadout']['import'] = 5;// number of records to import per event
	$spe['spreadout']['update'] = 5;// number of records to update per event
	$spe['spreadout']['status'] = false;// use to stop all projects using the speed profile - false is paused
	$spe['spreadout']['type'] = 'spreadout';// determines interface requirements
	$spe['spreadout']['filecheckdelay'] = 3600;// check for file datestamp change
	
	// save the configuration array
	add_option('eci_spe',$spe);
}

# Snippets Installation - used to generate html for copying and pasting
function eci_install_snippets()
{
	$sni = array();
	$sni['arraydesc'] = 'HTML code for copying and pasting too WYSIWYG editor';
	$sni['imagebutton'] = '<a href="URL COLUMN" target="_blank"><img src="IMAGE COLUMN"/></a>';
	$sni['image'] = '<img src="IMAGE COLUMN TOKEN HERE" alt="ADD ALTERNATIVE TEXT OR TITLE COLUMN HERE" />';
	$sni['link'] = '<a href="URL COLUMN TOKEN HERE">Link Text</a>';

		// seo templates
	$sni['templates']['seo']['title'] = '+TITLE+';
	$sni['templates']['seo']['description'] = '+DESCRIPTION+';		
	$sni['templates']['seo']['keywords'] = '+KEYWORDS+';	
	
	// post title and content designs - these are uses as snippets only, copied to the designs array during processes
	$sni['templates']['post']['title'] = '+TITLE+';
	$sni['templates']['post']['content'] = '
	<a href="xLINKx" target="_blank"><img class="alignleft" src="xIMAGEx" alt="+TITLE+" /></a>+DESCRIPTION+&nbsp;
	<a title="+TITLE+" href="xLINKx" target="_blank"><img src="http://www.webtechglobal.co.uk/images/button/greenreadmore.jpg" alt="+TITLE+" /></a>';	

	// shopperpress template
	$sni['templates']['V.I.P ShopperPress Theme']['title'] = '+TITLE+';
	$sni['templates']['V.I.P ShopperPress Theme']['content'] = '
	+DESCRIPTION+&nbsp;
	<a title="+TITLE+" href="xLINKx" target="_blank"><img src="http://www.webtechglobal.co.uk/images/button/greenreadmore.jpg" alt="+TITLE+" /></a>';	
	
	// classipress template
	$sni['templates']['ClassiPress']['title'] = '+TITLE+';
	$sni['templates']['ClassiPress']['content'] = '
	+DESCRIPTION+&nbsp;
	<a title="+TITLE+" href="xLINKx" target="_blank"><img src="http://www.webtechglobal.co.uk/images/button/greenreadmore.jpg" alt="+TITLE+" /></a>';	

	// couponpress template
	$sni['templates']['CouponPress']['title'] = '+TITLE+';
	$sni['templates']['CouponPress']['content'] = '
	+DESCRIPTION+&nbsp;
	<a title="+TITLE+" href="xLINKx" target="_blank"><img src="http://www.webtechglobal.co.uk/images/button/greenreadmore.jpg" alt="+TITLE+" /></a>';	
		
	add_option('eci_sni',$sni);
}

function eci_install_status()
{
	$sta = array();
	$sta['memuse_create'] = 0;// memory used by the end of the last post create event
	add_option('eci_sta',$sta);
}

/**
 * Array holds settings for database export
 */
function eci_install_export()
{
	$exp = array();
	$exp['columns']['wp_posts']['post_title']['type'] = 'standard'; 
	add_option('eci_exp',$exp);
}


/**
 * Installs auto token array, used to match csv titles to the closest token in templates and other configuration values
 * This can be used to auto populate SEO custom fields, categorise and more
 */
function eci_install_autotokens()
{
	$tok = array();

	// title similarity - for automatically populating design with users tokens
	// check csv file column title in array, extract the token paired with it that exists in template
	
	// title
	$tok['similarity']['columns']['+title+'] = 'title';// example: title, is used to access config array
	$tok['similarity']['columns']['+titles+'] = 'title';
	$tok['similarity']['columns']['+posttitle+'] = 'title';	
	$tok['similarity']['columns']['+TITLE+'] = 'title';
	$tok['similarity']['columns']['+PRODUCT+'] = 'title';
	$tok['similarity']['columns']['+PRODUCTNAME+'] = 'title';
	$tok['similarity']['columns']['+product+'] = 'title';	
	$tok['similarity']['columns']['+productname+'] = 'title';	
	$tok['similarity']['columns']['+producttitle+'] = 'title';	
	$tok['similarity']['columns']['+PRODUCTTITLE+'] = 'title';	
	// title config - once we match a column and get title for example, we can then get other config
	$tok['similarity']['config']['title']['token'] = '+TITLE+';// token used in template designs
	$tok['similarity']['config']['title']['aioseotitle'] = '_aioseop_title';// All In One SEO Title
	$tok['similarity']['config']['title']['yoasttitle'] = '_yoast_wpseo_title';// Yoast SEO Title

	// description
	$tok['similarity']['columns']['+description+'] = 'description';
	$tok['similarity']['columns']['+desc+'] = 'description';
	$tok['similarity']['columns']['+longdesc+'] = 'description';
	$tok['similarity']['columns']['+fulldesc+'] = 'description';
	$tok['similarity']['columns']['+DESC+'] = 'description';
	$tok['similarity']['columns']['+DESCRIPTION+'] = 'description';
	$tok['similarity']['columns']['+itemdesc+'] = 'description';
	$tok['similarity']['columns']['+itemdescription+'] = 'description';
	// description config
	$tok['similarity']['config']['description']['token'] = '+DESCRIPTION+';// token used in template designs
	$tok['similarity']['config']['description']['aioseodesc'] = '_aioseop_description';// All In One SEO Description
	$tok['similarity']['config']['description']['yoastdesc'] = '_yoast_wpseo_metadesc';// Yoast SEO Description
	
	// linkurl
	$tok['similarity']['columns']['xlinkx'] = 'link';
	$tok['similarity']['columns']['xlinkonex'] = 'link';
	$tok['similarity']['columns']['xLINKx'] = 'link';
	$tok['similarity']['columns']['xurlx'] = 'link';
	$tok['similarity']['columns']['xURLx'] = 'link';
	$tok['similarity']['columns']['xaffiliatelinkx'] = 'link';
	$tok['similarity']['columns']['xaffiliateurlx'] = 'link';
	$tok['similarity']['columns']['xaffiliatepagex'] = 'link';
	$tok['similarity']['columns']['xpagex'] = 'link';
	// linkurl config
	$tok['similarity']['config']['link']['token'] = 'xLINKx';	
	
	// image
	$tok['similarity']['columns']['ximagex'] = 'image';
	$tok['similarity']['columns']['xIMAGEx'] = 'image';
	$tok['similarity']['columns']['ximgx'] = 'image';
	$tok['similarity']['columns']['xIMGx'] = 'image';
	$tok['similarity']['columns']['xphotox'] = 'image';
	$tok['similarity']['columns']['xPHOTOx'] = 'image';
	$tok['similarity']['columns']['xpicturex'] = 'image';
	$tok['similarity']['columns']['xPICTUREx'] = 'image';
	// image config
	$tok['similarity']['config']['image']['token'] = 'xIMAGEx';
	
	// keywords
	$tok['similarity']['columns']['+keywords+'] = 'keywords';
	$tok['similarity']['columns']['+keys+'] = 'keywords';
	$tok['similarity']['columns']['+keyw+'] = 'keywords';
	$tok['similarity']['columns']['+KEYS+'] = 'keywords';
	$tok['similarity']['columns']['+KEYWORDS+'] = 'keywords';
	$tok['similarity']['columns']['+KEYWORDSTRING+'] = 'keywords';
	// keywords config
	$tok['similarity']['config']['keywords']['token'] = '+KEYWORDS+';	
	$tok['similarity']['config']['keywords']['aioseokeywords'] = '_aioseop_keywords';// All In One SEO Description
	$tok['similarity']['config']['keywords']['wpseokeywords'] = '_aioseop_keywords';// Yoast SEO Keywords

	// category level one
	$tok['similarity']['columns']['+categoryone+'] = 'categoryone';	
	$tok['similarity']['columns']['+cat+'] = 'categoryone';	
	$tok['similarity']['columns']['+cat1+'] = 'categoryone';	
	$tok['similarity']['columns']['+catone+'] = 'categoryone';	
	$tok['similarity']['columns']['+CAT1+'] = 'categoryone';	
	$tok['similarity']['columns']['+CATONE+'] = 'categoryone';	
	$tok['similarity']['columns']['+CATEGORY+'] = 'categoryone';		
	$tok['similarity']['columns']['+CATEGORYONE+'] = 'categoryone';		
	$tok['similarity']['columns']['+parentcat+'] = 'categoryone';	
	$tok['similarity']['columns']['+parentcategory+'] = 'categoryone';		
	$tok['similarity']['columns']['+PARENTCATEGORY+'] = 'categoryone';	
	$tok['similarity']['columns']['+country+'] = 'categoryone';
	$tok['similarity']['columns']['+COUNTRY+'] = 'categoryone';		
	// category level one config
	$tok['similarity']['config']['categoryone']['token'] = '+CATEGORYONE+';// this token won't be used much but it may come in handy for shortcodes that display accordians etc
	$tok['similarity']['config']['categoryone']['use'] = true;// later will be in setting to switch off categories, true will apply the column as level one
	
	// category level two
	$tok['similarity']['columns']['+categorytwo+'] = 'categorytwo';	
	$tok['similarity']['columns']['+cat2+'] = 'categorytwo';	
	$tok['similarity']['columns']['+cattwo+'] = 'categorytwo';	
	$tok['similarity']['columns']['+CAT2+'] = 'categorytwo';	
	$tok['similarity']['columns']['+CATTWO+'] = 'categorytwo';		
	$tok['similarity']['columns']['+CATEGORYTWO+'] = 'categorytwo';		
	$tok['similarity']['columns']['+childcat1+'] = 'categorytwo';	
	$tok['similarity']['columns']['+childcatone+'] = 'categorytwo';	
	$tok['similarity']['columns']['+CHILDCATONE+'] = 'categorytwo';
	$tok['similarity']['columns']['+childcategory1+'] = 'categorytwo';
	$tok['similarity']['columns']['+CHILDCATEGORY1+'] = 'categorytwo';
	$tok['similarity']['columns']['+county+'] = 'categorytwo';
	$tok['similarity']['columns']['+COUNTY+'] = 'categorytwo';		
	$tok['similarity']['columns']['+STATE+'] = 'categorytwo';	
	$tok['similarity']['columns']['+state+'] = 'categorytwo';	
	// category level two config
	$tok['similarity']['config']['categorytwo']['token'] = '+CATEGORYTWO+';// this token won't be used much but it may come in handy for shortcodes that display accordians etc
	$tok['similarity']['config']['categorytwo']['use'] = true;// later will be in setting to switch off categories, true will apply the column as level one

	// category level three
	$tok['similarity']['columns']['+categorythree+'] = 'categorythree';	
	$tok['similarity']['columns']['+cat3+'] = 'categorythree';	
	$tok['similarity']['columns']['+catthree+'] = 'categorythree';	
	$tok['similarity']['columns']['+CAT3+'] = 'categorythree';	
	$tok['similarity']['columns']['+CATTHREE+'] = 'categorythree';		
	$tok['similarity']['columns']['+CATEGORYTHREE+'] = 'categorythree';		
	$tok['similarity']['columns']['+childcat2+'] = 'categorythree';	
	$tok['similarity']['columns']['+childcattwo+'] = 'categorythree';	
	$tok['similarity']['columns']['+CHILDCATTWO+'] = 'categorythree';
	$tok['similarity']['columns']['+childcategory2+'] = 'categorythree';
	$tok['similarity']['columns']['+CHILDCATEGORY2+'] = 'categorythree';
	$tok['similarity']['columns']['+county+'] = 'categorythree';
	$tok['similarity']['columns']['+COUNTY+'] = 'categorythree';		
	$tok['similarity']['columns']['+STATE+'] = 'categorythree';	
	$tok['similarity']['columns']['+state+'] = 'categorythree';	
	// category level two config
	$tok['similarity']['config']['categorythree']['token'] = '+CATEGORYTHREE+';// this token won't be used much but it may come in handy for shortcodes that display accordians etc
	$tok['similarity']['config']['categorythree']['use'] = true;// later will be in setting to switch off categories, true will apply the column as level one
				
	add_option('eci_tok',$tok);
}

?>