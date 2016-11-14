<?php
/**
 * Used for multi file projects only, creates an update query for none parent files and is counted as a partial insert
 * @param array $record (row from csv file into array)
 * @param array $columns (array of csv file column titles)
 * @param string $filename
 * @param numeric $id (id of the record or custom id that we're adding more data too)
 * @param string $method (byid or byorder, if byorder then $id is the eciid else it is the users own csv file id to be used)
 */
function eci_sqlupdate_multifileinsert($record,$columns,$filename,$id,$method,$pro )
{			
	$csv = get_option('eci_' . $filename);
	
	$col = 0;
	
	// start SET data part of query
	$set = ' SET ';
	
	// start where part of query
	// if method is byid we need to check if the current column is the id column and apply its id to query
	//if( $method == 'byid' && $pro[]['multifilebyid'] == $key )// CURRENTLY NOT IN USE
	if( $method == 'byorder' )
	{
		$where = 'WHERE eciid = '.$id;// the giving $id should be row count which should match eci record id
	}
	
	foreach( $columns as $key=>$column )
	{						
		// apply encoding if required
		if( $csv['importencoding'] == 'UTF8Standard' )
		{
			$record[$col] = utf8_encode( $record[$col] );
		}
		
		###### IS STRLEN THE CORRECT FUNCTION TO USE HERE   ???????????

		// is the users data wrapped in double quotes
		if( strlen( $record[$col] ) == '"' && $string[strlen($string)-1] == '"' )
		{
			// if we have already added a column add a comma
			if( $col > 0 )
			{
				$set .= ',';
			}
		
			// apply single quotes if data is wrapped in double quotes already
			$set .= eci_cleansqlcolumnname($column) ." = '". $record[$col] ."'";
		}
		elseif( strlen( $record[$col] ) == "'" && $string[strlen($string)-1] == "'" )
		{
			// if we have already added a column add a comma
			if( $col > 0 )
			{
				$set .= ',';
			}
			
			// apply double quotes if data is wrapped in single quotes already
			$set .= eci_cleansqlcolumnname($column) .' = "'. $record[$col] .'"';
		}
		else
		{
			// if we have already added a column add a comma
			if( $col > 0 )
			{
				$set .= ',';
			}
			
			// default is double quotes around data - for values that do not have any quotes mostly
			$set .= eci_cleansqlcolumnname($column) .' = "'. $record[$col] .'"';
		}	
		
		++$col;
	}	
	
	// put together parts of query
	$q = 'UPDATE ' . eci_wptablename( basename($pro[$filename]['multifileset'][0]) ) . $set . $where;	

	return $q;
}

