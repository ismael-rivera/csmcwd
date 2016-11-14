
<?php 
if(isset($csv['singlecategory']) && $csv['singlecategory'] != 'NA' && is_numeric($csv['singlecategory']))
{
	echo '<p>Some category features are currently hidden as you have saved a Single Category. Single category selection
	does not require any of the hidden features. Change the Single Category menu to Not Applicable to dislay all features again.</p>';
}
?>

<?php 
if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == 'NA' || !isset( $csv['singlecategory'] ) )
{
	// only display category column menus if user has not selected category splitter on special functions page
	if( isset( $csv['special']['state']['cats_col'] ) 
	&& $csv['special']['state']['cats_col'] == 'On' )
	{
		eci_not(__('Category Splitter Selected'),__('You have selected the Category Splitter option on the Special Functions stage. That will manage your categories
		for you. Change that setting if you wish to setup categories up manually.'));
	}
	elseif( isset( $csv['special']['state']['cats_col'] ) 
	&& $csv['specialfunctions']['state']['cats_col'] == 'Off'
	|| !isset( $csv['special']['state']['cats_col'] ) )
	{?>
		<h2>Create Category Groups</h2>
		<form method="post" name="eci_categorygroupsave_form" action="">            
		<p><?php _e('Use this form to create a category group. Create as many as you like, your post may have multiple parent categories.');?></p>
		<table class="widefat post fixed">
		  <tr><td><b>Category</b></td><td><b>Name</b></td><td><b> Apply To Post </b></td><td><b> Description Part One</b></td><td><b>Part Two</b></td><td><b>Part Three</b></td></tr>
		  <tr><td><b><a href="#" title="">Category 1 (parent)</a></b></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c1', $csv, $pro );?></td><td><?php eci_applycategory_checkbox($pro['current'], 'c1a', $csv, $pro);?></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c1d', $csv, $pro );?></td><td>Paid Edition Only</td><td>Paid Edition Only</td></tr>
		  <tr><td><b><a href="#" title="">Category 2 (child)</a></b></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c2', $csv, $pro );?></td><td><?php eci_applycategory_checkbox($pro['current'], 'c2a', $csv, $pro);?></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c2d', $csv, $pro );?></td><td>Paid Edition Only</td><td>Paid Edition Only</td></tr>
		  <tr><td><b><a href="#" title="">Category 3 (child)</a></b></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c3', $csv, $pro );?></td><td><?php eci_applycategory_checkbox($pro['current'], 'c3a', $csv, $pro);?></td><td><?php eci_csvcolumnmenu( $pro['current'], 'c3d', $csv, $pro );?></td><td>Paid Edition Only</td><td>Paid Edition Only</td></tr>                   
		</table>
		<input class="button-primary" type="submit" name="eci_categorygroupsave_submit" value="Save Category Set" />
		</form><?php
	}
	echo '<br /><br />';
}
?>

<?php	
// print current category groups here
if( isset( $csv['categories'] ) && !isset( $csv['singlecategory'] ) || isset( $csv['singlecategory'] ) && $csv['singlecategory'] == 'NA' )
{
	echo '<h2>Saved Category Groups</h2><table class="widefat post fixed">
	<p>The first group will set the default order of the categories. Meaning if the same column is used in more than one
	category group. The category will be created in relation to the first group i.e. parent, child.</p>';

	$i = 0;

	echo '<table class="widefat post fixed"><form method="post" name="eci_categorygroupsave_form" action="">';
			
	if(isset($csv['categories']))
	{
		foreach( $csv['categories'] as $catgroup=>$c )
		{	
			echo '
			<input type="hidden" name="eci_catgroupid" value="'.$catgroup.'" />';
		
			echo '
			<tr>
				<td width="200"><h4>Group Number '. $i .'</h4></td><td>
				<input class="button-primary" type="submit" name="eci_deletecategorygroup_submit" value="Delete Category Set" /></td><td></td><td></td>
			</tr>';	
			
			echo '
			<tr>
				<td><strong>Column</strong></td><td><strong>Level</strong></td><td><strong>Description Column</strong></td><td><strong>Applied To Post</strong></td>
			</tr>';
			
			echo '
			<tr>
				<td>'. $c['cat1'] .'</td><td>Parent/Category Level 1</td><td>'. $c['cat1d'] .'</td><td>'. $c['cat1a'] .'</td>
			</tr>';			
			
			echo '
			<tr>
				<td>'. $c['cat2'] .'</td><td>Child/Category Level 2:</td><td>'. $c['cat2d'] .'</td><td>'. $c['cat2a'] .'</td>
			</tr>';			
			
			echo '
			<tr>
				<td>'. $c['cat3'] .'</td><td>Child/Category Level 3:</td><td>'. $c['cat3d'] .'</td><td>'. $c['cat3a'] .'</td>
			</tr>';		
					
			++$i;
			
		}// end foreach categories set
	}
	echo '</table></form><br />';
}
?>

