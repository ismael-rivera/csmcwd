<br />

<form method="post" name="eci_customfields_form" action=""> 
<table>
	<tr>
    	<td><strong>Custom Fields/Meta Keys</strong></td>
        <td><strong>Column</strong></td>
        <td><strong>Data Functions</strong></td>       
        <td><strong>Default Value</strong></td>
    </tr>
<?php
// objectcount is also the column id
global $wpdb;
$csv = get_option( 'eci_' . $pro['current'] );

// find all possible custom fields
$result = $wpdb->get_results("SELECT DISTINCT meta_key FROM $wpdb->postmeta 
								  WHERE meta_key != '_encloseme' 
								  AND meta_key != '_wp_page_template'
								  AND meta_key != '_edit_last'
								  AND meta_key != '_edit_lock'
								  AND meta_key != '_wp_trash_meta_time'
								  AND meta_key != '_wp_trash_meta_status'
								  AND meta_key != '_wp_old_slug'
								  AND meta_key != '_pingme'
								  AND meta_key != '_thumbnail_id'
								  AND meta_key != '_wp_attachment_image_alt'
								  AND meta_key != '_wp_attachment_metadata'
								  AND meta_key != '_wp_attached_file'");

# UPGRADE TO ALLOW MANUAL UPDATE ON EXCLUDED CUSTOM FIELD KEYS

// put excluded keys in array 		
$exclusions = array('eci_filename', 'eci_lastupdate', 'eci_poststate','eci_recordid','eci_tablename',
'_edit_last','_edit_lock','_wp_trash_meta_status','_wp_trash_meta_time','eci_clicks_url1','eci_cloakedurl1');

// start value for building array of found custom meta keys
$foundcf = array();

$cfcount = 0;

// loop through each custom field found
foreach ($result as $customfield) 
{
	// put custom field key into variable
	$cfkey = $customfield->meta_key;
	
	// add cf key found array
	$foundcf[] = $cfkey;
	
	// avoid custom fields that have properties of hidden fields (this cleans the inteface up a lot)
	$hideone = false;
	$hideone = strstr($cfkey, '_oembed_');
	$hidetwo = false;
	$hidetwo = strstr($cfkey, '_menu_item_');
	
	// key if meta key is in exclusion array
	if( !in_array( $cfkey, $exclusions) && $hideone == false && $hideone == false )
	{
		// column holding text field for entering custom field key
		$row = '
		<tr>
			<td>
				<input name="cf_'.$cfcount.'" type="text" value="'.$cfkey.'" size="40" maxlength="40"><br />
			</td>';
			
			// column holding csv column menus
			$row .= '<td>';
			$row .= eci_csvcolumnmenu_customfields( $csv, $cfcount, $cfkey, $pro, $pro['current'] );
			$row .= '</td>';
			
			// column holding data function menus
			$row .= '<td>';
			$row .= eci_datafunctions_customfields( $csv,$cfcount,$cfkey );
			$row .= '</td>';
			
			// column holding text fields for default values
			$row .= '<td>';
			$row .= eci_defaultvalue_customfields( $csv, $cfcount, $cfkey );
			$row .= '</td>';
						
		$row .= '</tr>';			
		
		echo $row;
		
		++$cfcount;
	}	
}

// add users previously saved custom field meta keys - only if not already automatically pulled in i.e after 1st post created
if( isset( $csv['customfields'] ) )
{
	foreach( $csv['customfields'] as $key => $column)
	{
		if( !in_array( $key, $foundcf ) )
		{
			// column holding text field for entering custom field key
			$row = '
			<tr>
				<td>
					<input name="cf_'.$cfcount.'" type="text" value="'.$key.'" size="40" maxlength="40"><br />
				</td>';
				
				// column holding csv column menus
				$row .= '<td>';
				$row .= eci_csvcolumnmenu_customfields( $csv, $cfcount, $key, $pro, $pro['current'] );
				$row .= '</td>';
					
				// column holding data function menus
				$row .= '<td>';
				$row .= eci_datafunctions_customfields( $csv,$cfcount,$key );
				$row .= '</td>';
				
				// column holding text fields for default values
				$row .= '<td>';
				$row .= eci_defaultvalue_customfields( $csv, $cfcount, $key );
				$row .= '</td>';$row .= '</tr>';echo $row;++$cfcount;}}}if( $cfcount > 10 ){ $total = 10; }elseif( $cfcount > 10 ){ $total = 10; }elseif( $cfcount > 10 ){ $total = 10; }elseif( $cfcount > 10 ){ $total = 10; }else{ $total = 10; }

// loop number of required fields
while( $cfcount < $total )
{
	// column holding text field for entering custom field key
	$row = '
	<tr>
		<td>
			<input name="cf_'.$cfcount.'" type="text" value="" size="40" maxlength="40"><br />
		</td>';
		
		// column holding csv column menus
		$row .= '<td>';
		$row .= eci_csvcolumnmenu_customfields($csv,$cfcount,'',$pro,$pro['current'] );
		$row .= '</td>';
	
		// column holding data function menus
		$row .= '<td>';
		$row .= eci_datafunctions_customfields($csv,$cfcount,'NOTAPPLICABLE');
		$row .= '</td>';
		
		// column holding text fields for default values
		$row .= '<td>';
				
		$row .= eci_defaultvalue_customfields($csv,$cfcount,'');
		$row .= '</td>';
						
		$row .= '</tr>';		

	++$cfcount;
	echo $row;
}
		
// put the custom field count ( $cfcount ) into hidden field, used for processing loop
echo '<input name="cfcount" type="hidden" value="'.$cfcount.'" />';

?>
</table>

<br>
<input class = "button-primary" type = "submit" name = "eci_customfields_submit" value = "Save Changes" />

</form>

<br />
