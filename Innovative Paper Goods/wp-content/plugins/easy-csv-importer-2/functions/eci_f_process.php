<?php

function eci_export_finish()
{
	if( isset( $_POST['eci_executeexport_finish_submit'] ) && is_admin() )
	{	
		global $wpdb;
		
		$exp = get_option('eci_exp');
		
		$header = '';
	
		$data = '';
		
		$result = mysql_query ( $_POST['eci_query'] );
	
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=export.csv' );
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	
		// output header row (if atleast one row exists)
		$row = mysql_fetch_assoc( $result );
		if ( $row )
		{
			eci_echocsv( array_keys( $row ) );
		}
		
		// output data rows (if atleast one row exists)
		while ( $row )
		{
			eci_echocsv( $row );
			$row = mysql_fetch_assoc( $result );
		}
	}
}

function eci_echocsv( $fields )
{
	$separator = '';
	foreach ( $fields as $field )
	{
		if ( preg_match( '/\\r|\\n|,|"/', $field ) )
		{
			$field = '"' . str_replace( '"', '""', $field ) . '"';
		}
		echo $separator . $field;
		$separator = ',';
	}
	echo "\r\n";
}
  
function eci_export_processstart()
{
	if( is_admin() )
	{		
		$exp = get_option('eci_exp');

		// build select and from
		$queryselect = 'SELECT ';
		$queryfrom = ' FROM ';
		$orderby = ' ORDER BY ';
		$counttable = 0;
		$countcolumn = 0;
		
		// check if order by column set and apply it
		$temp = 0;
		if( $temp == 6 )
		{
			$orderbydone = true;
		}
		else
		{
			$orderbydone = false;
		}
				
		// loop through each entry in $exp['columns']
		foreach( $exp['columns'] as $table=>$valone )
		{
			//echo $table.'= key1 table name    ';
			//$exp['columns'][$row_table[0]][$row_column[0]]['type'] = 'standard'; 		
			foreach( $valone as $column => $valtwo )
			{
				//echo $column.'= key2 column name    ';
				//echo $valtwo.'= val2  array    ';
				
				if( $countcolumn > 0 )
				{
					$queryselect .= ',';
				}

				$queryselect .= $table.'.'.$column;
				
				// if ORDER BY column not set we will use the first table and first column
				if( $orderbydone == false )
				{
					$orderby .= $table.'.'.$column;
					$orderbydone = true;
				}
				
				++$countcolumn;
			} 
			
			if( $counttable > 0 )
			{
				$queryfrom .= ',';
			}

			$queryfrom .= $table;
			
			++$counttable;
		}

		$finalquery = $queryselect.$queryfrom.$orderby;
		
		$finishform = '<form method="post" name="eci_executeexport_finish_form" action="">
		<input type="hidden" name="eci_query" value="'.$finalquery.'" />
		<input class = "button-primary" type = "submit" name = "eci_executeexport_finish_submit" value = "Continue Export" />
		</form>';
		
		eci_mes('Export Part One Complete','A database query has been created using your export configuration. The query is shown below, you
		may now click continue to begin the actual export.'.$finishform);
		
		eci_mes('MySQL Query',$finalquery);
	}	
}

function eci_export_savesettings()
{
	// ensure user is admin before continuing
	if( is_admin() )
	{
		$exp = get_option('eci_exp');	
		
		// query database for table names
		$result_tables = eci_get_tables();
		
		while ($row_table = mysql_fetch_row($result_tables)) 
		{		   
		   	// query current tables columns
			$result_columns = eci_get_dbcolumns($row_table[0]);	
			
			$i = 0;
			
			while ($row_column = mysql_fetch_row($result_columns)) 
			{				
				//echo "Column:".$row_column[0]."<br />";
				if( isset( $_POST['standard_'.$row_table[0].'_'.$row_column[0].''] ) )
				{
					$exp['columns'][$row_table[0]][$row_column[0]]['type'] = 'standard'; 
				}

				/* SPLIT ABILITY TO BE COMPLETE
				// is set split $_POST for this table and this column
				if( isset( $_POST['split_'.$table.'_'.$row[0]] ) )
				{
					
					// query this table and get all distinct values within this column
					$distinctresults = $wpdb->get_results( 'SELECT DISTINCT '.$row[0].' FROM '. $table .' WHERE '.$row[0].' IS NOT NULL',ARRAY_A );
					
					
					// loop through the distinct values - from here on they will be treated as columns
					foreach( $distinctresults as $rec )
					{
						$uniqueval = $rec[$row[0]];
					}
					
					// we add each unique value to the array as if it is a column
					$buildarray_split[$table][] = $uniqueval;
					
				}	*/		
					
			}// end while row column
			
		}// end while row table
		
		update_option('eci_exp', $exp);
		
		eci_mes('Export Settings Saved', 'Your next data export will be effected by your new configuration');
	}// end is admin
}

/**
 * Saves numeric modification on conditions panel
 */
function eci_numericmodifier_save( $filename,$csv )
{
	if( !isset( $_POST['eci_modifier'] ) || is_null( $_POST['eci_modifier'] ) || !is_numeric( $_POST['eci_modifier'] ) )
	{
		eci_err( __('Modification Value Required'),__('You must enter a numeric value only value which will modify a numeric data value') );
	}
	elseif( $_POST['eci_columnid_1'] == 'NA' )
	{
		eci_err( __('No Column Selected'),__('You must select a column of data holding numeric values which will be modified by the answer of the sum') );
	}
	else 
	{
		// check that selected column holds numeric values only, process 100 lines
		if( eci_numericcolumncheck( $_POST['eci_columnid_1'], $csv['sql']['tablename'] ) )
		{
			$csv['conditions']['numericmod']['modifier'] = $_POST['eci_modifier'];			
			$csv['conditions']['numericmod']['symbol_1'] = $_POST['eci_mathssymbol_1'];			
			$csv['conditions']['numericmod']['columnid_1'] = $_POST['eci_columnid_1'];
			
			if( update_option( 'eci_'.$filename,$csv ) )
			{
				eci_mes( __('Numeric Modifier Condition Saved'), __('The column you selected will be effected by this modifier. The values
				in the column will be changed before being used to replace tokens or put into custom fields. There is also a shortcode
				to do this should you want to place the original data value into your content plus the sum answer.') );
			}
			else 
			{
				eci_err( __('No Changes Made'), __('No changes were required to the current configuration') );
			}
		}
		else 
		{
			eci_err( __('Data Column None Numeric'),__('It appears that the column you selected may hold none numeric values. This feature requires a column of data holding numeric values only') );
		}
	}
}

# Saves Custom Field Form Changes 
function eci_savecustomfields( $filename )
{
	$i = 0;
	$used = 0;
	
	$csv = get_option( 'eci_'.$filename );
		
	###############  IMPROVE THIS FUNCTION TO SHOW EXAMPLES OF DATA    ################
	
	// delete current custom fields - removes any no longer entered on form
	if( isset( $csv['customfields'] ) )
	{
		unset( $csv['customfields'] );
	}
	
	while( $i < $_POST['cfcount'] )
	{		
		if( empty( $_POST['cf_'.$i.''] ) )
		{
			// do nothing
		}
		elseif( !empty( $_POST['cf_'.$i.''] ) && $_POST['col_'.$i.''] == 'NA' && !empty( $_POST['def_'.$i.''] ) )
		{
			// save default
			$csv['customfields'][$_POST['cf_'.$i.'']]['def'] = $_POST['def_'.$i.''];	
		}
		elseif( !empty( $_POST['cf_'.$i.''] ) && $_POST['col_'.$i.''] != 'NA' )
		{
			// save column
			$csv['customfields'][$_POST['cf_'.$i.'']]['col'] = $_POST['col_'.$i.''];
			$csv['customfields'][$_POST['cf_'.$i.'']]['fun'] = $_POST['fun_'.$i.''];
			
			// then check if default set - used if column values are null
			if( !empty( $_POST['def_'.$i.''] ) )
			{
				$csv['customfields'][$_POST['cf_'.$i.'']]['def'] = $_POST['def_'.$i.''];				
			}
			
			++$used;
		}
		
		++$i;
	}

	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$filename,$csv ) )
	{
		eci_mes( __('Custom Fields Saved'),'You have created '.$used.' Custom Fields for adding meta data to your posts' );
	}
	else
	{
		eci_err( __('No Custom Fields Changed'),__('Wordpress did not detect any changes made to your Custom Fields') );
	}
}

/**
 * Easy CSV Importer - saves the single menu that the user has selected for holding their unique values
 * @param $filename 
 */
function eci_valuedesignmenusave( $filename,$column )
{
	$csv = get_option('eci_'.$filename);
	$csv['valdesigncolumn'] = $column;
	if(update_option('eci_'.$filename,$csv))
	{
		eci_mes( __('Value Designs Column Saved'), __('The conditions panel will now show a list of distinct values. You can pair a design with each value to apply that design to your post.') );
	}
	else 
	{
		eci_err( __('Value Design Column Failed'),__('Please try again, if the problem persists plese seek support') );
	}
}
	
/**
 * Easy CSV Importer - saves the design to value configuration
 * @param $filename
 */
function eci_valuedesignconfigsave( $filename )
{
	$csv = get_option('eci_'.$filename);
	$i = 0;
	while( $i < $_POST['valcount'] )
	{		
		$csv['valdesign'][$_POST['val'.$i.'']] = $_POST['eci_design'.$i.''];
		++$i;
	}
	
	if(update_option('eci_'.$filename,$csv))
	{
		eci_mes(__('Value Designs Saved'), __('Easy CSV Importer will apply different designs based on the pairing you just saved') );
	}
	else 
	{
		eci_err(__('Value Designs Failed'),__('The plugin could not save your paired designs, please try again') );
	}
}
			
/**
 * Validates And Saves Widget One Form Submission For Project Only
 * 
 * @return NA
 * @param $widgettitle,$project
 */
function eci_widgetone_saveproject( $widgettitle,$project )
{
	$set = get_option( 'eci_set' );
	$set['widget'][1]['title'] = $widgettitle;	
	$set['widget'][1]['project1'] = $project;
	if( update_option( 'eci_set',$set ) )
	{
		eci_mes( __('Widget One Changes Saved'),__('Your changes will take effect straight away, please test your widget in your browser before finishing.') );
	}
	else
	{
		eci_err( __('No Changes Made'),__('It appears no changes were made, nothing has been changed for your widget.') );
	}
}

/**
 * Validates And Saves Widget One Form Submission For Selected Columns
 * 
 * @return NA
 * @param $itemimgcol,$itempricecol,$itemtextcol,$itemtitlecol,$project,$widgettitle
 */
function eci_widgetone_savecolumns( $widgettitle,$project,$itemtitlecol,$itemimgcol,$itempricecol,$itemtextcol )
{
	$set = get_option( 'eci_set' );
	$set['widget'][1]['title'] = $widgettitle;	
	$set['widget'][1]['project1'] = $project;
	$set['widget'][1]['titlecolumn'] = $itemtitlecol;		
	$set['widget'][1]['imagecolumn'] = $itemimgcol;	
	$set['widget'][1]['pricecolumn'] = $itempricecol;		
	$set['widget'][1]['textcolumn'] = $itemtextcol;		
	
	if( update_option( 'eci_set',$set ) )
	{
		eci_mes(__('Widget One Changes Saved'),__('Your changes will take effect straight away, please test your widget in your browser before finishing.') );
	}
	else
	{
		eci_err(__('No Changes Made'),__('It appears no changes were made, nothing has been changed for your widget.') );
	}
}

/**
 * Processes giving csv file and stores a profile in $csv options table
 * @param string $filepath
 * @param string $filename
 * @return true or false directly from update_option
 */
function eci_create_csv_profile( $filepath, $filename, $approach )
{
	//  check if wordpress option value already exists for filename
	if( !$csv = get_option( 'eci_'.$filename ) )
	{
		$csv = array();
	}
			
	/* FILE DATESTAMP AND UPDATING TRIGGER
	1. Set a current data and a previous date value
	2. While both do not match it means an update is not complete
	3. Whenever an update is complete, we set previous date to same as current */
	$csv['format']['currentfiledate'] = filemtime( $filepath );
	$csv['format']['previousfiledate'] = filemtime( $filepath );
			
	// use pear to get file configuration
	$conf = File_CSV::discoverFormat( $filepath );
	$fields = File_CSV::read( $filepath, $conf );

	// save column titles	
	$count = 0;
	foreach( $fields as $title )
	{
		$csv['format']['titles'][$count] = $title;
		$count++;
	}
	
	// if quote not established use double quote as default
	if(!isset($conf['quote']) || $conf['quote'] == NULL )
	{
		$conf['quote'] = '"';
	}
	
	// if separator not established use comma as default
	if(!isset($conf['sep']) || $conf['sep'] == NULL )
	{
		$conf['sep'] = ',';
	}

	// variables for while loop and tracking columns already checked
	$datachecked = array();// we use to track what columns have been checked in the event of null values
	$datacomplete = 0;// count number of entries made to $datachecked array - keep going until it equals columns
	$recordscount = 0;// total rows counter (included header row)
				
	// we can force the plugin to count csv rows using the while loop, this is the old way, we'll keep it available for now
	$forcewhileapproach = false;
	
	// loop through files columns checking example data and building token list - this used to be used to count csv rows
	// it can still be used to count rows by changing $forcewhileapproach to true but the new php count method is faster
	$con = $conf;
	while ( $record = File_CSV::read( $filepath,$con ) ) 
	{
		if( $recordscount == 0 )
		{
			// add the current
		}
		elseif( $recordscount != 0 )
		{
			$columnid = 0;
			foreach( $record as $data )
			{					
				// ensure column has not already been checked and set in array
				if( !isset( $datachecked[$columnid] ) )
				{
					// ensure field value is not null - if it is do not insert
					if( $data != NULL && $data != '' && $data != ' ' && !empty( $data ) )
					{
						if( eci_valid_url($data) )
						{							
							// set column id in array to prevent it being rechecked
							$datachecked[$columnid] = 'url';
							// increase $datacomplete - when it matches column count then we have all we need
							++$datacomplete;
							$csv['format']['tokensymbols'][$columnid] = 'x'; 
						}
						elseif( is_numeric($data) )
						{							
							$datachecked[$columnid] = 'numeric';
							++$datacomplete;
							$csv['format']['tokensymbols'][$columnid] = '+';								
						}
						else
						{
							$datachecked[$columnid] = 'string';
							++$datacomplete;								
							$csv['format']['tokensymbols'][$columnid] = '+';
						}
					}
				}
				++$columnid;
			}
			
			// when we have processed every column stop the while loop
			if( $columnid == $conf['fields'] )
			{
				break;
			}
		}
		
		++$recordscount;
	}
	
  	// count number of lines in file
  	if( $forcewhileapproach == false )
  	{
	  	$lines = count(file($filepath));
	  	
	  	// deduct 1 (the header) the count we store
	  	if( !$lines )
	  	{
	  		eci_log('Count CSV Lines Failed', 'Create CSV profile action could not count file rows','The plugin attempted to count the number
	  		of lines in '.basename($filepath).' file when you created a new profile with it but there was a failure.', 
	  		basename($filepath), $set, 'Critical',__LINE__,__FILE__,__FUNCTION__);
	  	}
  	}
  	
	// set csv format
	$csv['format']['rows'] = 2000;
	$csv['format']['rowsupdatedtime'] = time();
	$csv['format']['seperator'] = $conf['sep'];
	$csv['format']['quote'] = $conf['quote'];
	$csv['format']['columns'] = $conf['fields'];		
	$csv['sql']['tablename'] =  eci_wptablename( $filename );
	$csv['importencoding'] = 'None';
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	
	// adding 9th July 2011 by Ryan Bayne to assist new approach 
	//to creating posts faster,straight from CSV file,on start page	
    $csv['datemethod'] = 'default';
    $csv['posttype'] = 'post';
    $csv['poststatus'] = 'publish';
    $csv['postcomments'] = 'open';
    $csv['postpings'] = '1';
    $csv['postadopt'] = '1';
    $csv['postpublisher'] = '1';
    $csv['maindesign'] = 'Default';
    // approach - controls easy project controls
	$csv['approach'] = $approach;
        
	// save changes
	return update_option( 'eci_' . $filename, $csv );	
}

/**
 * Saves new csv file project to the $pro array with default values
 * @package Easy CSV Importer
 * @param string $filename1
 * @param string $filepath1
 * @param string $filesettype (single or multifile)
 * @param string $level (parent or child)
 * @param string $method (byorder or byid)
 * @param string $type
 * @return true or false (result comes from update_option function)
 */
function eci_create_pro_profile( $filename1,$filepath1,$filesettype,$level,$fileset,$method,$type )
{
	// get project array
	$pro = get_option('eci_pro');

	$pro[$filename1]['filesettype'] = $filesettype;
	if( $filesettype == 'multifile' )
	{		
		$pro[$filename1]['multifilemethod'] = $method;// mysql query method byid or byorder		
		$pro[$filename1]['multifileset'] = $fileset;
		$pro[$filename1]['multifilelev'] = $level;// parent or child
	}
	
	// set current value - if a multifile project only set it if this is the parent
	if( $filesettype == 'multifile' && $level == 'parent' )
	{
		$pro['current'] = $filename1;// for user interest	
	}
	elseif( $filesettype == 'singlefile' )
	{
		$pro['current'] = $filename1;// for user interest		
	}
	
	// store default project values - also sets current project
	$pro[$filename1]['created'] = time();// for user interest
	$pro[$filename1]['reset'] = time();// when counters last reset
	$pro[$filename1]['filepath'] = $filepath1;// full files path
	$pro[$filename1]['status'] = 'Paused';// Paused or Active
	$pro[$filename1]['protype'] = $type;// when counters last reset
	$pro[$filename1]['postscreated'] = 0;// includes posts created or deleted during updates
	$pro[$filename1]['postsupdated'] = 0;// reset per new update phase
	$pro[$filename1]['postsfailed'] = 0;
	$pro[$filename1]['adoptsucces'] = 0;
	$pro[$filename1]['adoptfailed'] = 0;
	$pro[$filename1]['catscreated'] = 0;// number of categories created in project
	$pro[$filename1]['tagscreated'] = 0;// number of tags created in project
	$pro[$filename1]['rowsinsertsuccess'] = 0;// number of csv files rows inserted (sql succcess)
	$pro[$filename1]['rowsupdatesuccess'] = 0;// number of csv rows used to update a database row
	$pro[$filename1]['rowsinsertfail'] = 0;// number of csv rows that caused sql insert failure
	$pro[$filename1]['rowsupdatefail'] = 0;// number of csv rows that caused sql update failure
	$pro[$filename1]['events'] = 0;// number of events previously actioned for project
	$pro[$filename1]['speed'] = 'fullspeed';// data import,update,post creation update speed
	
	// save project array
	return update_option('eci_pro',$pro);
}
		
/**
 * Creates a new project - currently based on $_POST submission with no pre validation
 * @param array $set
 */
function eci_newproject()
{
	if( isset( $_POST['eci_newproject_submit'] ) )
	{
		global $set;
		
		// include PEAR CSV
		eci_pearcsv_include();
		
		// we need to get $filename1 as parent file which project is based on
		if( $_POST['eci_filesettype'] == 'singlefile' )
		{
			$filename1 = basename( $_POST['eci_filepath'] );  
			$filepath1 = $_POST['eci_filepath'];
		}
	
		// save filename and filepath too project list array - returns false if filename exists already
		if( !eci_updateprojectlist( $filename1 ) )
		{
			eci_err(__('File/Project Name Already In Use'),__('The submitted csv file name is already in use, you cannot have two projects with the same name'));
		}
		else// continue processing csv file and creating project profile
		{		
				$prores = eci_create_pro_profile( $filename1,$filepath1,$_POST['eci_filesettype'],'parent','singlefile','NA',$_POST['eci_projecttype'] );
				$csvres = eci_create_csv_profile( $filepath1, $filename1,$_POST['eci_approach'] );
	  
				// build and store the start of the sql insert query
				eci_sqlinsert_start( eci_wptablename( $filename1 ),$filename1 );						
				
				// create project table
				eci_createtable( $filename1,$set );	
				
				// set title result value for sake of passing final argument
				$titlesres = true;
			
			
			// output results - do some checks to ensure we have values we need
			if( $csvres && $prores && $titlesres )
			{
				###### IMPROVE THIS TO DISPLAY A MULTI FILE SET RESULT AND NOT JUST SINGLE FILE
				$csv = get_option('eci_'.$filename1);
				$pro = get_option('eci_pro');
				$spe = get_option('eci_spe');				


				eci_mes(__('Success - Project Created'),__('The plugin established the format of your file.
				Your separator is <strong>'.$csv['format']['seperator'].'</strong> and your quote is 
				<strong>'.$csv['format']['quote'].'</strong>'));
				
			}
			else
			{
				eci_err(__('Failed - CSV File Problem'),__('The plugin could not read your csv file.
				Please ensure it is a properly formatted csv file. It is recommended to make column
				titles a single word. Also try opening your file in Excel, if that does not open
				like a spreadsheet then the csv file is not ready for using.') );
			}
		}
	}
}



/**
 * Counts values in array then counts unique values only, comparing the answers
 * @param array $array
 */
function eci_array_has_dupes($array) 
{
	// clean titles based on sql and count again
	$temparray = $array;
	
	$i = 0;
	foreach( $temparray as $title )
	{
		$temparray[$i] = eci_cleansqlcolumnname( $title );
		
		++$i;
	}
	
	// count array values after cleaning
	$titlestotal_clean = count( $temparray );
	$uniquetitlestotal_clean = count( array_unique( $temparray ) );
	
	if( $titlestotal_clean != $uniquetitlestotal_clean )
	{
		$difference = $titlestotal_clean - $uniquetitlestotal_clean;
		return $difference;
	}
	else
	{
		return 0;
	}
}

# remove directory from plugin data (does not delete folder user must do that ftp etc)
function eci_undodirectory( $pathname,$action )
{
	###############################  TO BE COMPLETE   ##################################
}

# Saves And Tests Unique Key Submission On Update Stage
function eci_processuniquekey( $filename, $key1, $key2, $key3 )
{
	global $wpdb;
	
	// get plugin options
	$eci = get_option('eci_set');
	$pro = get_option('eci_pro');
	$csv = get_option( 'eci_'.$filename );
	
	// delete current key settings to prevent update failure  - need to allow user to keep testing
	unset( $csv['updating']['key1'] );
	unset( $csv['updating']['key2'] );
	unset( $csv['updating']['key3'] );
	
	// save the deletion of existing key settings
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	update_option( 'eci_'.$filename, $csv );
	
	// unset array values for keys set as NA for post creation
	if($key1 == 'NA'){unset($csv['updating']['key1']);}else{$csv['updating']['key1'] = $key1;}
	if($key2 == 'NA'){unset($csv['updating']['key2']);}else{$csv['updating']['key2'] = $key2;}
	if($key3 == 'NA'){unset($csv['updating']['key3']);}else{$csv['updating']['key3'] = $key3;}

	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	// update unique reference column submissions
	if( !update_option( 'eci_'.$filename, $csv ) )
	{
		eci_err(__('No Changes Saved'),ECINOSAVE);
	}
	else
	{		
		if( $key1 == 'NA' && $key2 == 'NA' && $key3 == 'NA' )
		{
			eci_mes( __('Unique Key Not Setup'),__('You saved Not Applicable for all 3 Unique Key choices, no updating can be performed for this project.') );
			$csv['updating']['ready'] = false;
			$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
			update_option( 'eci_'.$filename, $csv );
		}
		else
		{
			eci_mes(__('Unique Key Saved'),__('Columns you selected to build your Unique Key have been saved. The plugin will now test the Unique Key however the results are a guide for you to decide if updating in your project is ready.'));
			$csv['updating']['ready'] = true;
			update_option( 'eci_'.$filename, $csv );
	
			// only attempt to test the unique key if the project table has 10 or more records
			$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . eci_wptablename( $filename ) );
			
			if( $count < 10 )
			{
				eci_mes(__('Unique Key Test Cannot Be Performed'),__('Your project database table requires 10 or more records
						to perform sufficient tests. The test is not required and 100 records are recommended.'));
			}
			else
			{
				// how many columns are in use? 
				$c = 0;
				if( isset( $csv['updating']['key1'] ) && is_numeric( $csv['updating']['key1'] ) ){ ++$c; }
				if( isset( $csv['updating']['key2'] ) && is_numeric( $csv['updating']['key2'] ) ){ ++$c; }
				if( isset( $csv['updating']['key3'] ) && is_numeric( $csv['updating']['key3'] ) ){ ++$c; }
							
				// count rows where key matches other rows			
				$duplicates = 0;
							
				// query one - build a query selecting only columns submitting as unique reference
				$q1 = "SELECT ";
									
				$count = 0;
				
				foreach( $csv['format']['titles'] as $col )
				{			
					if( isset( $csv['updating']['key1'] ) && $col == $csv['updating']['key1'] ){ if( $count > 0 ){ $q1 .= ','; } $q1 .= $col; ++$count; }
					if( isset( $csv['updating']['key2'] ) && $col == $csv['updating']['key2'] ){ if( $count > 0 ){ $q1 .= ','; } $q1 .= $col; ++$count; }
					if( isset( $csv['updating']['key3'] ) && $col == $csv['updating']['key3'] ){ if( $count > 0 ){ $q1 .= ','; } $q1 .= $col; ++$count; }
				}
		
				// add end of sql query
				$q1 .= ' FROM '.eci_wptablename( $filename ).' LIMIT 100';
		
				// run query
				$result1 = $wpdb->get_results( $q1 );
		
				// query two - build query for the check on every row
				$q2start = 'SELECT COUNT(*) FROM ' . eci_wptablename( $filename ) . ' WHERE ';
				
				// loop through results checking each against all records in project table
				foreach( $result1 as $record )
				{
					$q2end = '';
										
					// select any matching results and increase counter
					$count2 = 0;
					
					// keep count how many commas placed
					$ANDplaced2 = 0;
					
					// loop through csv columns to build the query
					foreach( $csv['format']['titles'] as $column )
					{	
						// loop three times checking all 3 key settings
						$loop = 0;
						
						while( $loop != $c )
						{
							++$loop;
							
							// when column matches key column - apply both record and column title to sql query
							if( $column == $csv['updating']['key'.$loop.''] )
							{ 	
								// put data value for current column into $data
								eval( '$data = $record->$column; ' );
													
								$q2end .= " " . $column . " = " . "'". mysql_real_escape_string($data) ."'";
													
								if( $count2 > 0 ){ $q2end .= ' AND '; }
							}
						}
						++$count2;// used to track where AND should bed placed
					}
		
					// put two halds of query together
					$q2 = $q2start . $q2end;
					
					// run query and get variable on how many matches found
					$found = $wpdb->get_var( $q2 );
					
					// reset query2end for the next record
					$query2end = '';
					
					// deduct one - one row found as duplicate is the same row being compared too all others
					$found = $found - 1;
					
					// add new found total to overall duplicates out of the 100 records
					$duplicates = $duplicates + $found;
				}
		
				if( $duplicates > 0 )
				{
					eci_mes(__('Unique Key Test Results: Not Unique'),'The test found that one or more
					of your records have matching data within in the columns used as your Unique Key.
					A total of '. $duplicates .' matches were found, one record is compared to all 
					others with each record possibly matching many others.');
				}
				else
				{
					eci_mes(__('Unique Key Test Results: Key Is Unique'),__('The test did not find any duplicate
							keys after testing 100 records and comparing the key data against the 
							rest of your project table. Please do not make changes to your Unique Key
							once your project is creating posts and updating.') );
				}
			}
		}
	}
}

# Delets CSV File Using Passed Path
function eci_deletecsvfile( $pathname,$set )
{
    $do = unlink( $pathname );
    if( $do == "1" )
	{
        eci_mes( __('CSV File Deleted'),'You have deleted '.$pathname.'' );
    } 
	else 
	{ 
		eci_err( __('Failed To Delete CSV File'),'You attempted to delete a csv file but it was not
				possible. Please try again then seek support. Provide the following path
				of your csv file and also confirm that you have double checked that the file
				was not deleted. <br /><br />Path: '.$pathname.'' );
	} 
}

/**
 * Easy CSV Importer - saves general update settings
 * @param file $filename
 * @param unknown_type $updateposts - automatically update posts with new data or not
 * @param unknown_type $autonewfile - automatically process new files or not
 * @param unknown_type $postsync - switch for activating post to data sync
 * @param unknown_type $synccontent - the column that should be updated with the post entire content
 */
function eci_updatestagesettings( $filename, $updateposts, $autonewfile, $postsync, $synccontent )
{
	$csv = get_option('eci_'.$filename);
	
	$csv['updating']['updateposts'] = $updateposts;
	$csv['updating']['autonewfile'] = $autonewfile;	
	$csv['updating']['postdatasync'] = $postsync;	
	$csv['updating']['synccontent'] = $synccontent;
		
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$filename, $csv ) )
	{
		eci_mes(__('Update Settings Saved'),__('Changes have been saved, details for each option saved will be shown below this message.'));
		
		if( $csv['updating']['updateposts'] == 'Yes' )
		{
			eci_mes(__('Update Posts Activated'),__('When the plugin has new data stored in your projects table, 
			it will update posts. The update happens only when posts are opened for viewing.'));
		}

		if( $csv['updating']['autonewfile'] == 'Yes' )
		{
			eci_mes(__('Automatically Process New File Activated'),__('The plugin will monitor your projects
			csv file. If the files datestamp increase, the files data will be used to update the project database table.'));
		}
	
		if( $csv['updating']['postdatasync'] == 'Yes' )
		{
			eci_mes(__('Post To Data Sync Activated'),__('When you edit post content or custom field values, the changes you
			make will be applied to the project database.'));
		}
		
		if( $csv['updating']['synccontent'] == 'Yes' )
		{
			eci_mes(__('Post To Data Sync Content Column'),__('Post to data sync will put the post entire content into the '.$csv['updating']['synccontent'].' column.'));
		}
	}
	else
	{
		eci_err(__('No Changes Saved'),ECINOSAVE);
	}
}

# Save Value Swap Submission
function eci_valueswapsave( $filename,$type,$old,$new )
{
	$csv = get_option('eci_'.$filename);
	
	$i = 0;
	
	if( isset( $csv['conditions']['valueswap'] ) )
	{
		foreach( $csv['conditions']['valueswap'] as $key => $arg)
		{
			$csv['conditions']['valueswap'][$i]['type'] = $arg['type'];
			$csv['conditions']['valueswap'][$i]['old'] = $arg['old'];
			$csv['conditions']['valueswap'][$i]['new'] = $arg['new'];
			++$i;	
		}
	}
	
	$csv['conditions']['valueswap'][$i]['type'] = $type;
	$csv['conditions']['valueswap'][$i]['old'] = $old;
	$csv['conditions']['valueswap'][$i]['new'] = $new;
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option('eci_'.$filename,$csv) )
	{
		eci_mes(__('Value Swap Saved'),'You setup the plugin to replace "'.$old.'" with "'.$new.'" in your '.$type.' values.');
	}
	else
	{
		eci_err(__('No Changes Made'),__('You may have tried to save a Value Swap that has already been setup.'));
	}
}

# Saves Speed Profile Changes
function eci_updatespeed( $s )
{
	eci_err(__('Feature Only Available In Paid Edition'),__('The paid edition allows you to configure
	the time delay between the events that create posts, how many posts are created per event and more
	abilities are coming soon.'));
}

# Creates Test Post Or Page
function eci_designtest( $d,$p )
{
	# REQUIRES POST CREATION FUNCTIONS TO BE COMPLETE
	eci_mes(__('Not Available'),__('This function is to be complete,
	if you would like it to be made available please send your
	interest too webmaster@webtechglobal.co.uk'));
}

# Saves Project Options
function eci_saveprojectoptions( $datemethod,$type,$publish,$comments,$ping,$publisher,$filename,$adopt,$maindesign )
{
	$csv = get_option( 'eci_'.$filename );
	
	$csv['datemethod'] = $datemethod;
	$csv['posttype'] = $type;
	$csv['poststatus'] = $publish;
	$csv['postcomments'] = $comments;
	$csv['postpings'] = $ping;
	$csv['postadopt'] = $adopt;
	$csv['postpublisher'] = $publisher;
	$csv['maindesign'] = $maindesign;
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	if( update_option( 'eci_'.$filename, $csv ) )
	{
		eci_mes( __('Project Options Saved'),__('You may change your options at anytime, even once
				posts have been created. However changes will only apply to existing posts
				after update events are initiated.') );
	}
	else
	{
		eci_err( __('No Changes Saved'),__('Wordpress could not update the options table at this time. Please try again and seek support if this happens again.') );
	}
}
	

# Saves New Or Updates Post Layout Designs From WYSWIYG Editor Page
function eci_savedesign($name,$title,$content,$shortcodedesign,$aioseotitle,$aioseodescription,$wpseokeywords,$wpseofockey,$wpseotitle,$wpseodescription)
{	
	$eci = get_option('eci_set');
	$pro = get_option( 'eci_pro' );
	$lay = get_option( 'eci_lay' );
	$des = get_option('eci_des');

	// prepare the standsrd content data 
	$content = str_replace('<br>','<br />',$content);
	$content = str_replace('<hr>','<hr />',$content);
	
	// remove spaces from end of titles
	$title = rtrim($title);

	// if new design we need to set its id using current time
	if( !isset( $des[$name] ) ){$des[$name]['id'] = time();}
	
	$des[$name]['updated'] = time();
	$des[$name]['title'] = $title;
	$des[$name]['content'] = $content;
	$des[$name]['shortcodedesign'] = $shortcodedesign;
	$des[$name]['seotitle'] = $aioseotitle;
	$des[$name]['seodescription'] = $aioseodescription;
	$des[$name]['wpseokeywords'] = $wpseokeywords;
	$des[$name]['wpseofockeyword'] = $wpseofockey;
	$des[$name]['wpseotitle'] = $wpseotitle;
	$des[$name]['wpseodescription'] = $wpseodescription;

	if( update_option( 'eci_des',$des ) )
	{
		eci_mes( __('Design Saved'),__('Your design has been saved. If you have already used your design to create posts and want the
				changes you just made to reflect in existing posts, you will need to run updating even if you do not have
				any new data. If you require alternative designs similiar to the on you just saved, simply change the name of the
				design, make changes in the WYSIWYG editor then save. By changing the name it will save as a new design and not
				overwrite the previous.') );
	}
	else
	{
		eci_err( __('Failed To Save'),__('This is an error that should be reported. Please seek support from WebTechGlobal.') );
	}
}

# save existing folder path as csv file directory
function eci_savedirectory( $pathname,$pathdir )
{
	// first ensure directory does not already exist - if it does do not create it
	if( is_dir( $pathdir ) )
	{
		$direxists = true;
	}
	elseif( !is_dir( $pathdir ) )
	{
		eci_err( __('Directory Does Not Exist'),__('The directory you submitted does not appear to exist. Please
		check the path you entered. The plugin will not automatically create it using the tool you use. There
		is another button for creating a new directory') );
		$direxists = false;
	}
	
	if( $direxists == true )
	{
		$dirready = false;
		
		if(	is_readable( $pathdir ) )
		{
			eci_mes( __('Read Test Success'),__('The plugin can read the submitted directory.') );
			$dirready = true;
		}
		else
		{
			eci_err( __('Read Test Failed'),__('The plugin could not read the submitted directory, this may be a permissions
					issue. Please change the permission of the directory to a suitable chmod but please put your
					security first and do not make changes without support when you are unsure.') ); 
		}		
		
		if(	is_writable( $pathdir ) )
		{
			eci_mes( __('Write Test Success'),__('The plugin can write too the submitted directory.') );
			$dirready = true;
		}
		else
		{
			eci_err( __('Write Test Failed'),__('The plugin could not write to the submitted directory, this may be a permissions
					issue. Please change the permission of the directory to a suitable chmod but please put your
					security first and do not make changes without support when you are unsure.') ); 
			$dirready = false;
		}
		
		// if directory is ready to use then save it to data source array
		if( $dirready == false )
		{
			eci_err( __('Directory Not Saved'),__('Due to problems accessing the directory, the plugin will not save the submitted path.
					The issue must be resolved before the plugin attempts to load the directories file contents and display
					results on the interface. You may have permissions problems which are easily resolved') );
		}
		elseif( $dirready == true )
		{
			// save the directory to path data array
			$pat = get_option('eci_pat');
			
			if( !$pat || $pat == '' || $pat == ' ' )
			{
				// change name to lowercase
				$name = strtolower( $pathname );
				$pat[$name]['name'] = $pathname;
				$pat[$name]['path'] = $pathdir;
			}
			else
			{
				// loop through existing [ath data and ensure submissions has no duplicates
				$name = strtolower( $pathname );
				$duplicate = false;
				foreach( $pat as $p )
				{
					if( $p == $name )
					{
						eci_err( __('No Changes Saved'),__('The plugin found an existing directory with the same name as 
								you have submitted. Please ensure your directory has not already been setup or change
								the name for your new directory.') );
						$duplicate = true;
					}
					
					if( $p == $pathdir )
					{
						eci_err( __('No Changes Saved'),__('The path you submitted is a match to an existing path already
								saved in the plugins settings. No changes have been made to file directories or
								to the plugins settings data.') );
						$duplicate = true;
					}
				}
				
				// if no duplicates found save the provide path and name as a new directory
				$pat[$name]['name'] = $pathname;
				$pat[$name]['path'] = $pathdir;
			}
			
			update_option( 'eci_pat',$pat );
			
			eci_mes( __('Directory Saved And Ready'),__('All tests returned success, your path has been saved to the data source array
					and any csv files it contains will be displayed on the start page for creating new projects with.') );
		}
	}
}
							
# create a new folder and save path as csv file directory
function eci_createdirectory( $pathname,$pathdir )
{
	// first ensure directory does not already exist - if it does do not create it
	if( is_dir( $pathdir ) )
	{
		eci_mes(__('Directory Already Exists'),__('The plugin does not need to create a new folder in your sites files as the directory already exists.') );
		$direxists = true;
	}
	elseif( !is_dir( $pathdir ) )
	{
		// create the directory
		if( mkdir ( $pathdir, 0750 , false ) )
		{
			eci_mes( __('New Folder Created'),'A new folder was created with permissions 0750, to complete the submitted directory: '.$pathdir.'.' );
			$direxists = true;
		}
		else
		{
			eci_err( __('Failed To Create Directory'),__('The plugin has confirmed that the submitted directory does not exist
					however it could not create the directory. This could be a permissions issue. Please try again then
					seek support or consider creating the required folders manually.') );
			$direxists = false;
		}
	}
	
	if( $direxists == true )
	{
		$dirready = false;
		
		if(	is_readable( $pathdir ) )
		{
			eci_mes( __('Read Test Success'),__('The plugin can read the submitted directory.') );
			$dirready = true;
		}
		else
		{
			eci_err( __('Read Test Failed'),__('The plugin could not read the submitted directory, this may be a permissions
					issue. Please change the permission of the directory to a suitable chmod but please put your
					security first and do not make changes without support when you are unsure.') ); 
		}		
		
		if(	is_writable( $pathdir ) )
		{
			eci_mes( __('Write Test Success'),__('The plugin can write too the submitted directory.') );
			$dirready = true;
		}
		else
		{
			eci_err( __('Write Test Failed'),__('The plugin could not write to the submitted directory, this may be a permissions
					issue. Please change the permission of the directory to a suitable chmod but please put your
					security first and do not make changes without support when you are unsure.') ); 
			$dirready = false;
		}
		
		// if directory is ready to use then save it to data source array
		if( $dirready == false )
		{
			eci_err( __('Directory Not Saved'),__('Due to problems accessing the directory, the plugin will not save the submitted path.
					The issue must be resolved before the plugin attempts to load the directories file contents and display
					results on the interface. You may have permissions problems which are easily resolved') );
		}
		elseif( $dirready == true )
		{
			// save the directory to path data array
			$pat = get_option('eci_pat');
			
			if( !$pat || $pat == '' || $pat == ' ' )
			{
				// change name to lowercase
				$name = strtolower( $pathname );
				$pat[$name]['name'] = $pathname;
				$pat[$name]['path'] = $pathdir;
			}
			else
			{
				// loop through existing [ath data and ensure submissions has no duplicates
				$name = strtolower( $pathname );
				$duplicate = false;
				foreach( $pat as $p )
				{
					if( $p == $name )
					{
						eci_err( __('No Changes Saved'),__('The plugin found an existing directory with the same name as 
								you have submitted. Please ensure your directory has not already been setup or change
								the name for your new directory.') );
						$duplicate = true;
					}
					
					if( $p == $pathdir )
					{
						eci_err( __('No Changes Saved'),__('The path you submitted is a match to an existing path already
								saved in the plugins settings. No changes have been made to file directories or
								to the plugins settings data.') );
						$duplicate = true;
					}
				}
				
				// if no duplicates found save the provide path and name as a new directory
				$pat[$name]['name'] = $pathname;
				$pat[$name]['path'] = $pathdir;
			}
			
			update_option( 'eci_pat',$pat );
			
			eci_mes( __('Directory Saved And Ready'),__('All tests returned success, your path has been saved to the data source array
					and any csv files it contains will be displayed on the start page for creating new projects with.') );
		}
	}
}





# Saves New Category Group
function eci_savegroupcategory( $filename )
{
	$csv = get_option( 'eci_'.$filename );
		
	// loop number of columns - used column ID/counter to retrieve the correct POST data and store in array with ID
	$i = 0;
	
	if( isset( $csv['categories'] ) && $csv['categories'] != '' && $csv['categories'] != ' ' )
	{
		foreach( $csv['categories'] as $c )
		{
			$csv['categories'][$i]['cat1'] = $c['cat1'];
			$csv['categories'][$i]['cat2'] = $c['cat2'];
			$csv['categories'][$i]['cat3'] = $c['cat3'];

			$csv['categories'][$i]['cat1d'] = $c['cat1d'];
			$csv['categories'][$i]['cat2d'] = $c['cat2d'];
			$csv['categories'][$i]['cat3d'] = $c['cat3d'];

			$csv['categories'][$i]['cat1a'] = $c['cat1a'];
			$csv['categories'][$i]['cat2a'] = $c['cat2a'];
			$csv['categories'][$i]['cat3a'] = $c['cat3a'];
						
			++$i;
		}
	}
	
	$csv['categories'][$i]['cat1'] = $_POST['eci_columnid_c1'];
	$csv['categories'][$i]['cat2'] = $_POST['eci_columnid_c2'];
	$csv['categories'][$i]['cat3'] = $_POST['eci_columnid_c3'];

	@$csv['categories'][$i]['cat1a'] = $_POST['eci_applycat_c1a'];
	@$csv['categories'][$i]['cat2a'] = $_POST['eci_applycat_c2a'];
	@$csv['categories'][$i]['cat3a'] = $_POST['eci_applycat_c3a'];
	
	// description part 1
	$csv['categories'][$i]['cat1d'] = $_POST['eci_columnid_c1d'];
	$csv['categories'][$i]['cat2d'] = $_POST['eci_columnid_c2d'];
	$csv['categories'][$i]['cat3d'] = $_POST['eci_columnid_c3d'];
			
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	
	update_option( 'eci_'.$filename,$csv);

	eci_mes(__('Category Group Saved'),__('Your category group has been saved. You can now move to the Conditions stage by pressing the button below.') );		
}


# Deletes Passed Category Group 
function eci_deletecategorygroup( $filename, $groupid )
{
	$csv = get_option( 'eci_'.$filename );
	// loop through category groups until we have the one to be deleted
	$i = 0;
	foreach( $csv['categories'] as $set=>$c )
	{		
		// confirm that the submitted group id exists
		if( $set == $groupid )
		{
			unset( $csv['categories'][$groupid] );
			eci_mes('Category Group '.$groupid.' Deleted','Please remember to add new category groups if required.');
		}
		++$i;
	}
	
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );

	update_option( 'eci_'.$filename,$csv );
}

# Saves Single Category
function eci_savesinglecategory( $filename, $catid )
{
	$csv = get_option('eci_'.$filename);
	$csv['singlecategory'] = $catid;
	if( update_option('eci_'.$filename,$csv) )
	{
		if( $catid == 'NA' )
		{
			eci_mes(__('Single Category Disabled'),__('You selected Not Required and the plugin has
					saved your change. Your project will not assign all new posts to a single category.'));
		}
		else
		{
			eci_mes(__('Single Category Saved'),__('Your project will assign all new posts to the selected category.'));
		}
	}
	else
	{
		eci_mes(__('No Change Required'),__('You did not appear to make any changes, you must have selected the value already saved.'));
	}	
}

# Saves Special Function Page Submission
function eci_savespecials( $filename )
{
	global $wpdb;
	
	$csv = get_option( 'eci_'. $filename );
	$set = get_option('eci_set');
	
	// get a single record for using as examples		
	$record = $wpdb->get_results( 'SELECT * FROM '. eci_wptablename( $filename ) .' WHERE ecipostid IS NULL OR ecipostid = 0 LIMIT 1' );
	
	if( !$record )
	{
		eci_err(__('Failed To Retrieve Test Data'),__('The plugin needs a single record to be imported to the project database table. The records data is tested with various parts of your configuration including Special Functions. Please go to First Data Import stage and initiate a data import.'));
	}
	else
	{
		// loop number of columns - used column ID/counter to retrieve the correct POST data and store in array with ID
		$i = 0;
		
		// put all post values into data array
		$csv['specials']['col']['madetags_col'] = $_POST['eci_spec_madetags'];
		$csv['specials']['col']['thumbnail_col'] = $_POST['eci_spec_thumbnail'];
		$csv['specials']['col']['cloaking1_col'] = $_POST['eci_spec_cloaking1'];
		$csv['specials']['col']['permalink_col'] = $_POST['eci_spec_permalink'];
		$csv['specials']['col']['dates_col'] = $_POST['eci_spec_dates'];
		$csv['specials']['col']['tags_col'] = $_POST['eci_spec_tags'];
		$csv['specials']['col']['excerpt_col'] = $_POST['eci_spec_excerpt'];
		$csv['specials']['col']['catsplitter_col'] = $_POST['eci_spec_catsplitter'];
		$csv['specials']['col']['catsplitter2_col'] = $_POST['eci_spec_catsplitter2'];
		
		// preset the switch too Off
		$csv['specials']['state']['madetags_col'] = 'Off';
		$csv['specials']['state']['thumbnail_col'] = 'Off';
		$csv['specials']['state']['cloaking1_col'] = 'Off';
		$csv['specials']['state']['permalink_col'] = 'Off';
		$csv['specials']['state']['dates_col'] = 'Off';
		$csv['specials']['state']['tags_col'] = 'Off';
		$csv['specials']['state']['excerpt_col'] = 'Off';
		$csv['specials']['state']['catsplitter_col'] = 'Off';
		$csv['specials']['state']['catsplitter2_col'] = 'Off';
		
		foreach( $record as $data )
		{			
			// Pre-Made Tags 
			if( $csv['specials']['col']['madetags_col'] != 'NA' )
			{ 
				$csv['specials']['state']['madetags_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['madetags_col']);
				eval('$value = $data->$col;');
				eci_mes('Pre-Made Tags Example',$value);
			}
			
			// Featured Image
			if( $csv['specials']['col']['thumbnail_col'] != 'NA' )
			{ 
				$csv['specials']['state']['thumbnail_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['thumbnail_col']);
				eval('$value = $data->$col;');
				eci_mes('Featured Image Example','URL: '.$value.'<br /><br /><img src="'.$value.'" width="200" height="200" />');
			}
			
			// cloaking
        	if( ECI_EFF_HOOKSFILE )
        	{
				if( $csv['specials']['col']['cloaking1_col'] != 'NA' )
				{ 
					$csv['specials']['state']['cloaking1_col'] = 'On';
					$col = eci_cleansqlcolumnname($csv['specials']['col']['cloaking1_col']);
					eval('$value = $data->$col;');
					eci_mes('URL Cloaking Example','URL Data: '.$value.'<br /><br />Example Cloak Only: '. get_bloginfo( 'url' ) . '?viewitem=12345&section=2');
				}
        	}
			
			// permalink 1
			if( $csv['specials']['col']['permalink_col'] != 'NA' )
			{ 
				$csv['specials']['state']['permalink_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['permalink_col']);
				eval('$value = $data->$col;');
				eci_mes('Parmalink Example','PermalinkData: '.$value.'<br /><br />Example URL: '. get_bloginfo( 'url' ) . $value);
			}

			// dates
			if( $csv['specials']['col']['dates_col'] != 'NA' )
			{ 
				$csv['specials']['state']['dates_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['dates_col']);
				eval('$value = $data->$col;');
				
				// if string to time could not be done, output some help information
				if ( ( $timestamp = strtotime( $value ) ) === false ) 
				{
					eci_err( __('Date Format Problem'),'It appears the date value tested could not be converted for using in Wordpress. Try replacing any
							slashes used in your dates with hyphens instead. The specific problem is that when the plugin attempts to use the strtotime 
							php function, the function returns false. Here are sources which explain more about this procedure.<br /><br />							
							<a href="http://www.webtechglobal.co.uk/blog/help/eci-tutorial-date-formatting" target="_blank">Go to WebTechGlobals ECI Date Format help page and leave a comment for quick support</a>' );;
				} 
				else 
				{
					eci_mes(__('Date Example & Formatting Test'),'
					Your Data Value: '.$value.'<br /><br />
					Date Wordpress Formated: '.date("Y-m-d H:i:s", $timestamp).'<br /><br />
					Extended Test: '.date('l dS \o\f F Y h:i:s A', $timestamp).'<br /><br />
					PHP strtotime: '.$timestamp.'<br /><br />');
				}
			}
			
			// tags
			if( $csv['specials']['col']['tags_col'] != 'NA' )
			{ 
				$csv['specials']['state']['tags_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['tags_col']);
				eval('$value = $data->$col;');
				$value = eci_createtags( $value, $set['tagschars'], $set['tagsnumeric'], $set['tagsexclude'] );
				eci_mes('Generated Tags Example',$value.' (based on any changes you have made in the settings page');
			}
			
			// excerpt
			if( $csv['specials']['col']['excerpt_col'] != 'NA' )
			{ 
				$csv['specials']['state']['excerpt_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['excerpt_col']);
				eval('$value = $data->$col;');
				$value = eci_createexcerpt( $value,150 );
				eci_mes('Generated Excerpt Example',$value);
			}
			
			// category splitter ( forward slash / )
			if( $csv['specials']['col']['catsplitter_col'] != 'NA' )
			{ 
				$csv['specials']['state']['catsplitter_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['catsplitter_col']);
				eval('$value = $data->$col;');
					
				// remove spaces from string
				$value = str_replace(' ','',$value);
						
				// explode resulting string
				$value = explode('/',$value,3);
						
				$cats = '';
				$count = 0;
				foreach( $value as $cat )
				{
					if( $count != 0 )
					{
						$cats .= ',';
					}
							
					$count++;
							
					$cats .= $cat;
				}
			
				eci_mes('Split Columns Example Using (/)',$cats);
			}	
			
			// category splitter 2 ( splitter character = > )
			if( $csv['specials']['col']['catsplitter2_col'] != 'NA' )
			{ 
				$csv['specials']['state']['catsplitter2_col'] = 'On'; 
				$col = eci_cleansqlcolumnname($csv['specials']['col']['catsplitter2_col']);
				eval('$value = $data->$col;');
					
				// remove spaces from string
				$value = str_replace(' ','',$value);
						
				// explode resulting string
				$value = explode('>',$value,3);
						
				$cats = '';
				$count = 0;
				foreach( $value as $cat )
				{
					if( $count != 0 )
					{
						$cats .= ',';
					}
							
					$count++;
							
					$cats .= $cat;
				}
						
				eci_mes('Split Columns Example Using (>)',$cats);
			}				
			
			++$i;
		}

		$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
		$result = update_option( 'eci_'.$filename,$csv );
		
		if( $result )
		{
			eci_mes( __('Special Functions Saved'),__('Your changes for Special Functions 
			setup has been saved. If you used any of the functions available you
			should see samples for your selections below.') );	
		}
		else
		{
			eci_err(__('No Changes Made'),__('Your configuration for the Special Functions form did not save. This is usually because no changes were made to the form.'));
		}
	}
}


# Saves Speed Profile For Data Import And Offers Further Import Actions
function eci_dataimport_speedprofile( $speed,$filename )
{
	$pro = get_option('eci_pro');
	
	$pro[$filename]['speed'] = $speed;
	
	update_option('eci_pro',$pro);

	// create import button
	$importbutton = '<br /><br /><input class="button-primary" type="submit" name="eci_datatransfer_2_submit" value="Import" /><br />';
	
	// get speed profiles type to decide what interface is required
	$spe = get_option('eci_spe');
	
	// get speed profiles type, use speed profiles name to access array
	$speed = $spe[$speed]['type'];

	// output display based on speed profile selected and set further values
	if($speed == 'fullspeed')
	{
		// display a single button to begin the import straight away
		eci_mes(__('Full Speed Import Saved'),'You can begin importing by pressing the Import button below.
		The plugin will process your csv file until every row has been imported.'.$importbutton);
	}
	elseif( $speed == 'manualevents' )
	{
		// display single button to begin import, re-display button for multiple events
		eci_mes(__('Manual Events Import Speed Saved'),'Your projects data import has been setup for
				multiple events importing. To import 100% of your data, you will need to
				manually action each import event.'.$importbutton);
	}
	elseif( $speed == 'spreadout' )
	{
		// display message only, button not required
		eci_mes(__('Spreadout Data Import Speed Saved'),__('To be complete, show the next 10 scheduled
				events according to set delay, indicate number of records to import on that 
				delay') );
		
		############## indicate schedule set 
		
		############## display next event time 
		
		######################## set project and speed profile too active
	}
	elseif( $speed == 'blog2blog' )
	{
		// display button to activate full speed transfer - confirm receiving blog
		eci_mes('Blog2Blog Import Saved','Message to be complete');
	}
}

# Delete Speed Profile
function eci_deletespeed( $speedname )
{
	$spe = get_option('eci_spe');
	
	if( $spe && $spe != '' )
	{
		// ensure the speed profile is not one of the defaults, we don't allow them to be deleted
		if( $speedname == 'fullspeed' || $speedname == 'events' || $speedname == 'spreadout' || $speedname == 'blog2blog' )
		{
			eci_err(__('Not Deleted'),__('You cannot delete the default speed profiles; Full Speed, Manual Events, Spreadout or Blog2Blog'));
		}
		else
		{
			unset( $spe[$speedname] );
		}
	}		 
	
	update_option('eci_spe',$spe);
	
	// get array again and confirm the delete happened
	$spe = get_option('eci_spe');
	
	if( isset( $spe[$speedname] ) )
	{
		eci_err(__('Failed To Delete Speed Profile'),__('Wordpress did not allow the Speed Profile data array to be updated. The submitted Speed Profile has not been deleted,please try again.'));
	}
	else
	{
		eci_mes('Speed Profile '.$speedname.' Deleted',__('The speed profile has been removed from the Speed Profile data array.') );
	}
}

function eci_updateprojectlist( $filename )
{
	$pro = get_option('eci_pro');
	
	$saved = true;
	
	$counter = 0;

	// loop through projects 
	if( $pro )
	{
		foreach( $pro as $key=>$file )
		{ 
			// if match to submitted csv file found return false
			if( $key == $filename )
			{
				$saved = false;
			}
			++$counter; 
		}	
		
		if( $saved == true )
		{
			update_option('eci_pro',$pro);
		}
	}
	else
	{
		$saved = false;
	}
	
	// returning false indicates no save made because the filename exists already
	return $saved;
}

/**
 * Save custom post type in use submission on configuration
 */
function eci_posttypes_inuse_save($filename)
{
	$csv = get_option('eci_'.$filename);
	
	if( !isset($_POST['posttype']) )
	{
		eci_mes('No Post Types Selected','It appears that you did not put a tick in any boxes. You must tick at least one box. If you are not sure, put a tick in the box for post, this is the standard Wordpress post type.');
	}
	else
	{
		// delete existing post type in use settings
		if( isset($csv['posttypes'])){unset($csv['posttypes']);}
		
		foreach( $_POST['posttype'] as $pt )
		{
			$csv['posttypes'][] = $pt;
		}
		
		if( update_option('eci_'.$filename,$csv) )
		{
			eci_mes('Post Types In Use Saved','The post types you wish to use can now be used to configure other related settings for your project');
		}
		else
		{
			eci_err('No Changes Made','There was no change made to your projects post types in use compared to what was saved already');
		}
	}
}

/**
 * Saves condition form submission for pairing designs to post types
 * @param base name $filename
 */
function eci_save_posttypedesigns($filename)
{
	$csv = get_option('eci_'.$filename);
	
	// $key should be the same integer value as used as $id in the interface function to display design menus
	foreach($csv['posttypes'] as $key=>$pt)
	{
		$csv['conditions']['posttypes'][$pt] = $_POST['eci_design'.$key.''];
	}
	
	if(update_option('eci_'.$filename,$csv))
	{
		eci_mes('Post Type Designs Saved','Your changes have been made to the designs being applied to each post type');
	}
	else 
	{
		eci_err('No Changes Required','It appears there are no actual changes made to the existing settings for post type designs');
	}
}

/**
 * Saves submission of post type configuration form on configuration page
 * @param base $filename
 * @global $wpdb
 */
function eci_save_posttypefilterconfig($filename)
{
	echo '<p><strong>Not ready for use, thank you for your patience. Please check on the latest beta version, the plugin is updated weekly</strong></p>';
}

function eci_posttype_changestatus($filename,$newstatus)
{
	$csv = get_option('eci_'.$filename);
	if($newstatus == 'Activate')
	{
		$csv['posttypestatus'] = 'Active';
		$a = 'Activated';
	}
	elseif($newstatus == 'Disable')
	{
		$csv['posttypestatus'] = 'Disabled';
		$a = 'Disabled';
	}
	if(update_option('eci_'.$filename,$csv))
	{
		eci_mes('Post Type Configuration '.$a,'Post type filtering has been '.$a.'. Your selection of multiple post types will still effect the Post Type Design condition if you activate that Condition');
	}
	else
	{
		eci_err('Post Type Configuration Not '.$a,'Post Type Configuration for your project may have already been '.$a.'');
	}
}

function eci_save_categorypairing($filename)
{	
	$csv = get_option('eci_'.$filename);
	// loop through number of possible pairings
	$i = 0;
	
	if(isset($csv['categoriespaired'])){unset($csv['categoriespaired']);}
	
	while($i != $_POST['eci_pairedcategories'])
	{	
		// do not save NA values
		if($_POST['eci_blogcategory_'.$i] != 'NA')
		{
			// data value can only be used once so it becomes a key with our ID assigned
			// the assigned ID to each data category value can be used to get the blog category it belongs too
			$csv['categoriespaired']['data'][$_POST['eci_datacategory_'.$i]] = $i;	
			
			// the blog category post value is the term id not the term name
			// during post creation we need to make the data category match, get our $i ID then use that to
			// get our term ID within this blog array 
			$csv['categoriespaired']['blog'][$i] = $_POST['eci_blogcategory_'.$i];
		}
		++$i;
		
		// loop limit
		if($i > 500){break;}
	}
	
	if(update_option('eci_'.$filename,$csv))
	{
		eci_mes(__('Category Data To Blog Pairing Saved'),__('Please run small tests on your setup and
		confirm that you have done it correct, you may want to backup your database for quick
		restoration later.'));
	}
	else
	{
		eci_err(__('Category Data To Blog Pairing Failed To Save'),__('Please try again then seek support
		if you continue to experiance problems.'));
	}
}

function eci_save_taxonomy($filename)
{
	if(!isset($_POST['eci_taxid']) && $_POST['eci_taxid'] != 0)
	{
		eci_err('No Taxonomies To Save','A submission to save taxonomy pairing was done but there does not appear to be any taxonomies.');
	}
	else
	{
		$csv = get_option('eci_'.$filename);
		
		// delete the old array first
		if(isset($csv['taxonomies'])){unset($csv['taxonomies']);}
		
		// use the counted number of taxonomy to loop through each row of objects on the form
		for ($i = 0; $i < $_POST['eci_taxid']; $i++) 
		{
			// do not save NA values
			if($_POST['col_'.$i] != 'NA')
			{
				// store post type as first array key then the column as array key and array value is taxonomy name
				// during post creation we use the Current Column to any taxonomy set then using that column we extract data value from record
				$csv['taxonomies'][$_POST['eci_posttype_'.$i]][$_POST['col_'.$i]] = $_POST['eci_tax_'.$i];				
			}
		}
		
		if(update_option('eci_'.$filename,$csv))
		{
			eci_mes('Taxonomy Settings Saved','The plugin will populate your taxonomy during post creation events.');
		}
		else 
		{
			eci_err('Taxonomy Settings Not Saves','No changes were made to the form so no save was required.');
		}
	}
}

?>