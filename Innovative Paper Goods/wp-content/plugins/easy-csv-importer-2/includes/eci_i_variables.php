<?php
// expected folder name - used for making up path too plugin files
if(!defined("ECIFOLDER")){define("ECIFOLDER","/easy-csv-importer-2/");}

// complete path too plugin files
if(!defined("ECIPATH")){define("ECIPATH",WP_PLUGIN_DIR.ECIFOLDER);}

// default csv storage folder path
if(!defined("ECICSVFOLDER")){define("ECICSVFOLDER",WP_CONTENT_DIR . "/ecifiles/");}

// plugin authorisation level (0 for testing but 10 for final launch)
if(!defined("ECIAUTHLEV")){define("ECIAUTHLEV","Administrator");}

// no save or update response
if(!defined("ECINOSAVE")){define("ECINOSAVE","Wordpress could not update the options table with your changes at this time. Pleasee try again then seek support if this continues to happen.");}

// define base url to easy csv importer blog 
if(!defined("ECIBLOG")){define("ECIBLOG","http://www.webtechglobal.co.uk/blog/wordpress/easy-csv-importer/");}

// define eci own maximum execution time currently minus 5 from server setting
if(!defined("ECIMAXEXE")){$m = ini_get("max_execution_time"); $m = $m - 5; define("ECIMAXEXE",$m);}
?>