<?php
function eci_webtechglobal_interface()
{
	/*
	 * Developed by Ryan Bayne
	 * Site: www.webtechglobal.co.uk
	 * Package: Easy CSV Importer
	 */
}

# Used On Categories Stage To Indicate The Users Current Setup
function eci_categoriesstatus()
{
	$pro = get_option( 'eci_pro' );
	$csv = get_option( 'eci_'.$pro['current'] );
	
	$status = '---- No Categories Setup';
	
	if( $pro )
	{
		// single category setup overrides all other settings
		if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] != 'NA' )
		{
			$status = '---- Single Categories Activated';
		}
		else
		{
			// display number of category sets saved
			if( isset( $csv['categories'] ) && !isset( $csv['singlecategory'] ) 
			|| isset( $csv['singlecategory'] ) && $csv['singlecategory']  == 'NA' )
			{			
				$i = 0;
				foreach( $csv['categories'] as $set=>$c )
				{	
					++$i;
				}
				
				$status = '---- Category Sets Saved: '.$i;
			}
		}
	}
	
	echo $status;
}

/**
 * Builds a table of checkboxes for selecting post types to be used in project or globally with plugin
 * @param base only $filename
 */
function eci_customposttypes_listcheck($filename,$csv)
{
	$post_types = get_post_types('','names');
	
	$table = '
	<form method="post" name="eci_singlecategory_form" action="">
		<table class="widefat post fixed">';
	
		foreach ($post_types as $post_type) 
		{
			// do not display post types that wont be used
			if( $post_type != "revision" || $post_type != "nav_menu_item" )
			{
				$checked = '';
				
				// get currently saved post types - loop through them, compare to current $post_type
				if(isset($csv['posttypes']))
				{
					// if it exists in our $csv then we show it as checked
					foreach($csv['posttypes'] as $pt)
					{
						if($post_type == $pt)
						{
							$checked = 'checked="checked"';
						}
					}			
				}
				
				// determine checked status
				$table .= '<tr>';
				$table .= '<td width="25"><input type="checkbox" name="posttype[]" value="'.$post_type.'" '.$checked.' /></td>';
				$table .= '<td>'.$post_type.'</td>';
				$table .= '</tr>';
			}
		}
	
	$table .= '
	</table><br />
	<input class="button-primary" type="submit" name="eci_posttypes_inuse_submit" value="Save" />
	</form>';
	
	echo $table;
}

/**
 * Used on Design page to process snippet requests that are stored in snippets folder
 */