<?php 
if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == 'NA' || !isset( $csv['singlecategory'] ) )
{
	// only display category column menus if user has not selected category splitter on special functions page
	if( isset( $csv['special']['state']['cats_col'] ) 
	&& $csv['special']['state']['cats_col'] == 'On' )
	{
		eci_not(__('Category Splitter Selected'),__('You have selected the Category Splitter option on the Special Functions stage. That will manage your categories
		for you. Change that setting if you wish to setup categories up manually.'));
	}
	elseif( isset( $csv['special']['state']['cats_col'] ) 
	&& $csv['specialfunctions']['state']['cats_col'] == 'Off'
	|| !isset( $csv['special']['state']['cats_col'] ) )
	{?>
		<h2>Map Data Categories To Existing Categories</h2>      
		<p><?php _e('You should import all your data to use this feature properly. You must also setup at least one category group using the form above.
		The plugin needs to establish all your datas category values by querying your project table.
		This form allows you to pair categories in your data to categories already in your blog. Only use this where the categories are 
		not an exact match. If your categories in blog and in data match, then do not use this form.');?></p><?php
		eci_categoriespairing_form($pro['current'],$csv,$pro);
	}
	echo '<br /><br />';
}

?>

<?php 
if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == 'NA' || !isset( $csv['singlecategory'] ) )
{?>
    <h2><?php _e('Create Categories Early');?></h2>
    <p><?php _e('This function will allow you to create your categories now, based on the category groups you have saved and shown above. This
is not required, you can let the plugin create the categories as it creates posts. Notice: sometimes not all your categories will be visible on the
Categories admin page. Simply create then delete a category to make them display. ');?></p>
    <form method="post" name="eci_createcatsnow_form" action="">  
        <input class="button-primary" type="submit" name="eci_createcatsnow_submit" value="Create Categories Now" />
    </form><br /><?php 
}
?>

<?php 
if( isset( $csv['singlecategory'] ) && $csv['singlecategory'] == 'NA' || !isset( $csv['singlecategory'] ) )
{
	$status = 'Disabled';
}
elseif( isset( $csv['singlecategory'] ) && $csv['singlecategory'] != 'NA' )
{
	$status = 'Enabled';
}
?>
<h2>Apply Single Default Category - Currently <?php echo $status;?></h2>
<p><?php _e('Use this to put all posts or pages into a single category. This will over ride all other category options and
as a result will hide the other category options. To display them again later, simply selected "Not Required" and submit.');?></p>
<?php 
if(isset($csv['singlecategory']) && $csv['singlecategory'] != 'NA' && is_numeric($csv['singlecategory']))
{
	$categoryterm = get_category($csv['singlecategory']);
	echo '<strong>Current Single Category:</strong> '.$categoryterm->name.' (ID: '.$csv['singlecategory'].') <br />';
}
else 
{
	echo '<strong>No Single Category Saved</strong> <br />';	
}
?>
<br />
<?php eci_categoriesmenu( $csv );?>

<br />