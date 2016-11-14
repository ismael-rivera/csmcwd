<br />

<form method="post" name="eci_usermetakeys_form" action=""> 
<table>
	<tr>
    	<td><strong>Part</strong></td>
        <td><strong>Column</strong></td>
        <td><strong>Data Functions</strong></td>       
        <td><strong>Default Value</strong></td>
    </tr>
<?php
// objectcount is also the column id
global $wpdb;
$csv = get_option( 'eci_' . $pro['current'] );

// find all possible user meta keys and exclude system ones to keep the form tidy
$result = $wpdb->get_results("SELECT DISTINCT meta_key FROM $wpdb->usermeta 
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
								  AND meta_key != '_wp_attached_file'
								  AND meta_key != 'wp_dashboard_quick_press_last_post_id'
								  AND meta_key != 'closedpostboxes_dashboard'
								  AND meta_key != 'metaboxhidden_dashboardc'
								  AND meta_key != 'meta-box-order_dashboard'
								  AND meta_key != 'screen_layout_dashboard'
								  AND meta_key != 'meta-box-order_post'
								  AND meta_key != 'closedpostboxes_post'
								  AND meta_key != 'metaboxhidden_post'
								  AND meta_key != 'wp_capabilities'
								  AND meta_key != 'rich_editing'
								  AND meta_key != 'show_admin_bar_front'
								  AND meta_key != 'show_admin_bar_admin'
								  AND meta_key != 'wp_user-settings'
								  AND meta_key != 'metaboxhidden_dashboard'
								  AND meta_key != 'plugins_last_view'
								  AND meta_key != 'screen_layout_post'
								  AND meta_key != 'wp_user-settings-time'
								  AND meta_key != 'comment_shortcuts'
								  AND meta_key != 'admin_color'
								  ");

# UPGRADE TO ALLOW MANUAL UPDATE ON EXCLUDED CUSTOM FIELD KEYS

// start value for building array of found custom meta keys
$founduk = array();// store what has been found to prevent it being displayed twice in list
$ukcount = 0;// count the total number of objects added to form, this used as form object ID

// add the user_email address field - this is REQUIRED
$row = '
<tr>
	<td>
		<input name="uk_'.$ukcount.'" type="text" value="user_email" size="25" maxlength="40" readonly> (required)<br />
	</td>';
	
	// add user key to found array
	$founduk[] = 'email';
	
	// column holding csv column menus
	$row .= '<td>';
	$row .= eci_csvcolumnmenu_usermetakeys($csv, $ukcount, 'user_email', $pro, $pro['current']);
	$row .= '</td>';
	
	// column holding data function menus
	$row .= '<td>';
	$row .= eci_datafunctions_usermetakeys( $csv,$ukcount,'user_email' );
	$row .= '</td>';
	
	// column holding text fields for default values
	$row .= '<td>';
	$row .= eci_defaultvalue_customfields( $csv, $ukcount, 'user_email' );
	$row .= '</td>';
				
$row .= '</tr>';			
echo $row;
++$ukcount;	

// add the user_login (username) field - this is not required parts of names or email address will be used if not provided
$row = '
<tr>
	<td>
		<input name="uk_'.$ukcount.'" type="text" value="user_login" size="40" maxlength="40" readonly><br />
	</td>';
	
	// add user key to found array
	$founduk[] = 'email';
	
	// column holding csv column menus
	$row .= '<td>';
	$row .= eci_csvcolumnmenu_usermetakeys($csv, $ukcount, 'user_login', $pro, $pro['current']);
	$row .= '</td>';
	
	// column holding data function menus
	$row .= '<td>';
	$row .= eci_datafunctions_usermetakeys( $csv,$ukcount,'user_login' );
	$row .= '</td>';
	
	// column holding text fields for default values
	$row .= '<td>';
	$row .= eci_defaultvalue_customfields( $csv, $ukcount, 'user_login' );
	$row .= '</td>';
				
$row .= '</tr>';			
echo $row;
++$ukcount;

// loop through each user meta key found and create a form object for each
foreach ($result as $customfield) 
{
	// put user meta value into variable
	$ukkey = $customfield->meta_key;
	
	// add user key to found array
	$founduk[] = $ukkey;

	// column holding text field for entering custom field key
	$row = '
	<tr>
		<td>
			<input name="uk_'.$ukcount.'" type="text" value="'.$ukkey.'" size="40" maxlength="40"><br />
		</td>';
		
		// column holding csv column menus
		$row .= '<td>';
		$row .= eci_csvcolumnmenu_usermetakeys($csv, $ukcount, $ukkey, $pro, $pro['current']);
		$row .= '</td>';
		
		// column holding data function menus
		$row .= '<td>';
		$row .= eci_datafunctions_usermetakeys( $csv,$ukcount,$ukkey );
		$row .= '</td>';
		
		// column holding text fields for default values
		$row .= '<td>';
		$row .= eci_defaultvalue_customfields( $csv, $ukcount, $ukkey );
		$row .= '</td>';
					
	$row .= '</tr>';			
	
	echo $row;
	
	++$ukcount;
}

// add users previously saved custom field meta keys - only if not already automatically pulled in i.e after 1st post created
if( isset( $csv['usermetakeys'] ) )
{
	foreach( $csv['usermetakeys'] as $key => $column)
	{
		// column holding text field for entering custom field key
		$row = '
		<tr>
			<td>
				<input name="uk_'.$ukcount.'" type="text" value="'.$key.'" size="40" maxlength="40"><br />
			</td>';
			
			// column holding csv column menus
			$row .= '<td>';
			$row .= eci_csvcolumnmenu_usermetakeys( $csv, $ukcount, $ukkey, $pro, $pro['current'] );
			$row .= '</td>';
				
			// column holding data function menus
			$row .= '<td>';
			$row .= eci_datafunctions_usermetakeys( $csv,$ukcount,$ukkey );
			$row .= '</td>';
			
			// column holding text fields for default values
			$row .= '<td>';
			$row .= eci_defaultvalue_customfields( $csv, $ukcount, $ukkey );
			$row .= '</td>';
						
		$row .= '</tr>';			
			
		// display 
		echo $row;
		
		// increase found and displayed counter
		++$ukcount;		
	}
}

// add blank fields for manual custom field entry - number depending on existing meta keys
if( $ukcount > 20 ){ $total = 23; }
elseif( $ukcount > 15 ){ $total = 20; }
elseif( $ukcount > 10 ){ $total = 20; }
elseif( $ukcount > 5 ){ $total = 20; }
else{ $total = 20; }

// loop number of required fields
while( $ukcount < $total )
{
	// column holding text field for entering custom field key
	$row = '
	<tr>
		<td>
			<input name="uk_'.$ukcount.'" type="text" value="" size="40" maxlength="40"><br />
		</td>';
		
		// column holding csv column menus
		$row .= '<td>';
		$row .= eci_csvcolumnmenu_usermetakeys( $csv,$ukcount,'',$pro,$pro['current'] );
		$row .= '</td>';
	
		// column holding data function menus
		$row .= '<td>';
		$row .= eci_datafunctions_usermetakeys( $csv,$ukcount,$ukkey );
		$row .= '</td>';
		
		// column holding text fields for default values
		$row .= '<td>';
				
		$row .= eci_defaultvalue_customfields( $csv, $ukcount, '' );
		$row .= '</td>';
						
		$row .= '</tr>';		

	++$ukcount;
	echo $row;
}
		
// put the custom field count ( $cfcount ) into hidden field, used for processing loop
echo '<input name="ukcount" type="hidden" value="'.$ukcount.'" />';

?>
</table>

<br>
<input class = "button-primary" type = "submit" name = "eci_usermetakeys_submit" value = "Save Changes" />

</form>

<br />
