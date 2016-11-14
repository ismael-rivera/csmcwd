<?php
function eci_webtechglobal_widget()
{
	
}

/**
 * Widget one - a set layout that cannot be edited at this time
 * 
 * @param unknown_type $args
 */
function eci_widget_one($args)
{
	global $wpdb;
	 
	$set = get_option('eci_set');

	// extracts before_widget,before_title,after_title,after_widget all required and cannot be deleted
	extract($args); 
	
	if( isset( $set['widget'][1]['title'] ) )
	{
		$title = $set['widget'][1]['title'];
	}
	else
	{
		$title = 'Title Not Saved';
	}
	
	echo $before_widget . $before_title . ' '. $title .' ' . $after_title;
		
	// continue only if user has selected a project to be used
	// currently only one project is possible (project1), if required we can upgrade to allow multiple projects
	if( isset( $set['widget'][1]['project1'] ) && $set['widget'][1]['project1'] != 'NA' )
	{
		// get entire project array
		$pro = get_option( 'eci_pro');		
		$csv = get_option( 'eci_'.$set['widget'][1]['project1']);
		
		// now put widget project into variable
		$proj = array();
		$proj = $pro[ $set['widget'][1]['project1'] ];
		// define output values		
		$itemtitle = 'TBC';		
		$itemimage = 'Not Set';
		$itemprice = 'Not Set';
		$itemtext = 'TBC';
		
		// build sql select query to get a random record
		$finalquery = 'SELECT '; 
		$query_part2 = ''; 

		// add required columns
		if( isset( $set['widget'][1]['titlecolumn'] ) && $set['widget'][1]['titlecolumn'] != 'NA' )
		{
			// we need to prepare column names (no spaces or special characters etc)
			$titlecolumn = eci_cleansqlcolumnname($set['widget'][1]['titlecolumn']);	

			$query_part1 .= $titlecolumn; 
			
			// add "WHERE" to this part as it is the first
			$query_part2 .= ' WHERE ' . $titlecolumn . ' IS NOT NULL '; 
			
			$possible_one = true;
		}		
		else
		{		
			$itemtitle = 'Not Set';
		}
		
		if( isset( $set['widget'][1]['imagecolumn'] ) && $set['widget'][1]['imagecolumn'] != 'NA' )
		{
			if( $possible_one == true )
			{
				$query_part1 .= ',';
				
				$query_part2 .= ' AND ';
			}
			
			// we need to prepare column names (no spaces or special characters etc)
			$imagecolumn = eci_cleansqlcolumnname($set['widget'][1]['imagecolumn']);	

			$query_part1 .= $imagecolumn; 
			
			$query_part2 .= $imagecolumn . ' IS NOT NULL '; 
			
			$possible_two = true;
		}		
		else
		{
			$itemimage = 'Not Set';
		}
		
		if( isset( $set['widget'][1]['pricecolumn'] ) && $set['widget'][1]['pricecolumn'] != 'NA' )
		{
			if( $possible_two == true )
			{
				$query_part1 .= ',';
				
				$query_part2 .= ' AND ';
			}			
			
			// we need to prepare column names (no spaces or special characters etc)
			$pricecolumn = eci_cleansqlcolumnname($set['widget'][1]['pricecolumn']);	

			$query_part1 .= $pricecolumn; 
			
			$query_part2 .= $pricecolumn . ' IS NOT NULL '; 
			
			$possible_three = true;
		}		
		else
		{
			$itemprice = 'Not Set';
		}		
		
		if( isset( $set['widget'][1]['textcolumn'] ) && $set['widget'][1]['textcolumn'] != 'NA' )
		{
			if( $possible_three == true )
			{
				$query_part1 .= ',';
				
				$query_part2 .= ' AND ';
			}	
			
			// we need to prepare column names (no spaces or special characters etc)
			$textcolumn = eci_cleansqlcolumnname($set['widget'][1]['textcolumn']);	

			$query_part1 .= $textcolumn; 
			
			$query_part2 .= $textcolumn . ' IS NOT NULL '; 
			
			$possible_four = true;
		}			
		else
		{
			$itemtext = 'Not Set';
		}	
			
		$finalquery .= $query_part1 .' FROM '. $csv['sql']['tablename'] . $query_part2;
		
		$finalquery .= 'ORDER BY RAND() LIMIT 1';
		
		$result = $wpdb->get_row($finalquery); 
		
		// get query values
		if( $set['widget'][1]['titlecolumn'] != 'TBC' && $set['widget'][1]['titlecolumn'] != 'NA' )
		{
			eval( '$itemtitle = $result->$titlecolumn;' );
		}		
		
		if( $set['widget'][1]['imagecolumn'] != 'TBC' && $set['widget'][1]['imagecolumn'] != 'NA' )
		{	
			eval( '$itemimage = $result->$imagecolumn;' );
		}		
		
		if( $set['widget'][1]['pricecolumn'] != 'TBC' && $set['widget'][1]['pricecolumn'] != 'NA' )
		{
			eval( '$itemprice = $result->$pricecolumn;' );
		}		
		
		if( $set['widget'][1]['textcolumn'] != 'TBC' && $set['widget'][1]['textcolumn'] != 'NA' )
		{
			eval( '$itemtext = $result->$textcolumn;' );
		}		
		
		// complete output
		echo $itemtitle;
		echo '<br />';
		echo '<img src="'.$itemimage.'" alt="'.$itemtitle.'" width="200" height="200" />';
		echo '<br />';
		echo '<strong>From only &pound;'.$itemprice.'</strong>';
		echo '<br />';
		echo $itemtext;
	}
	else
	{
		_e('Easy CSV Importer Widget has not been configured properly for use!');
	}

	// display after widget
	echo $after_widget;
}
?>