function get_getsnippet()
{
	$snippet = 'No Snippet Found';
	$displaysnippet = false;
	
	if( isset( $_POST['eci_googleplusone_tall'] ) )
	{
		$snippet = 'g:plusone count="false"></g:plusone>';
		$displaysnippet = true;
	}
	
	if( isset( $_POST['eci_googleplusone_small_count'] ) )
	{
		$snippet = 'g:plusone size="small"></g:plusone>';
		$displaysnippet = true;
	}
	
	if( isset( $_POST['eci_googleplusone_medium_count'] ) )
	{
		$snippet = 'g:plusone size="medium"></g:plusone>';		
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googleplusone_large_count'] ) )
	{
		$snippet = 'g:plusone></g:plusone>';		
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googleplusone_small_nocount'] ) )
	{
		$snippet = 'g:plusone size="small" count="false"></g:plusone>';		
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googleplusone_medium_nocount'] ) )
	{
		$snippet = 'g:plusone size="medium" count="false"></g:plusone>';		
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googleplusone_large_nocount'] ) )
	{
		$snippet = 'g:plusone count="false"></g:plusone>';
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googlebuzz_button_withcount'] ) )
	{
		$snippet = '<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="normal-count"></a>
		<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>';	
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googlebuzz_button_nocount'] ) )
	{
		$snippet = '<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="normal-button"></a>
		<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>';			
		$displaysnippet = true;
	}	
	
	if( isset( $_POST['eci_googlebuzz_link'] ) )
	{
		$snippet = '<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="link"></a>
		<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>';			
		$displaysnippet = true;
	}	
	
	if( $displaysnippet == true )
	{
		eci_mes( __('Your Snippet'),'<form><textarea name="snippet" cols="75" rows="5">'.$snippet.'</textarea></form><br />' );
	}	
}

# Places Project Tokens Or Shortcodes Into Snippet For Pasting Into WYSIWYG Editor Designs
function eci_snippetgenerator( $snippet,$method )
{
	$sni = get_option('eci_sni');

	if( $method == 't' )
	{
		// column titles currently assumed but later upgrade will allow any column to apply
		$colurl = 'url';
		$colimage = 'image';
		
		// replace url
		$snippet = str_replace ( 'url' , 'x'.$colurl.'x', $sni[$snippet] );
		
		// replace image
		$snippet = str_replace ( 'image' , 'x'.$colimage.'x', $snippet );
	}
	elseif( $method == 's' )
	{
		// column titles currently assumed but later upgrade will allow any column to apply
		$colurl = 'url';
		$colimage = 'image';
		
		// replace url
		$snippet = str_replace ( 'url' , '[eci '.$colurl.']', $sni[$snippet] );
		
		// replace image
		$snippet = str_replace ( 'image' , '[eci '.$colimage.']', $snippet );
	}
	
	eci_mes( __('Your HTML Snippet'),'<form><textarea name="snippet" cols="75" rows="5">'.$snippet.'</textarea></form><br />' );
}

/**
 * Displays a help bubble with link to a page on the web
 * can be configured to point towards translated pages
 * @param unknown_type $url
 */
function eci_help_icon($domain,$postname,$languagecode)
{
	$fullurl = $domain . $languagecode . $postname;
	echo '
	<a href="'.$fullurl.'" target="_blank"> 
	<img src="http://www.webtechglobal.co.uk/wp-content/uploads/2011/05/questionmark_48.png" align="right"> 
	</a>';
}

function eci_csvcolumnmenu_usermetakeys( $csv, $objectid, $uk, $pro, $filename )
{
	// then we list all column titles from the profile with their ID in the form
	$cols = 0;

	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
			
	// add all csv file column names to the menu as items
	$item = '';
	foreach( $titlearray as $column )
	{	
		// check if the current column is setup for the passed custom field for selected 
		if( isset( $csv['usermetakeys'][$uk]['col'] ) && $csv['usermetakeys'][$uk]['col'] == $column )
		{ 
			$selected = ' selected="selected"'; 
		}
		else
		{ 
			$selected = '';
		}
		
		$item .= '<option value="'. $column .'" '. $selected .'>'. $column .'</option>';
		
		++$cols;
	}
	
		
	$menu = ' <select name="col_'. $objectid .'" size="1">
				<option value="NA">NA</option>';
	
	$menu .= $item;
	
	$menu .= '</select>';
	
	return $menu;
}	

function eci_displaytables_exporter()
{
	// query database for table names
	$result_tables = eci_get_tables();

	while ($row_table = mysql_fetch_row($result_tables)) 
	{
		echo '<div class="postbox closed">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3>'.$row_table[0].'</h3>
					<div class="inside">
						<p>'.eci_columnchecklist_display($row_table[0]).'</p>	
					</div>
				</div>';
	}
}       
            
function eci_exporter_tablecolumn_menu($table)
{
	$result_columns = eci_get_dbcolumns($table);

	$i = 0;
	
	$menu = '<select name="eci_exportsecondarycolumn_'.$table.'" size="1">';

		$menu .= '<option value="NA">Not Required</option>';
					
		while ($row_column = mysql_fetch_row($result_columns)) 
		{				
			$menu .= '<option value="'.$row_column[0].'">'.$row_column[0].'</option>'; 
		}      

	$menu .= '</select></label>';
	return $menu;				
}

function eci_columnchecklist_display($table)
{
	global $wpdb;

	$rs = mysql_query("SHOW COLUMNS FROM " . $table);
	
	$i = 0;

	$t = '<table class="widefat post fixed">';

	$t .= '
	<tr class="first">
		<td width="55">Normal</td>		
		<td width="55">Split</td>
		<td width="250">Column Name (split key)</td>
		<td>Secondary Column (split value)</td>
	</tr>';
				
	if( $rs )
	{
		while ($row = mysql_fetch_array($rs)) 
		{
			$t .= '<tr>
				<td><input type="checkbox" name="standard_'.$table.'_'.$row[0].'" value="'.$row[0].'" /></td>
				<td><input type="checkbox" name="split_'.$table.'_'.$row[0].'" value="'.$row[0].'" /></td>
				<td><a href="#">'.$row[0].'</a></td>
				<td><a href="#">'.eci_exporter_tablecolumn_menu($table).'</a></td>
			</tr>';
			++$i;
		}
	}
	
	$t .= '</table>';
	return $t;
}
		
		
function eci_pagetitle($title,$filename)
{
	echo '<h2>'.$title.' (free edition) - Current Project:'.$filename.'</h2>';
}

/**
 * Displays a paragraphed messaed with a h4 title,mainly for sure inside panels
 * @param unknown_type $title
 * @param unknown_type $message
 */
function eci_not($title,$message){echo '<p><h4>'.$title.'</h4>'.$message.'</p>';}

/**
 * Displays file size formatted to suit size and ensures file exists before using formatting function
 * @uses eci_csvfileexists
 * @uses eci_formatfilesize
 * @param unknown_type $filepath
 * @param unknown_type $pro
 */
function eci_displayfilesize($filepath,$pro)
{
   $exists = eci_csvfileexists(basename($filepath), $pro);
                                        	
   if($exists == true)
   {
      echo eci_formatfilesize(filesize($filepath));
   }
   else
   {
      echo 0;
   }
}
                                        
/**
 * Easy CSV Importer - lists default values for custom fields within text fields
 * @param $cf - custom field key
 * @param $objectid - used to make form object and in processing
 */
function eci_defaultvalue_customfields( $csv, $objectid, $cf )
{
	if( isset( $csv['customfields'][$cf]['def'] ) )
	{ 
		$default = $csv['customfields'][$cf]['def']; 
	}
	else
	{ 
		$default = '';
	}
		
	return '<input name="def_'.$objectid.'" type="text" value="Paid Edition Only" size="40" maxlength="40" readonly ><br />';
}

# Menu For Selecting Optional Data Effects To Custom Field Values
function eci_datafunctions_customfields( $csv, $objectid, $cf )
{
    // go through each menu item and check if is the one saved
	$menu = '
	<select name="fun_'. $objectid .'" size="1">
		<option value="NA">NA</option>';

			$menu .= '<option value="NA">Keyword Generator (paid edition only)</option>';
			$menu .= '<option value="NA">ClassiPress Images (paid edition only)</option>';
			$menu .= '<option value="NA">ShopperPress Images (paid edition only)</option>';
		
	
	$menu .= '</select>';
	
	return $menu;
}

/**
 * Returns menu with special data function options for user meta key configuration form
 * @param integer or string $objectid
 */
function eci_datafunctions_usermetakeys( $csv,$ukcount,$ukkey )
{
    // first get csv profile
	$menu = '<select name="fun_'. $ukcount .'" size="1">';

		if( isset( $csv['usermetakeys'][$ukkey]['fun'] ) && $csv['usermetakeys'][$ukkey]['fun'] == 'keywordgenerator' )
		{ 
			$menu .= '<option value="NA" selected="selected">Not Applicable</option>';
		}
		else
		{
			$menu .= '<option value="NA">Not Applicable</option>';
		}
		
	$menu .= '</select>';
	
	return $menu;
}	

# Returns Menu For Selecting CSV Columns On Custom Fields Form
function eci_csvcolumnmenu_customfields( $csv, $objectid, $cf, $pro, $filename )
{
	// then we list all column titles from the profile with their ID in the form
	$cols = 0;

	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
			
	// add all csv file column names to the menu as items
	$item = '';
	foreach( $titlearray as $column )
	{	
		// check if the current column is setup for the passed custom field for selected 
		if( isset( $csv['customfields'][$cf]['col'] ) && $csv['customfields'][$cf]['col'] == $column )
		{ 
			$selected = ' selected="selected"'; 
		}
		else
		{ 
			$selected = '';
		}
		
		$item .= '<option value="'. $column .'" '. $selected .'>'. $column .'</option>';
		
		++$cols;
	}
	
		
	$menu = ' <select name="col_'. $objectid .'" size="1">
				<option value="NA">NA</option>
				<option value="NA">Paid Edition Only £49.99</option>';
	
	$menu .= $item;
	
	$menu .= '</select>';
	
	return $menu;
}	

/**
 * Easy CSV Importer - displays menu of csv file columns for selecting default content data within project table
 * @param unknown_type $filename
 * @param unknown_type $objectid
 */
function eci_csvcolumnmenu_syncontent($filename,$pro)
{
	$csv = get_option('eci_'.$filename);

	$titlearray = eci_gettitlearray($csv,$pro,$filename);
		
	echo '<select name="eci_synccontent_menu" size="1">';
	echo '<option value="NA">Not Required</option>';	
	echo '<option value="eciconsync">Default</option>';
	$i = 0;
	foreach( $titlearray as $column )
	{		
		$sqlcolumn = eci_cleansqlcolumnname($column);
		echo'<option value="'. $sqlcolumn .'">'. $column .'</option>';
		++$i;
	}
	echo '</select>';
}	

/**
 * Easy CSV Importer - displays a menu showing the currently selected csv column holding the unique values
 * @param unknown_type $filename
 */
function eci_csvcolumnmenu_valdesign( $filename, $csv, $pro )
{	
	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
	echo '<select name="eci_valuedesigncolumn" size="1">';
	echo '<option value="NA">Not Required</option>';
	echo '<option value="NA">Paid Edition Is Upgraded Weekly</option>';
	$i = 0;
	foreach( $titlearray as $column )
	{
		if( isset( $csv['valdesigncolumn'] ) && $csv['valdesigncolumn'] == $column )
		{ 
			$selected = ' selected="selected"'; 
		}
		else
		{ 
			$selected = '';
		}
		
		echo'<option value="'. $column .'" '.$selected.'>'. $column .'</option>';
		++$i;
	}
	echo '</select>';
}	

/**
 * Easy CSV Importer - menu of designs, will display currently selected design for giving unique value if it has been set
 * @param $id an object id incremented for processing
 * @param $csv profile of file
 * @param $uniqueval a single value
 */
function eci_valdesign_menu( $id,$csv,$uniqueval )
{
	$design = get_option('eci_des');
	
	$sel = '';
	
	// apply id to object
	$menu = '<select name="eci_design'.$id.'" size="1"><option value="main">Main Project Design</option>';
	$i = 0;
	foreach( $design as $desname=>$d )
	{ 
		if( $desname != 'arraydesc' )
		{
			// apply selected value to current save
			$sel = '';		
			
			if( isset( $csv['valdesign'][$uniqueval] ) && $csv['valdesign'][$uniqueval] == $desname ) 
			{					
				$sel = 'selected="selected"';
			}
			
			$menu .= '<option value="'.$desname.'" '.$sel.'>'.$desname.'</option>'; 
		}
		++$i;
	}      
	
	$menu .= '</select>';
	return $menu;
}

/**
 * Easy CSV Importer - lists unique values from a selected column and menus for selecting designs
 * @param unknown_type $csv
 * @global $wpdb
 */
function eci_listvaluedesigns($filename)
{	
	$csv = get_option('eci_'.$filename);
	
	// get column that holds the specials values
	if(!isset($csv['valdesigncolumn']))
	{
		echo '<p><strong>';
		_e('No column has been selected');
		echo '</strong></p>';
	}
	elseif(isset($csv['valdesigncolumn']))
	{
		// prepare the column title for sql query use
		$column = eci_cleansqlcolumnname($csv['valdesigncolumn']);		
		
		// query project table for unique values - compare it to existing in array
		global $wpdb;	
		
		// execute sql SELECT query to get records
		$records = $wpdb->get_results( 'SELECT DISTINCT '.$column.' FROM '. $csv['sql']['tablename'] .' WHERE '.$column.' IS NOT NULL',ARRAY_A );

		if( !$records )
		{
			echo '<p><strong>';
			_e('No Records, Your Project Table Must Have Records');
			echo '</strong></p>';
		}
		else
		{
			echo '<table class="widefat post fixed"><tr><td width="200"><strong>';
			_e('Unique Values');
			echo '</strong></td><td><strong>';
			_e('Paired Design');
			echo '</strong></td></tr>';
						
			// begin looping through all records
			$i = 0;			
			foreach( $records as $rec )
			{			
				// get this unique data value
				//$uniqueval = $rec->$rec[$csv['valdesigncolumn']];
				$uniqueval = $rec[$column];
				
				//                       object unique id           put selected column data value as value
				echo '<tr><td><input name="val'.$i.'" type="text" value="'.$uniqueval.'" size="50" maxlength="250" readonly="true" /></td><td>'.eci_valdesign_menu( $i,$csv,$uniqueval ).'</td></tr>';
				++$i;					
			}
			
			// include number of unique values for the processing loop
			echo '<input name="valcount" type="hidden" value="'.$i.'" />';				
		}
				
		echo '</table>';
	}
}

/**
 * Prints CSV File Contents Too Screen As A Test
 * @param filpath,settings,files separator,files quote, table row limit
 */
function eci_printcsv( $path,$set,$separator,$quote,$limit )
{		
	if ( !file_exists( $path ) ) 
	{
		eci_mes(__('CSV File Not Found'),__('The plugin could not locate the selected csv file. Here is the path used: ').$path );
	}
	else
	{	
		_e('<h2>CSV File View and Test</h2>');
		echo '<strong>You Are Viewing: '.$path.'</strong><br /><br />';
		_e('<strong>Please Scroll Down To Bottom Of Table For More Options</strong>');

		eci_pearcsv_include();
	
		// csv file row counter
		$rows = 0;
	
		// use pear to read csv file
		$conf = File_CSV::discoverFormat( $path );
		
		// apply seperator
		$conf['sep'] = $separator;	
		
		// apply quote
		$conf['quote'] = $quote;
		
		$table = '';
		
		// build header of table by looping through records but using the first row only
		$con = $conf;
		while ( ( $readone = File_CSV::read( $path,$con ) ) ) 
		{				
			// start table header
			$table .= '<table class="widefat post fixed">
			<tr>';
			
			for ( $i = 0; $i < $con['fields']; $i++ )
			{
				$table .= '<td>'.$readone[$i].'</td>';
			}
			
			// end table header
			$table .= '</tr>';			
						
			break;
		}
		
		$entry = 1;
		
		// loop through records until speed profiles limit is reached then do exit
		$con = $conf;

		while ( ( $readtwo = File_CSV::read( $path,$con ) ) ) 
		{
			$table .=  '<tr>';

			for ( $d = 0; $d < $con['fields']; $d++ )
			{
				$table .= '<td>'.$readtwo[$d].'</td>';
			}

			$table .=  '</tr>';
			
			++$entry;
			
			++$rows;
			
			// stop looping when we hit the limit
			if( $limit == $rows )
			{
				break;
			}
		}
		
		$table .=  '</table>';
		
		echo $table;
	}
}

# Lists All Current Projects And Speed Profile Menus For Pairing
function eci_speedprofilepairinglist( $pro )
{
	// list existing projecta each one with their own post box
	if( isset( $pro ) && $pro != '' )
	{
		echo '<table class="widefat post fixed">';

		echo '<tr>';
		echo '<td>Project</td>';
		echo '<td>';
		_e('Speed Profile');
		echo '</td>';
		echo '</tr>';
		
		$prono = 0;
		
		foreach( $pro as $key=>$item )
		{
			if( $key != 'arraydesc' && $key != 'current' && $key != 'records' )
			{
				// do not display child $pro of a multi file project
				if( isset( $pro[$key]['multifilelev'] ) && $pro[$key]['multifilelev'] == 'child' )
				{
					// nothing required
				}
				else
				{
					echo '<tr>';
					
					echo '<td>';
					echo '<input name="eci_project'.$prono.'" size="35" type="text" value="'.$key.'" readonly="readonly" />';
					echo '</td>';
					
					echo '<td>';
					eci_speedprofilemenu( $prono,$pro,$key );
					echo '</td>';
					
					echo '</tr>';
					
					++$prono;
				}
			}// end is
		}// end of loop each $pro
		
		echo '<input name="eci_projectcount" type="hidden" value="'.$prono.'" />';
		echo '</table>';
	}// end of is set $pro
	else
	{
		echo '<strong>';
		_e('No Projects Found, Please Create A Project On Start Page');
		echo '</strong><br />';
	}
}

function eci_speedprofilemenu( $objectid,$pro,$filename )
{
	$spe = get_option('eci_spe');
	
	if( $spe )
	{		
		echo '<select name="eci_speedprofile'.$objectid.'">';
		echo '<option value="NA">';
		_e('Not Set');
		echo '</option>';
		echo '<option value="NA">';
		_e('Feature not available in free edition');
		echo '</option>';
		
		foreach( $spe as $k=>$item )
		{
			if( $k != 'arraydesc' )
			{   	
				// set
				$multifile = false;
				if( isset( $pro[ $filename ]['filesettype'] ) && $pro[ $filename ]['filesettype'] == 'multifile' )
				{
					$multifile = true;
				}
				
				$sel = '';	   	
				if( isset( $pro[ $filename ]['speed'] ) && $pro[ $filename ]['speed'] == $k )
				{
					$sel = 'selected="selected"';
				}
				
				// do not allow spreadout speed profiles to be offered if this is a multifile project
				var_dump($k);
				
				if( !$multifile || $multifile && $k != 'spreadout'  )
				{
					echo '<option value="fullspeed" '.$sel.'>'.$item['label'].'</option>';
				}
				else
				{
					echo '<option value="fullspeed">Spreadout (currently disabled for multifile projects)</option>';
				}
			}
		}
		
		echo '</select>';		
	}
	else
	{
		_e('No Speed Profiles Found');
	}
}

# Warns User About The Use Of Selected Plugins While Using This Plugin - Called From Plugins Header Function
function eci_installedplugins()
{
	$activeplugins = get_option('active_plugins');// changed from get_settings by Ryan Bayne on 14th April 2011
	
	if( $activeplugins )
	{
		foreach( $activeplugins as $key=>$plugin )
		{
			// is this a twitter plugin
			if( stristr ( $plugin, 'twitter' ) || stristr ( $plugin, 'tweet' ) )
			{
				eci_err( __('Possible Twitter Plugin Active'),__('Easy CSV Importer has detected that you may be using a Twitter plugin. It is recommended that you disable all 
						plugins that reacte to post or update events in your blog, unless the rate of those events will generate an acceptable level of tweets.') );
			}
			   
			// is this a sitemap plugin
			if( stristr ( $plugin, 'sitemap' ) || stristr ( $plugin, 'sitemap' ) )
			{
				eci_err( __('Possible Sitemap Plugin In Use'),__('Easy CSV Importer has detected that you may be using a Sitemap plugin. Please ensure it does not automatically
						rebuild the sitemap everytime a post is created or updated unless the rate of events is acceptable.') );
			}			
		}
	}
}

/**
 * Form menu object holding sum modifers
 * @param intiger $id this is used to help identify the part of the sum it applys to
 */
function eci_numericmodifiers( $id, $filename )
{
	$csv = get_option( 'eci_'.$filename );	
	
	$select_multiply = '';
	$select_subtract = '';
	$select_add = '';
	$select_divide = '';
	
	if(isset($csv['conditions']['numericmod']['symbol_1']))
	{
		if($csv['conditions']['numericmod']['symbol_1'] == 'multiply'){$select_multiply = 'selected="selected"';}
		elseif($csv['conditions']['numericmod']['symbol_1'] == 'subtract'){$select_subtract = 'selected="selected"';}
		elseif($csv['conditions']['numericmod']['symbol_1'] == 'add'){$select_add = 'selected="selected"';}
		elseif($csv['conditions']['numericmod']['symbol_1'] == 'divide'){$select_divide = 'selected="selected"';}
	}
	
	$menu = '<select name="eci_mathssymbol_'.$id.'" size="1">';
	$menu .= '<option value="NA" '.$select_multiply.'>None Selected</option>';	
	$menu .= '<option value="multiply" '.$select_multiply.'>Multiply ( x )</option>';
	$menu .= '<option value="subtract" '.$select_subtract.'>Subtract ( - )</option>';
	$menu .= '<option value="add" '.$select_add.'>Add ( + )</option>';
	$menu .= '<option value="divide" '.$select_divide.'>Divide ( / )</option>';
	$menu .= '</select>';	
	echo $menu;
}

/** 
 * Create form menu object with csv file names
 * This is the latest function of its kind, it is flexible enough to begin replacing other 
 * similiar functions.
 * @param string $filename
 * @param integer $objectid
 * @param string $form
 */
function eci_csvcolumnmenu_standard( $filename, $objectid, $form, $pro )
{
	$csv = get_option( 'eci_'.$filename );

	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
	echo '<select name="eci_columnid_'. $objectid .'" size="1">';
	echo '<option value="NA">';
	_e('Not Required');
	echo '</option>';
	echo '<option value="NA">';
	_e('Buy ECI here www.wtg.co/eci');
	echo '</option>';
		
	$i = 0;
	foreach( $titlearray as $column )
	{		
		$selected = '';
		
		// we use the $form value to determine what saved value we need to use to determine select
		if( $form == 'eci_uniquekey_form' )// unique key form on updating panel
		{
			if( isset( $csv['updating']['key'.$objectid.''] ) && $csv['updating']['key'.$objectid.''] == $column )
			{ 
				$selected = ' selected="selected"'; 
			}			
		}
		elseif( $form == 'eci_numericmod_form' )// numeric modification form on conditions panel
		{
			if( isset( $csv['conditions']['numericmod']['columnid_1'] ) && $csv['conditions']['numericmod']['columnid_1'] == $column )
			{ 
				$selected = ' selected="selected"'; 
			}						
		}
		
		echo'<option value="'. $column .'" '.$selected.'>'. $column .'</option>';
		++$i;
	}
	echo '</select>';
}	

# Builds Form Menu Holding CSV File Columns - No Conditions Unlike The Other Menu Building Functions
function eci_csvcolumnmenu_updatekey( $filename, $objectid,$pro )
{
	$csv = get_option( 'eci_'.$filename );

	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
	echo '<select name="eci_columnid_'. $objectid .'" size="1">';
	echo '<option value="NA">';
	_e('Not Required');
	echo '</option>';
	
	$i = 0;
	foreach( $titlearray as $column )
	{
		if( isset( $csv['updating'] ) && isset( $csv['updating']['key'.$objectid.''] )
		&& $csv['updating']['key'.$objectid.''] == $column )
		{ 
			$selected = ' selected="selected"'; 
		}
		else
		{ 
			$selected = '';
		}
		
		echo'<option value="'. $column .'" '.$selected.'>'. $column .'</option>';++$i;
	}
	echo '</select>';
}	

# Builds Form Menu Holding CSV File Columns - No Conditions Unlike The Other Menu Building Functions
# Pages using this need to change to the new menu that allows passing of the currently saved value
function eci_csvcolumnmenu( $filename, $objectid, $csv, $pro )
{
	echo '<select name="eci_columnid_'. $objectid .'" size="1">';
	echo '<option value="NA">';
	_e('Not Required');
	echo '</option>';
	
	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
	$i = 0;
	foreach( $titlearray as $column )
	{		
		echo'<option value="'. $column .'">'. $column .'</option>';++$i;
	}
	echo '</select>';
}	
		
# Builds A Single Menu Form Object With Its Name Being Part Of Arguments Giving
function eci_csvcolumnmenu_specialfunctions( $filename, $objectid, $pro )
{
    // first get csv profile
	$csv = get_option( 'eci_' . $filename );

	// then we list all column titles from the profile with their ID in the form
	echo '<select name="eci_spec_'. $objectid .'" size="1">';
		echo '<option value="NA">';
		_e('Not Required In My Project');
		echo '</option>';
		
		$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
		// add all csv file column names to the menu as items
		foreach( $titlearray as $column )
		{	
			if( isset( $csv['specials']['state'][$objectid.'_col'] ) 
			&& $csv['specials']['state'][$objectid.'_col'] == 'On' 
			&& isset( $csv['specials']['col'][$objectid.'_col'] )
			&& $csv['specials']['col'][$objectid.'_col'] == $column )
			{ 
				$selected = ' selected'; 
			}
			else
			{ 
				$selected = '';
			}
			
			echo '<option value="'. $column .'" '. $selected .'>'. $column .'</option>';
		}
	
	echo '</select>';
}	

# Displays A Table Holding Projected Dates Based On Current Incremented Date Settings
function eci_projecteddates_incremental( $set )
{
	$rowswanted = 10;
	$rowsadded = 0;
	
	$table = '<table class="widefat post fixed">		
	<tr>
		<td width="30"></strong></td>
		<td><strong>'. __('Standard Date') .'</strong></td>
		<td><strong>'. __('GMT Date') .'</strong></td>
		<td><strong>'. __('Incremented Seconds') .'</strong></td>
	</tr>';
	
	$currentinctime = time();
	
	for ($rowsadded = 1; $rowsadded <= $rowswanted; $rowsadded++) 
	{		
		$date1 = '10th';
		$date2 = '10th';
		$date3 = '10th';

		$increment = rand( $set['incrementstart'], $set['incrementend'] );
		$currentinctime = $currentinctime + $increment;
		$my_post['post_date'] = date("Y-m-d H:i:s", $currentinctime);	
		$my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $currentinctime);
		
		$table .= '
		<tr>
			<td width="30">'.$rowsadded.'</strong></td>
			<td>'.date("Y-m-d H:i:s", $currentinctime).'</td>
			<td>'.gmdate("Y-m-d H:i:s", $currentinctime).'</td>
			<td>'.$increment.'</td>
		</tr>';		
	}		
	
	$table .= '</table>';
	return $table;
}
                    
# Displays A List Of Tokens For Use In WYSIWYG Editor - Based On Column Titles
function eci_displaytokenlist()
{
	global $wpdb;

	// include PEAR CSV
	eci_pearcsv_include();
	
	$eci = get_option('eci_set');
	$pro = get_option( 'eci_pro' );
	$csv = get_option( 'eci_'.$pro['current'] );
		
	// if a multifile project we loop through a different array else we retrieve the standard column title array
	if( isset( $pro[$pro['current']]['filesettype'] ) && $pro[$pro['current']]['filesettype'] == 'multifile' )
	{
		$titlesarray = $csv['format']['titlesmulti'];
		$symbols = 'tokensymbolsmulti';
	}
	else
	{
		$titlesarray = $csv['format']['titles'];
		$symbols = 'tokensymbols';
	}
	
	// loop through the column titles array
	if( isset( $titlesarray ) )
	{
		$i = 0;
			
		foreach( $titlesarray as $title )
		{		
			// tokens are being printed without symbols for occasional user
			// lets apply a default if symbol not set, if it is set then the issue is here but I cannot see how
			// 
			if(!isset($csv['format'][$symbols][$i]))
			{
				$sym = '+';
			}
			else 
			{
				$sym = $csv['format'][$symbols][$i];
			}
			
			echo '  ' . $sym . $title . $sym . ' <br /><br />'; 
			++$i;
		}
	}
	else
	{
		echo eci_not('No Titles Stored', 'It appears there are no csv column titles stored for your project. Please
		use the Test tool on the Start page with your csv file and ensure it is not caused by your file itself.');
	}
}

function eci_displayshortcodelist( $type )
{
	global $wpdb;

	// include PEAR CSV
	eci_pearcsv_include();
	
	$eci = get_option('eci_set');
	$pro = get_option( 'eci_pro' );
	$csv = get_option( 'eci_'.$pro['current'] );
	
	$i = 0;

	// if a multifile project we loop through a different array else we retrieve the standard column title array
	if( isset( $pro[$pro['current']]['filesettype'] ) && $pro[$pro['current']]['filesettype'] == 'multifile' )
	{
		$titlesarray = $csv['format']['titlesmulti'];
	}
	else
	{
		$titlesarray = $csv['format']['titles'];
	}
	
	foreach( $titlesarray as $title )
	{ 
		if( $type == 'standard' )
		{
			echo '[ecicol column="' . $title . '"]<br /><br />'; 
		}
		elseif( $type == 'label' )
		{
			echo '[ecilab column="'.$title.'" label="Your Label: " nullaction="delete/swap" nullswap="tbc"]<br /><br />';			
		}
		
		++$i;
	}
}
# Creates Menu Holding Encoding Options
function eci_encodingmenu( $set,$c,$part )
{
	$s = 'selected="selected"';?>
    <select name="eci_encoding_<?php echo $part;?>" size="s">
    	<option value="None" <?php if( $c == 'None' ){echo $s;}?>>None</option>
    	<option value="UTF8Standard" <?php if( $c == 'UTF8Standard' ){echo $s;}?>>UTF-8 Standard Function</option>
    	<option value="UTF8Full" <?php if( $c == 'UTF8Full' ){echo $s;}?>>UTF-8 Full (extra processing)</option>
    </select>
	<?php 
}

/**
 * Attempts to establish a data set profile, a specific file or advertiser (affiliate networks)
 * We can use this to make the plugin adapt to different types of data
 * @param string $returntype table or normal
 * @param filepath $filepath
 */
function eci_get_knowndataset($returntype,$filepath)
{
	// include PEAR CSV
	eci_pearcsv_include();

	// check csv files column titles against profiles to find a close match
	$datasetprofile = 'Unknown';
	
	// if $returntype == table we return a table row else return the value only
	if($returntype == 'table')
	{
		return '<td>'.$datasetprofile.'</td>';
	}
	elseif($returntype == 'normal')
	{
		return $datasetprofile;
	}	
}

/**
 * Returns a data provider abbreviation such as affiliate site (CJ,TD) if a match can be made
 * @param string $returntype table or normal
 * @param filepath $filepath
 */

function eci_get_knowndatasource($returntype,$filepath)
{
	// include PEAR CSV
	eci_pearcsv_include();
	
	// check csv files column titles against profiles to find a close match
	$datasourceprofile = 'Unknown';
	
	// set each known profiles evidence of match up counter
	$tradedoubler = 0;
	$commissionjunction = 0;
	$googleaffiliatenetwork = 0;
	
	// loop through csv files columns
	// use pear to get file configuration
	$conf = File_CSV::discoverFormat( $filepath );
	$fields = File_CSV::read( $filepath, $conf );

	// save column titles	
	$count = 0;
	foreach( $fields as $title )
	{
		// check for tradedoubler matchup evidence
		if( $title == 'TDProductId' || $title == 'TDCategoryID' || $title == 'TDCategoryName' )
		{
			++$tradedoubler;
		}
		
		// check for commission junction matchup evidence
		if( $title == 'commissionjunctiontobecomplete' ) 
		{
			++$commissionjunction;
		}
		
		// check for google affiliate network matchup evidence
		if( $title == 'googleaffnettobecomplete' )
		{
			++$googleaffiliatenetwork;
		}
	}
	
	// decide what data source profile should be applied to file
	if( $tradedoubler > 0 )
	{
		$datasourceprofile = 'TD';
	}
	elseif( $commissionjunction > 0 )
	{
		$datasourceprofile = 'CJ';
	}
	elseif( $googleaffiliatenetwork > 0 )
	{
		$datasourceprofile = 'GAN';
	}
		
	// if $returntype == table we return a table row else return the value only
	if($returntype == 'table')
	{
		return '<td>'.$datasourceprofile.'</td>';
	}
	elseif($returntype == 'normal')
	{
		return $datasourceprofile;
	}
}

/**
 * Lists un-used data sources with either radio buttons or check boxes depending on $type
 * @param array $set plugin settings
 * @param string $filesettype singlefile,multifile
 */
function eci_csvfilelist_newproject( $set,$filesettype,$assist )
{
	$pro = get_option( 'eci_pro' );
	
	$available = 0;
	
	// start tab
	echo '<table class="widefat post fixed">';
	
	// record paths already checked should we have duplicate entries, it avoids duplicates being displayed
	$pathschecked = array();
	
	if( $pat = get_option('eci_pat') )
	{?>

		<tr class="first">
			<td width="22"></td>
			<td width="200"><strong>File</strong></td>
			<td width="90"><strong>Rows</strong></td>
			<?php 
			// if $assist = easy we will display the files known data source
			if($assist == 'easy')
			{
				echo '<td width="150"><strong>Data Source</strong></td>';
				echo '<td width="150"><strong>Data Set</strong></td>';
			}
			?>
			<td><strong>Size</strong></td>
		</tr>
		
		<?php 		
		foreach( $pat as $p )
		{
			// open each directory
			@$csvfiles_diropen = opendir( $p['path'] );
						
			if( !$csvfiles_diropen )
			{?>	
				<tr>
					<td><input type="radio" name="NA" value="NA" disabled="disabled" /></td>
					<td>No permissions or does not exist: "<?php echo $p['path'];?>"</td>
					<td></td>
				</tr>
			<?php 
			}
			elseif( !in_array($p['path'],$pathschecked))
			{
				// add path to $pathschecked array
				$pathschecked[] = $p['path'];
				
				// loop through this directories csv files
				while( false != ( $oldfilename = readdir( $csvfiles_diropen ) ) )
				{
					// do not show file if already being used in project
					if( !isset( $pro[$oldfilename] ) )
					{
						if( ($oldfilename != ".") and ($oldfilename != "..") )
						{
							$fileChunks = explode(".", $oldfilename);
							  
							// ensure file extension is csv
							if( isset( $fileChunks[1] ) && $fileChunks[1] == 'csv')
							{
								// preset $cleanedfile variable to be the current value
								$newfilename = $oldfilename;
			
								// clean the filename
								$newfilename = eci_cleanfilename( $oldfilename );			
			
								// correct slashes to prevent error on the rename function
								$p1 = '"\"'; $p2 = '"/"';
								$oldpath = str_replace ( $p1, $p2, $p['path'].$oldfilename );// remove slashes
								$newpath = str_replace ( $p1, $p2, $p['path'].$newfilename );// remove slashes			
			
								// if returned clean file name is different from original then apply a file rename
								if( $oldfilename != $newfilename )
								{
									// store old file name for auto checking for a new file being placed
									// precleanfilename should only be set if a change happens, later its existance
									// will effect procedures
									$csv['precleanfilename'] = $oldfilename;
									$csv['precleanfilepath'] = $p['path'].$oldfilename;
									update_option( 'eci_'.$newfilename, $csv );
									
									// does new planned path already exist
									if ( file_exists( $newpath ) ) 
									{
										$newpathexists = true;
										$newpathdeleted = false;
										
										// delete existing file on our new planned path
										if( unlink( $newpath ) )
										{
											$newpathdeleted = true;
										}
										else
										{
											$newpathdeleted = false;
										}
									}
									else
									{
										$newpathexists = false;
									}
									
									// either apply rename or report failure
									if( $newpathexists && $newpathdeleted || !$newpathexists )
									{
										if( rename( $oldpath, $newpath ) )
										{
											eci_log( 'Files',
											'File '.$oldfilename.' renamed to '.$newfilename.'',
											'File '.$oldfilename.' renamed to '.$newfilename.'',
											'Operation',$set,'High',__LINE__,__FILE__,__FUNCTION__ );
										}
										else
										{
											eci_log( 'Files',
											'File '.$oldfilename.' failed to be renamed to '.$newfilename.'',
											'File '.$oldfilename.' failed to be renamed to '.$newfilename.'',
											'Operation',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
										}
									}
									elseif( $newpathexists && !$newpathdeleted )
									{
										eci_log( 'Files','File '.$oldfilename.' failed be deleted','Operation',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
									}
									else
									{
										eci_log( 'State',__('Unexpected state in new project file list'),'Operation',$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );
									}
								}
								?>
                                
                                <?php 
                                // add different form objects depending on $type
                                if( $filesettype == 'singlefile' )
                                {?>
									<tr>
										<td><input type="radio" name="eci_filepath" value="<?php echo $newpath;?>" checked  /></td>
										<td><?php echo $newfilename;//removed $p['path']?></td>	
										<td><?php echo count(file($p['path'].$newfilename));?></td>
										<?php 
										// if $assist = easy we will display the files known data source
										if($assist == 'easy')
										{
											echo eci_get_knowndatasource('table',$newpath);// table or normal
											echo eci_get_knowndataset('table',$newpath);// table or normal
                                		}?>	
										<td><?php echo eci_formatfilesize(filesize($p['path'].$newfilename));//removed $p['path']?></td>
									</tr><?php
                                }
                                elseif( $filesettype == 'multifile' )
							    {?>
									<tr>
										<td><input type="checkbox" name="eci_filepath[]" value="<?php echo $newpath;?>" /></td>
										<td><?php echo $newfilename;//removed $p['path']?></td>	
										<td><?php echo count(file($p['path'].$newfilename));?></td>										
										<td><?php echo eci_formatfilesize(filesize($p['path'].$newfilename));//removed $p['path']?></td>							
									</tr><?php
                                }
                                
								++$available;
							}
						}
					}
				}
				
				// close this directory
				closedir($csvfiles_diropen); 						
			}
		}
	}
	
	if( !$available )
	{
		echo '<br /><strong>';
		_e('No Files Were Found That Are Not Already In Use By A Project');
		echo '</strong>';
	}

	echo '</table>'; 
	
	// clear stored values
	clearstatcache();
}

# Builds Form Menu For Design Selection Per Category On Conditions Stage
function eci_maindesignsmenu( $csv )
{
	$des = get_option('eci_des');
	
	$sel = '';
	
	// apply id to object
	$menu = '<select name="eci_designmain" size="1"><option value="Default">Main Project Design</option>';
	
	if( $des )
	{
		foreach( $des as $key=>$d )
		{ 
			if( $key != 'arraydesc' )
			{
				// apply selected value to current save
				$sel = '';
				
				if( isset( $csv['maindesign'] ) && $csv['maindesign'] == $key ) 
				{				
					$sel = 'selected="selected"';
				}
				
				$menu .= '<option value="'.$key.'" '.$sel.'>'.$key.'</option>'; 
			}
		}   
	}
	else
	{
		$menu .= '<option value="">No Designs Created</option>'; 
	}
	
	$menu .= '</select>';
	return $menu;
}

# Builds Form Menu For Design Selection Per Category On Conditions Stage
function eci_categorydesignsmenu( $id, $csv, $category )
{
	$des = get_option('eci_des');
	
	$sel = '';
	
	// apply id to object
	$menu = '<select name="eci_design'.$id.'" size="1"><option value="main">Main Project Design</option>';
	
	foreach( $des as $key=>$d )
	{ 
		if( $key != 'arraydesc' )
		{
			// apply selected value to current save
			$sel = '';
			
			
			if( isset( $csv['conditions']['categorydesigns'][$category] ) && $csv['conditions']['categorydesigns'][$category] == $key ) 
			{				
				$sel = 'selected="selected"';
			}
			
			$menu .= '<option value="'.$key.$sel.'">'.$key.'</option>'; 
		}
	}      
	
	$menu .= '</select>';
	return $menu;
}

# Creates List Of Categories And Design Menus For Applying Per Category Design Conditions
function eci_listcategorydesigns( $csv )
{	
	$args = array(
	'type'                     => 'post',
	'hide_empty'               => 0,
 	'hierarchical'             => 0,
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );
	
	$cats = get_categories( $args );
	
	echo '<table class="widefat post fixed">
	<tr><td width="200"><strong>Categories (parent only)</strong></td><td><strong>Designs</strong></td></tr>';
	
	$i = 0;
	
	foreach( $cats as $c )
	{	
		if( $c->parent < 1 && $c->name != 'Uncategorized' )
		{
			echo '<tr><td><input name="category'.$i.'" type="text" value="'.$c->name.'" size="50" maxlength="100" readonly="true" /></td><td>'.eci_categorydesignsmenu( $i,$csv,$c->name ).'</td></tr>';
			++$i;
		}
	}	
	
	// include number of categories for the processing loop
	echo '<input name="catcount" type="hidden" value="'.$i.'" />';
	
	echo '</table>';
}

# Builds Advice Based On Current Plugin And Project Condition
function eci_recommendation( $pro,$csv,$spe )
{
	$rec = __('no recommendations at this time');	
	return $rec;
}

# Form Menu Item Selected Decision 
function eci_selected( $valueone, $valuetwo ){if($valueone==$valuetwo){echo'selected="selected"';}}

# Builds Menu Of Blog Categories For Form
function eci_categoriesmenu( $filename )
{	
	$csv = get_option('eci_'.$filename);
	
	// get blogs categories
	$cats = get_categories('hide_empty=0&echo=0&show_option_none=&style=none&title_li=');

	echo '<form method="post" name="eci_singlecategory_form" action="">            

    <label>Category<select name="eci_category" size="1">';
			
	echo '<option value="NA">Not Required</option>';
	
	foreach( $cats as $c )
	{ 
		// apply selected value to current save
		if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == $c->term_id ) 
		{
			$selected = 'selected="selected"';
		}
		
		echo '<option value="'.$c->term_id.'">'.$c->cat_name.'</option>'; 
	}      
	
	echo '</select></label>
	<br /><br />
		<input class="button-primary" type="submit" name="eci_singlecategory_submit" value="Submit" />
	</form>';
} 

# Form Object Listing All Paths - Default And Custom
function eci_pathsmenu()
{
	if( $pat = get_option('eci_pat') )
	{
		echo '<select name="eci_path">';
		
		foreach( $pat as $key=>$p )
		{
			echo '<option value="'. $key .'">'. $p['name'] .'</option>';
		}
		
        echo '</select>';			
	}
	else
	{
		_e('CSV file paths data does not appear to be installed, if you upload now
		your CSV file will be placed in the default directory. The plugin will also
		attempt to install the path data.');
		echo '<br /><br />';
	}
}

# Lists Event Speed Profiles In A Table With Form Radio Buttons
function eci_speedprofilelist()
{
	$spe = get_option('eci_spe');
	
	if( $spe )
	{
		$table = '<table>';
		
		foreach( $spe as $k=>$item )
		{
			if( $k != 'arraydesc' )
			{   			   
				$table .= 
				'
					<tr class="first"><td class="b b-comments">
						<input type="radio" name="eci_speedname" value="'.$k.'" checked  /></td>
						<td class="last t comments">'.$item['label'].'</td>
					</tr>			
				';
			}
		}
									
		$table .= '</table>';
		
		echo $table;
	}
	else
	{
		_e('No event speed profiles were found. They should have been installed when activating the plugin.');
	}
}

# Displays Standard Wordpress Message
function eci_mes( $title,$message )
{
	echo '<div id="message" class="updated fade"><strong>'.$title.'</strong><p>'. $message .'</p>
	<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* ECIFree4.0 */
google_ad_slot = "2068446115";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
}

# Displays Wordpress Error Message
function eci_err( $title,$message )
{
	echo '<div id="error" class="error"><strong>'.$title.'</strong><p>'. $message .'</p><script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* ECIFree4.0 */
google_ad_slot = "2068446115";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
}

/**
 * Header for all pages, handles Easy Project submission
 */
# Adds Wordpress Admin Style Sheets And Control Panel Too All Pages
function eci_header()
{	
	// added 9th July 2011 as part of Easy projects - button is on Start page
	if(isset($_POST['eci_easyprojectevent_submit']))
	{
		// we are going to try and do everything at once
		global $set,$pro,$spe,$des;
		$csv = get_option('eci_'.$_POST['eci_filename']);
		eci_dataimport($_POST['eci_filename'], 1, 'import', $set);
		eci_createposts($csv, $pro, $spe, $set, $des, 250, 1, $_POST['eci_filename']);
	}
	
	eci_installedplugins();
	
		?><p><a href="http://www.wtg.co/eci" title="Visit Easy CSV Importer sales page">The full edition offers a lot more, click here to watch videos of the full powered plugin</a></p> <?php 
	
}


/**
 * Footer for all pages, displays option arrays when activated
 */
function eci_footer()
{
	global $set;
	
	?><p><a href="http://www.wtg.co/eci" title="Visit Easy CSV Importer sales page">The full edition offers a lot more, click here to watch videos of the full powered plugin</a></p> <?php 

	$pro = get_option('eci_pro');

	if( !isset($pro['current']) || isset($pro['current']) && $pro['current'] == 'None Selected' )
	{
		eci_mes(__('No Current Easy CSV Importer Project'),__('Most pages require an existing project to function properly. Please use the Start page to create a project or open an existing one to make it your current project.') );
	}
	?>
	
   <script type="text/javascript">
        // <![CDATA[
        jQuery('.postbox div.handlediv').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
        jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
        jQuery('.postbox.close-me').each(function(){
        jQuery(this).addClass("closed");
        });
        //-->
    </script><?php 
}

# Counts Number of Special Functions Activated
function eci_countspecials( $filename )
{
	$csv = get_option( 'eci_'. $filename );
	$count = 0;
	// 1
	if( isset( $csv['specials']['col']['aioseokeywords_col'] ) && $csv['specials']['col']['aioseokeywords_col'] != 'NA' && $csv['specials']['state']['aioseokeywords_col'] == 'On' ){++$count;}
	// 2
	if( isset( $csv['specials']['col']['madetags_col'] )&& $csv['specials']['col']['madetags_col'] != 'NA'&& $csv['specials']['state']['madetags_col'] == 'On' ){++$count;}
	// 3
	if( isset( $csv['specials']['col']['thumbnail_col'] )&& $csv['specials']['col']['thumbnail_col'] != 'NA'&& $csv['specials']['state']['thumbnail_col'] == 'On' ){++$count;}
	// 4
	if( isset( $csv['specials']['col']['cloaking1_col'] )&& $csv['specials']['col']['cloaking1_col'] != 'NA'&& $csv['specials']['state']['cloaking1_col'] == 'On' ){++$count;}
	// 5
	if( isset( $csv['specials']['col']['permalink_col'] )&& $csv['specials']['col']['permalink_col'] != 'NA'&& $csv['specials']['state']['permalink_col'] == 'On' ){++$count;}
	// 6
	if( isset( $csv['specials']['col']['dates_col'] )&& $csv['specials']['col']['dates_col'] != 'NA'&& $csv['specials']['state']['dates_col'] == 'On' ){++$count;}
	// 7
	if( isset( $csv['specials']['col']['tags_col'] )&& $csv['specials']['col']['tags_col'] != 'NA'&& $csv['specials']['state']['tags_col'] == 'On' ){++$count;}
	// 8
	if( isset( $csv['specials']['col']['excerpt_col'] )&& $csv['specials']['col']['excerpt_col'] != 'NA'&& $csv['specials']['state']['excerpt_col'] == 'On' ){++$count;}
	// 9
	if( isset( $csv['specials']['col']['cats_col'] )&& $csv['specials']['col']['cats_col'] != 'NA'&& $csv['specials']['state']['cats_col'] == 'On' ){++$count;}
	return $count;
}

# Returns Number Of Custom Fields In Use ( Not NA )#
function eci_customfieldsinuse( $filename )
{
	$csv = get_option('eci_'.$filename);
	
	$total = 0;
	
	if( isset( $csv['customfields'] ) )
	{
		foreach( $csv['customfields'] as $key => $column )
		{
			if( $column != 'NA' ){++$total;}
		}
	}

	return $total;
}

# Menu Of Condition Argument Options
function eci_conditionstypes( $filename )
{
	$csv = get_option( 'eci_'.$filename );
	echo '<select name="eci_type" size="1">';
	
	echo '<option value="content">Post Content</option>';
	echo '<option value="title">Post Title</option>';
	echo '<option value="titlecontent">Post Title &amp; Content</option>';
	
	/*    ############   ADD THIS AGAIN - PROCESSING FUNCTION WILL NEED TO LOOP THROUGH COLUMNS    #############
	$i = 0;
	foreach( $csv['format']['titles'] as $column )
	{
		echo'<option value="'. $i .'">Column: '. $column .'</option>';
		++$i;
	}
	*/

	echo '<option value="everything">Everything</option>';

	echo '</select>';
}

# Returns Column Exclusion Status On Config Page
function eci_columnexclusionsstatus( $filename )
{
	$csv = get_option( 'eci_'.$filename );
	
	$c = 0;
	
	if( isset( $csv['updating']['exclusions'] ) )
	{
		foreach( $csv['updating']['exclusions'] as $key => $val )
		{
			if( $val == 'Exclude' )
			{
				++$c;
			}
		}
	}
				
	return ' ---- Excluded: ' . $c;
}

# Builds Drop Down Form Menu For Update Exclusions Form
function eci_exclusionmenu( $column, $filename )
{
	$csv = get_option( 'eci_'.$filename );
	
	$object = '<select name="eci_ex_'.$column.'" size="1">';
	
	if( !isset( $csv['updating']['exclusions'][$column] ) ) 
	{
		$object .= '<option value="Include" selected="selected">Not Set</option>';
	}
	
	if( isset( $csv['updating']['exclusions'][$column] ) && $csv['updating']['exclusions'][$column] == 'Include' ) 
	{
		$selected = 'selected="selected"';
	}
	else
	{
		$selected = '';
	}
	
	$object .= '<option value="Include" '.$selected.'>Include</option>';

	if( isset( $csv['updating']['exclusions'][$column] ) && $csv['updating']['exclusions'][$column] == 'Exclude' )
	{
		$selected = 'selected="selected"';
	}
	else
	{
		$selected = '';
	}
	
	$object .= '<option value="Exclude" '.$selected.'>Exclude</option>';
		
	$object .= '</select>';
	
	return $object;
}

/**
 * Constructs a message then passes it to eci_mes for output
 * @param string $title
 * @param string $message
 * @param integer $line
 * @param filename $file
 * @param string $function
 * @param array set
 */
function eci_inf($title,$message,$line,$file,$function,$set)
{
	$deb = get_option('eci_deb');
	if($deb == 'Yes')
	{
		$text = $message.'<br /><br />
		<strong>PHP Information</strong><br />
		Line: '.$line.'<br />
		File: '.basename($file).'<br />
		Function: '.$function.'';
		eci_mes($title,$text);
	}
}

/**
 * Displays menu of csv columns on its own form for using as a single interface feature
 * @param string $saved (the value already selected)
 * @param base only $filename
 */
function eci_columnmenu_singleuseform($saved,$filename,$formname,$csv,$action = '')
{	
	global $pro;
	
	$titlearray = eci_gettitlearray( $csv, $pro, $filename );
		
	echo '<form method="post" name="'.$formname.'" action="">
	<select name="formobject" size="1">';
	
	echo '<option value="NA">';
	_e('Not Required (beta testing)');
	echo '</option>';
	
	$i = 0;
	foreach( $titlearray as $column )
	{		
		echo'<option value="nocorrectvalues" >'. $column .'</option>';
		++$i;
	}
	echo '</select><br /><br />'.eci_posttypefiltermethod_menu($filename).'<br /><br />
	<input class="button-primary" type="submit" name="submit" value="Save" />
	</form>';
}



/*
 * Checks if giving plugin name is active or not
 * @param string $default (name of plugin)
 * @return true or false
 */
function eci_is_plugininstalled($queryplugin)
{
	$activeplugins = get_option('active_plugins');// changed from get_settings by Ryan Bayne on 14th April 2011
	
	if( $activeplugins )
	{
		foreach( $activeplugins as $key=>$plugin )
		{
			if( stristr ( $plugin, $queryplugin ) )
			{
				return true;
			}
		}
	}
	else 
	{
		return false;	
	}
}


function eci_categoriespairing_form($filename,$csv,$pro)
{	
	// only show the form if at least one category group has been created
	if(!isset($csv['categories'][0]['cat1']))
	{
		echo '<strong>You cannot use this feature until you create a category group, then a form will be dislayed here</strong>';
	}
	else 
	{
		// now ensure the user has at least 1 record of data - keep the total for displaying to user
		$records = eci_counttablerecords($filename);
		
		if(!$records || $records == 0)
		{
			echo '<strong>'. __('You cannot use this feature until you import data, then a form will be dislayed here').'</strong>';
		}
		else
		{
			global $wpdb;
			
			$form = '<form method="post" name="eci_categorypairing_form" action="">
				<table class="widefat post fixed">
					<tr>
						<td width="200">Categories In Data</td><td>Categories In Blog</td>
					</tr>';
			
					$cleancol = eci_cleansqlcolumnname($csv['categories'][0]['cat1']);
					
					$query = "SELECT DISTINCT ".$cleancol." FROM ". eci_wptablename( $filename ) . ";";
					$catsfound = $wpdb->get_results( $query );
			
					if(!$catsfound)
					{
						eci_mes(__('Project Table Query Categories Problem'),'ECI attempted to establish all the distinct categories in the column named '.$cleancol.' ');
					}		
					else
					{
						$id = 0;
						
						foreach($catsfound as $catkey=>$cat)
						{
							$form .= '
							<tr>
								<td><input name="eci_datacategory_'.$id.'" type="text" value="'.$cat->$cleancol.'" size="30" readonly /></td>
								<td>'.eci_categoriesmenu_object($csv,$id).'</td>
							</tr>'; 
							++$id;
						}
					}
                        
			$form .= '</table>
			<input name="eci_pairedcategories" type="hidden" value="'.$id.'" />
			<input class="button-primary" type="submit" name="eci_categorypairing_submit" value="Submit" />
			</form>';
			echo $form;
		}
	}
}

function eci_categoriesmenu_object($csv,$id)
{	
	// get blogs categories
	$cats = get_categories('hide_empty=0&echo=0&show_option_none=&style=none&title_li=');
	$menu = '<select name="eci_blogcategory_'.$id.'" size="1">';
	$menu .= '<option value="NA">Not Required</option>';
	foreach( $cats as $c )
	{ 
		// apply selected value to current save
		if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == $c->term_id ) 
		{
			$selected = 'selected="selected"';
		}
		
		$menu .= '<option value="'.$c->term_id.'">'.$c->cat_name.'</option>'; 
	}      
	
	$menu .= '</select>';
	return $menu;	
}

function eci_columnmenu_taxonomies($csv,$pro,$filename,$objectid,$taxname,$posttype)
{
	// then we list all column titles from the profile with their ID in the form
	$cols = 0;

	// 	$csv['taxonomies'][$_POST['eci_posttype_'.$i]][$_POST['col_'.$i]] = $_POST['eci_tax_'.$i];	
	
	$titlearray = eci_gettitlearray($csv,$pro,$filename,$objectid);

	$menu = ' <select name="col_'. $objectid .'" size="1"><option value="NA">NA</option>';

	
	// add all csv file column names to the menu as items
	foreach( $titlearray as $column )
	{	
		$select = '';
		if(isset($csv['taxonomies'][$posttype][$column]) && $csv['taxonomies'][$posttype][$column] == $taxname)
		{
			$select = 'selected="selected"';
		}
		
		$menu .= '<option value="true" '.$select.'>'. $column .'</option>';	
		
		++$cols;
	}	
		
	$menu .= '</select>';
	
	return $menu;	
}

/**
 * Displays checkbox for user applying category level to a post or not
 * Categories can be created, but a tick does not need to be put in the box for every level on the post edit screen
 * @param array $csv
 * @param array $pro
 */
function eci_applycategory_checkbox($filename, $id, $csv, $pro)
{	
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="eci_applycat_'.$id.'" value="unknown" disabled="disabled" />';
}

/**
 * Outputs form button for deleting giving conditions
 * @param string $condition (type of condition, used to access correct array)
 * @param integer $key
 */
function eci_conditionsdelete_button($condition,$key,$term)
{
	return '
	<form method="post" name="eci_conditions_form" action=""> 
	<input type="hidden" name="eci_condition_type" value="'.$condition.'" />
	<input type="hidden" name="eci_condition_key" value="'.$key.'" />
	<input type="hidden" name="eci_condition_term" value="'.$term.'" />
	<input class="button-primary" type="submit" name="eci_conditiondelete_submit" value="Delete" />
	</form>';
}
?>