# Runs A Data Import Event - $total will equal FALSE or value to force a stop
# 1. Schedule is not advanced in time during this function, that is handled by scheduling hook function
# 2. Updating: check for a project being ready for updating before calling this function, unique key etc
function eci_dataimport( $filename, $output, $event, $set )
{
	// begin script timer
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    $scriptstarttime = $mtime;
   
	eci_log( 'Data Import Started','Data '.$event.' started',
	'Data import was requested and attempted. The results will be entered in further log entries.'
	,$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );eci_pearcsv_include();global $wpdb;$pro = get_option('eci_pro');$csv = get_option( 'eci_' . $filename );$spe = get_option('eci_spe');$titlearray = eci_gettitlearray($csv,$pro,$filename);$label = 'Default';$importbase = 2123;$type = 'fullspeed';$import = $importbase - 123;
    if(isset($_POST['eci_datatransferonerecord_submit'])){$import = 1;}$stamp = eci_filetime( $filename,$pro,$csv,$set );$progress = eci_progress( $filename );
	
	// used to compare against event limit on this event only
	$recordsprocessed = 0;

	// records actually looped - on this event only
	$recordslooped = 0;
	
	// csv rows imported to create database record - used for interface output only
	$updatesuccess = 0;
	$updatefailed = 0;
	$importsuccess = 0;
	$importfailed = 0;
	$updateresult = 0;

	// use pear to read csv file
	$conf = File_CSV::discoverFormat( $pro[$filename]['filepath'] );
	
	// apply stored seperator
	$conf['sep'] = $csv['format']['seperator'];		
	$conf['quote'] = $csv['format']['quote'];
		
	// set limit
	if( $event == 'import' )
	{
		$max = $import;
	}
	elseif( $event == 'update' )
	{
		$max = $update;
	}
	
	// loop through records until speed profiles limit is reached then do exit
	while ( ( $record = File_CSV::read( $pro[$filename]['filepath'], $conf ) ) && $recordsprocessed < $max ) 
	{		
		// skip first record - also skip done rows by using more or equal too
		if( $recordslooped != 0 && $recordslooped > $progress )
		{			
			// count actual records processsed after progress total looped through
			++$recordsprocessed;
	
			$profilecolumnid = 0;
			
			// if a record was found in project table matching current processed record, add it to failed count if duplicates are not allowed
			// on an update, this means the new csv files row has not changed and so no update needs to be done
			if( isset( $selectfound ) && $selectfound != false && $set['allowduplicaterecords'] != 'Yes' )
			{
				++$pro[$filename]['rowsinsertfail'];
				++$importfailed;
			}
			else
			{				
				// if this is a multifile project we don't use an INSERT query if it is not the parent file being imported
				if( $pro[$filename]['filesettype'] == 'multifile' && $pro[$filename]['multifilelev'] != 'parent' )
				{
					// $recordslooped acts as the byorder ID, if method is byid we need use the id within the record
					// byid not currently active
					// we need to pass only the current csv files own titles so that the record array can be accessed properly
					$finalquery = eci_sqlupdate_multifileinsert($record,$csv['format']['titles'],$filename,
					$recordslooped,$pro[$filename]['multifilemethod'],$pro);
				}
				else// do a normal insert even if this is a multifile project but this is the parent file
				{
					$sqlmiddle = eci_sqlinsert_middle($record,$titlearray,$stamp['current'],$csv);
					$finalquery = $csv['sql']['insertstart'] . $sqlmiddle;
				}
								
				$insert = $wpdb->query( $finalquery );

				// increase statistics for success or fail
				if( $insert === false )
				{
					eci_adminmes( __('SQL Query Failed'),'The plugin attempted to run an SQL query
					but Wordpress returned false. Please investigate this before running further data import events. The 
					SQL query is below:<br /><br />'.$finalquery.'','err' );
				}
				elseif( $insert === 0 )
				{
					++$pro[ $filename ]['rowsinsertfail'];
					++$importfailed;
				}
				elseif( $insert === 1 )
				{
					++$pro[ $filename ]['rowsinsertsuccess'];
					++$importsuccess;
				}	

				unset( $insertquery );
				
			}
		}
		
		// check timer
		$mtime = microtime(); 
   		$mtime = explode(" ",$mtime); 
   		$mtime = $mtime[1] + $mtime[0]; 
   		$endtime = $mtime; 
   		$scripttotaltime = ($endtime - $scriptstarttime); 
   		if($scripttotaltime > ECIMAXEXE){$stopscript = true;}else{$stopscript = false;}
   
		// if total records processed hits event limit ( $import ) then exit loop
		if( $stopscript == true || $recordsprocessed >= $import )
		{
			// if were breaking due to hitting limit we will log it
			if($stopscript == true)
			{
				eci_log( 'Execution Limit','ECI reached execution limit on data import',
				'ECI reached the execution limit of '.ECIMAXEXE.' seconds during data import and stopped',
				$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );					
			}
			
			break;
		}
			
		++$recordslooped;
		
	}// end of while loop

	// now update project progress counters
	++$pro[ $filename ]['events'];
	
	// update csv array to save sql queries
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	update_option( 'eci_' . $filename, $csv );
	update_option('eci_pro',$pro);

	eci_log( 'Import','Imported:'.$importsuccess.' Import Failed:'.$importfailed.' Updated:'.$updatesuccess.' (paid edition only) Updated Failed:'.$updatefailed.'',
	'Imported:'.$importsuccess.' Import Failed:'.$importfailed.' Updated:'.$updatesuccess.' (paid edition only) Updated Failed:'.$updatefailed.'',
	$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	

	// on 100% success output result
	if( $output )
	{
		$outputmes = '
		Records Imported Successfully: '. $importsuccess .'<br />
		Records Import Failed: '. $importfailed .'<br />
		Records Updated Successfully: (paid edition only)<br />
		Records Update Failed: (paid edition only)<br />
		<br />
		Total Imported For Project: '.$pro[ $filename ]['rowsinsertsuccess'].'';
		
		// indicate if records imported match csv files lasted count (deduct 1 which is the header)
		if( $pro[ $filename ]['rowsinsertsuccess'] == $csv['format']['rows'] - 1 )
		{
			$outputmes .= '  <strong>(imported record count matches csv row count)</strong>';
		}

		// if this is a multifile project we need to add a button for each file in the set
		$buttons = '';
		if( isset( $pro[$pro['current']]['filesettype'] ) && $pro[$pro['current']]['filesettype'] == 'multifile' && isset( $pro[$pro['current']]['multifileset'] ) )
		{			
			$buttons .=  '<p>This is a multiple file project, your import counters should match before creating posts. You must also
			import each file in the order below or you will upset the progress counters.</p>';
			$buttons .= '<table class="widefat post fixed">';
			$buttons .= '<tr><td width="230"><strong>Import Buttons</strong></td><td><strong>Records Imported</strong></td></tr>';
							
			// loop through the multifileset paths, getting base filename for adding to form buttons
			foreach( $pro[$pro['current']]['multifileset'] as $path )
			{
				$buttons .= '
				<tr>
					<td>
						<input class="button-primary" type="submit" name="eci_datatransfer_submit" value="'.basename($path).'" />
					</td>
					<td>'.$pro[basename($path)]['rowsinsertsuccess'].'</td>
				</tr>
				';
			}
			
			$buttons .= '</table>';
		}
		else
		{
			$buttons = '<input class="button-primary" type="submit" name="eci_datatransfer_submit" value="'.$filename.'" />';
		}

		// output form with further actions if there is still data to be used else display complete message
		if( $pro[ $filename ]['rowsinsertsuccess'] != $csv['format']['rows'] - 1 )
		{
			$outputmes .= '
			<h3>More Actions For '.$filename.'</h3>
				<form method="post" name="eci_importstage_form" action="">  
					<input name="eci_filename" type="hidden" value="'.$filename.'" />
					<input name="eci_filesettype" type="hidden" value="'.$pro[$filename]['filesettype'].'" />
					Encoding To Apply:
					<select name="eci_encoding_importencoding" size="s">
						<option value="None">None</option>
						<option value="UTF8Standard">UTF-8 Standard Function</option>
						<option value="UTF8Full">UTF-8 Full (extra processing)</option>
					</select>
					<br />
					<h4>Import More Data From...</h4>
					'.$buttons.'
				</form><br />';
		}
		elseif( $pro[ $filename ]['rowsinsertsuccess'] == $csv['format']['rows'] - 1 )
		{
			$outputmes .= '<strong>All CSV rows imported</strong>';
			$outputmes .= '<p>All rows have been processed. Any rows indicating as a failure can be duplicate records (if duplicate prevention
			is active) or it may be other conditions setup.</p>';
		}
		
		eci_mes( 'Data '.$event.' event complete for '.$filename.'',$outputmes );
	}
}

# Builds Select Query - Selects Unique Key Column Values - ($type = standardselect,partofupdate )
function eci_sqlselect( $record, $filename, $type )
{
	$csv = get_option('eci_' . $filename);
	
	$col = 0;
	
	// start where part of query
	$where = ' WHERE ';
	
	// count how many keys are used
	$k = 0;
	$w = 0;
	
	foreach( $csv['format']['titles'] as $column )
	{		
		// if select is being done as part of an update event, we only want to select giving unique key columns
		// this helps us to get the record matching the unique key by the key only
		if( $type == 'partofupdate' )
		{
			// is this column part of unique key ?
			if( isset( $csv['updating']['key1'] ) && $csv['updating']['key1'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
			elseif( isset( $csv['updating']['key2'] ) && $csv['updating']['key2'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
			elseif( isset( $csv['updating']['key3'] ) && $csv['updating']['key3'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
			else{$key = false;} 
			
			// if column is 2nd,3rd or more then apply comma
			if( $k > 0 )
			{
				$select .= ',';
			}			
		}

		// if part of unique column or $type is whereall add the column and data to where part of query
		if( isset( $key ) && $key == true || $type == 'whereall' )
		{
			if( $w > 0 )
			{
				$where .= ' AND ';
			}
			
			// does user want mysql real escape string used here, true by default
			if(isset($set['selectescapedata']) && $set['selectescapedata'] == 'Yes' || !isset($set['selectescapedata']))
			{
				$where .= eci_cleansqlcolumnname( $column ) ." = '". mysql_real_escape_string( $record[$col] ) ."'";
			}
			else
			{
				$where .= eci_cleansqlcolumnname( $column ) ." = '". mysql_real_escape_string( $record[$col] ) ."'";				
			}
			
			++$w;
		}
		
		++$col;
	}	
	
	// if this is a standard select getting specific columns or a where all query (used for finding matching row to record) 
	if( $type == 'standardselect' || $type == 'whereall' )
	{
		$select = 'SELECT * ';
	}
	else
	{
		$select = ' SELECT '.$select;	
	}

	// put together parts of query
	$q = $select . ' FROM '. $csv['sql']['tablename'] . $where;
	
	return $q;	
}

// builds part of sql query that holds data - requires $record value from open csv file and $insertquery_start
function eci_sqlinsert_middle( $record, $totalcolumns,$filetime,$csv )
{		
	$insertquery = '(NOW(),';
	
	$columnid = 0;
	
	foreach( $record as $data )
	{			
		// does user want utf8 encoding on import
		if( $csv['importencoding'] == 'UTF8Standard' )
		{
			$data = utf8_encode( $data );
		}
		
		if( $columnid > 0 )
		{
			$insertquery .= ",";
		}

		// does user want mysql real escape string used here, true by default
		if(isset($set['insertescapedata']) && $set['insertescapedata'] == 'Yes' || !isset($set['insertescapedata']))
		{
			$insertquery .= '"' . mysql_real_escape_string( $data ) . '"';
		}
		else
		{
			$insertquery .= '"' . mysql_real_escape_string( $data ) . '"';				
		}
		
		++$columnid;
	}
	
	$insertquery .= ')';
	
	return $insertquery;
}

# Drops Project Table - interface actioned with message output
function eci_deletetable( $filename )
{
	global $wpdb;

	$set = get_option( 'eci_set' );
	
	// get project table name
	$table_name = eci_wptablename( $filename );
	
	// drop existing table and display message
	$query = 'DROP TABLE '. $table_name .'';
	
	// run drop table query
	$result = $wpdb->query( $query );
	
	// 0 = success anything else is error
	if( $result === 0 )
	{		
		eci_log( __('Table Deleted'),'The table named '.$table_name.' was deleted',false,$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	
		eci_mes('Table '.$table_name.' Deleted','All data and the table itself has been dropped. If you need to you may create the table again manually in the same way you deleted it.');
		return true;
	}
	else
	{
		eci_mes('Failed To Delete Table '.$table_name.'',__('The plugin could not delete your project table, please try again then seek support.'));
		return false;
	}	
	
}
	
# Create Project Database Table
function eci_createtable( $filename,$set )
{
	global $wpdb;

	eci_log( __('Created Table'),'Table created for '.$filename.'',
	'A new database table was created for project '.$filename.'. Your projects data is stored there first before being used to create posts.',
	$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	

	$result = false;
	
	$existsalready = false;
	
	// get project table name
	$table_name = eci_wptablename( $filename );
	
	// get csv profile
	$csv = get_option( 'eci_' . $filename );
	
	$wptables = eci_get_tables();
	
	$count = NULL;
	
	while ($row = mysql_fetch_row($wptables)) 
	{
		if($row[0] == $table_name)
		{
			// build select query to test tables existance and count alll records
			$selectcount = "SELECT COUNT(*) FROM " . $table_name;
			$count = $wpdb->get_var( $wpdb->prepare( $selectcount ) );			
		}
	}
		
	// get plugin settings
	$set = get_option('eci_set');
		
	// automatically drop table if existing records less than acceptable drop
	if( $count > $set['acceptabledrop'] && $count != NULL )
	{
		eci_err(__('Existing Table Encountered'),'Your project file matches an existing
		table in your database and your acceptable record loss setting is '. $set['acceptabledrop'] .' so
		the plugin could not automatically delete the table because there are '. $count .' records in the table. You
		must decide to either delete the table manually using the Tools page or continue adding data to it by using the
		forms below.<br /><br />
		Please remember that you can change your Acceptable Records Loss number on the Settings page under Interface Configuration. If you
		are running tests you can increase the number to allow automatic delete at this point, everytime but be carefull for real projects.');
		
		$existsalready = true;
	}
	elseif( $count <= $set['acceptabledrop'] && $count != NULL )
	{	
		// drop existing table and display message
		$query = 'DROP TABLE '. $table_name .'';
	
		// run drop table query
		$result = $wpdb->query( $query );
		
		eci_log( __('Table Deleted'),
		'Table '.$table_name.' dropped',
		'Database table named '.$table_name.' was deleted after a new project was created using the same name. Your acceptable drop limit allowed the existing table to be deleted automatically',
		$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	
		eci_err('Existing Table Deleted','Your project file matched an existing table in your database but has been deleted. It was deleted because your acceptable record loss setting is '. $set['acceptabledrop'] .' and the table had '. $count .' records.');
	}
	
	// attempt to create table if $existsalready = false
	if( $existsalready === false )
	{
		$table = "CREATE TABLE `". $table_name ."` (
		`eciid` int(10) unsigned NOT NULL auto_increment,
		`ecipostid` int(10) unsigned default NULL COMMENT '',
		`eciinuse` int(10) unsigned default NULL COMMENT '',		
		`eciconsync` int(10) unsigned default NULL COMMENT '',
		`eciapplied` datetime NOT NULL COMMENT '',
		`ecifiletime` datetime NOT NULL COMMENT '',";
		
		$columnid = 0;
		
		$pro = get_option('eci_pro');
		
		// establish the list of columns to be used - if multisite project we use a different arrAY
		$titlearray = eci_gettitlearray( $csv, $pro, $filename );
				
		// loop through csv column titles, each one built as table column
		$usedtitles = array();
		foreach( $titlearray as $column )
		{			
			++$columnid;
			
			// we need to prepare column names (no spaces or special characters etc)
			$sqlcolumn = eci_cleansqlcolumnname($column);	
			
			// ensure $sqlcolumn is not already in the $usedtitles array - if it is we do not use it
			// the user is warned about their sql duplicates on creating a project
			if( !in_array($sqlcolumn, $usedtitles ) )
			{						
				$usedtitles[] = $sqlcolumn;
				$table .= "`" . $sqlcolumn . "` text default NULL COMMENT '',";
			}
		}
		
		// end of table
		$table .= "PRIMARY KEY  (`eciid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Table created by Easy CSV Importer';";
				
		$createresult1 = $wpdb->query( $table );
		
		if( $createresult1 === false )
		{
			eci_log( __('Table Failed'),
			'Table '.$table_name.' could not be created',
			'A database table named '.$table_name.' could not be created. This is required to store your projects data in. Please investigate this issue.',
			$filename,$set,'Critical',__LINE__,__FILE__,__FUNCTION__ );	
			
			eci_err('Failed To Create Table','Your project database table failed to be created. Most of the time this is due
			to csv column titles including spaces, special characters or being too long. If you think this could be the case
			please try to make them simple names with a single word.');
		}
		else
		{
			eci_log( __('Table Created'),
			'Table name '.$table_name.'',
			'A database table named '.$table_name.' was created, your projects data will be stored here first before being used to create posts.',
			$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );	
			eci_mes('Created Project Database Table','Your project database table name is:'.$table_name.'');
		}
	}
}		

# Builds SQL Insert Query Start Based On CSV File Column Names
# Saves To CSV File Profile To Help Avoid Building Later
/**
 * Builds sql insert query, 
 * @param sql table name $table
 * @param csv file $filename
 */
function eci_sqlinsert_start( $table,$filename )
{
	$csv = get_option('eci_' . $filename);
	
	// tablename may not be current $filename project, it could be a parent table of a multifileproject
	$insertquery = "INSERT INTO `" . $table . "` (`ecifiletime`,";
	
	$columnid = 0;
	
	foreach( $csv['format']['titles'] as $column )
	{			
		++$columnid;
	
		// we need to prepare column names (no spaces or special characters etc)
		$sqlcolumn = eci_cleansqlcolumnname($column);	

		$insertquery .= "`" . $sqlcolumn . "`";
		
		if( $csv['format']['columns'] != $columnid ){ $insertquery .= ","; }// apply comma until last column
	}
	
	$insertquery .= ') VALUES ';
	
	// save query start to csv profile
	$csv['sql']['insertstart'] = $insertquery;
	$csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
	update_option( 'eci_' . $filename, $csv );
}

/**
 * counts total records in giving project table
 * @param filename $filename
 * @return 0 on fail or no records or the number of records in table
 */
function eci_counttablerecords( $filename )
{
	global $wpdb;
	
	$pro = get_option( 'eci_pro' );
	
	$query = "SELECT COUNT(*) FROM ". eci_wptablename( $filename ) . ";";
	
	$records = $wpdb->get_var( $query );
	
	if( $records )
	{
		$pro['records'] = $records;
		update_option( 'eci_pro', $pro );
		return $records;
	}
	else
	{		
		return '0';
	}	
}

# Deletes All Giving Projects Posts, Resets Progress Data And Data Table
function eci_deleteprojectposts( $pro,$filename,$csv,$set )
{
	global $wpdb;

	eci_log( 'Delete Posts',
	'deleting posts started',
	'The delete project post procedure started, the request should delete all posts for the project',
	$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	

	// select records from project table where post id is not null
	$myrows = $wpdb->get_results( 'SELECT * 
	FROM '. $csv['sql']['tablename'] .' 
	WHERE ecipostid 
	IS NOT NULL OR ecipostid != "0" 
	LIMIT '.$set['querylimit'].'' );
	
	if( $pro[$filename]['rowsinsertsuccess'] > $set['querylimit'] )
	{
		eci_err( __('Multiple Events Required'),'The plugin is currently limited to deleting '.$set['querylimit'].' in a single event and your project appears to have
				'.$pro[$filename]['rowsinsertsuccess'].' records. There limit setting called "SQL Query Limit" and it exists to prevent hanging but can be increased on the 
				settings page in the advanced panel. Please press the Delete Project Posts button again to run another event and delete another 
				'.$set['querylimit'].' posts or less.' );
	}
	
	// loop through selected records - get post id - delete the post - set id to null in table
	if( $myrows )
	{
		$df = 0;// delete attempts failed
		$ds = 0;// delete attempts success
		$deleted = 0;// from blog - not count of changes made in project table
		$deletedfailed = 0;
		foreach( $myrows as $post )
		{
			$result = wp_delete_post( $post->ecipostid, $force_delete = true );
							
			if( !$result )
			{ 
				++$df; 
			}
			else
			{
				++$ds; 
			}
			
			$query = 'UPDATE '. $csv['sql']['tablename'] .' 
			SET ecipostid = NULL 
			WHERE ecipostid = '. $post->ecipostid .'';
			$result = $wpdb->query( $query );
		}
		
		// update project schedule
		$pro[$filename]['postscreated'] = $pro[$filename]['postscreated'] - $ds;
		
		eci_log( 'Delete Posts',
		''.$df.' posts deleted',
		'The plugin deleted '.$df.' posts on request. The projects progress data is also updated to reflect this change',
		$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );	

		// output results
		eci_mes( 'Delete Event Finished','
		<strong>Posts Deleted Success:</strong> '.$ds.'<br /> 		
		<strong>Posts Deleted Failed:</strong> '.$df.' (usually indicates that expected post was manually deleted previously) 	
		<br /><br />
		<h4>Failed Delete Attempts</h4>
		<p>If the event results above indicate "Post Deleted Failed" do not be alarmed. It usually indicates that the post 
		the plugin attempted to delete, does not exist. It may have been deleted previously. The plugin will remove the plugins
		ID from the assigned database record so that it can be used again in Post Creation events.</p>' );	
	}
	else
	{
		eci_mes( __('Delete Event Finished'),__('No posts deleted. The plugin did not find any records in your project database table
				with a post id assigned to them. This indicates none of the records have been used to create posts.') );
	}
}

/**
 * Selects values from giving column and checks that the column to determine if it holds all numeric values or not
 * @param integer $column
 * @param string $tablename
 * @return true if all numeric or false if strings/none numeric values found in column
 */
function eci_numericcolumncheck( $column,$tablename )
{
	global $wpdb;
	$q1 = "SELECT $column 
	FROM $tablename 
	LIMIT 100";
	
	$result1 = $wpdb->get_results( $q1 );

	if( $result1 )
	{
		// loop through returned records
		$numeric = true;
		foreach( $result1 as $key )
		{
			// if data value is not number but is also not a null or empty then this column wont work
			if( !is_numeric( $key->$column ) && $key->$column != NULL && !is_empty( $key->$column )  )
			{
				$numeric = false;
				break;
			}
		}
		
		if( $numeric )
		{
			return true;
		}
		else 
		{
			return false;
		}		
	}
	else
	{
		return false;
	}
}		

?>
