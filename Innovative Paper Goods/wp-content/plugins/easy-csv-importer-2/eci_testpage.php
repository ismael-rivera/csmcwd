<?php 
eci_header();
global $pro,$set,$spe,$des,$wpdb;

// process form submissions
if( isset( $_POST['eci_widgetone_submit'] ) && !isset( $set['widget'][1]['title'] ) && isset( $_POST['eci_widgettitle'] ) )
{
	eci_widgetone_saveproject( $_POST['eci_widgettitle'],$_POST['eci_project1'] );
}
elseif( isset( $_POST['eci_widgetone_submit'] ) && isset( $set['widget'][1]['title'] ) )
{
	eci_widgetone_savecolumns( $_POST['eci_widgettitle'],$_POST['eci_project1'],
	$_POST['eci_columnid_itemtitle'],$_POST['eci_columnid_itemimage']
	,$_POST['eci_columnid_itemprice'],$_POST['eci_columnid_itemtext'] );
}
if( isset( $_POST['eci_widgetone_submit'] ) && !isset( $set['widget'][1]['title'] ) && !isset( $_POST['eci_widgettitle'] ) )
{
	eci_err( __('Please Complete Form'),__('It appears you missed something, please try again then seek support.') );
}

// get data arrays after processing
$pro = get_option( 'eci_pro' );
$set = get_option( 'eci_set' );
?> 

<?php eci_pagetitle('ECI Testing',$pro['current']); ?>

<p>Various tests will be performed here before being applied to the plugins main interface. The tests will be released in multiple verions before being applied anywhere
other than this page.</p>

<br /><br />

<h2>MySQL 2 CSV PHP Build - LEFT JOIN APPROACH</h2>
<?php 
// open csv file handle
//$fp = fopen(ECIPATH.'wtgexporttest.csv', "w");
if(!$fp)
{
	//echo 'fopen as failed';
}

// initiate query variables
$queryselect = 'SELECT ';

// build select
$tableone = 'wp_posts';
$columnOneA = 'post_title';
$columnOneB = 'post_type';
$columnOneC = 'post_parent';
$queryselect .= $tableone.'.'.$columnOneA.','.$tableone.'.'.$columnOneB.','.$tableone.'.'.$columnOneC.'';

$tabletwo = 'wp_users';
$columnTwoA = 'user_login';
$queryselect .= ','.$tabletwo.'.'.$columnTwoA.'';

$tablethree = 'wp_usermeta';
$columnThreeA = 'meta_key';
$columnThreeB = 'meta_value';
$queryselect .= ','.$tablethree.'.'.$columnThreeA.','.$tablethree.'.'.$columnThreeB.'';

// build from
$queryfrom = ' FROM '.$tableone;

// build join
$queryjoin = ' LEFT JOIN '.$tabletwo.'';
$queryjoin .= ' LEFT JOIN '.$tablethree.'';

$finalquery = $queryselect.$queryfrom.$queryjoin;

//$res = $wpdb->get_results($finalquery);

//var_dump( $res );

// and loop through the actual data
/*
while($row = $res) 
{   
	var_dump( $row );
	
    $line = "";
    $comma = "";
    foreach($row as $value) {
        $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
        $comma = ",";
    }
    $line .= "\n";
    fputs($fp, $line);  
}
*/
//fclose($fp);
?>
<strong>The Query</strong>
<form>
<textarea rows="4" cols="100"><?php echo $finalquery;?></textarea>
</form>

<br /><br />


<h2>MySQL 2 CSV Query Dump</h2>
<?php 

// initiate query variables
$queryselect = 'SELECT ';

// build select
$tableone = 'wp_posts';
$columnOneA = 'post_title';
$columnOneB = 'post_type';
$columnOneC = 'post_parent';
$queryselect .= $tableone.'.'.$columnOneA.','.$tableone.'.'.$columnOneB.','.$tableone.'.'.$columnOneC.'';

$tabletwo = 'wp_users';
$columnTwoA = 'user_login';
$queryselect .= ','.$tabletwo.'.'.$columnTwoA.'';

$tablethree = 'wp_usermeta';
$columnThreeA = 'meta_key';
$columnThreeB = 'meta_value';
$queryselect .= ','.$tablethree.'.'.$columnThreeA.','.$tablethree.'.'.$columnThreeB.'';

// build create path
$querypath = " INTO OUTFILE '/tmp/wtgtestexport2011.csv'";

// build config
$queryconfig = " FIELDS TERMINATED BY ','";
$queryconfig .= ' ENCLOSED BY '."'".'"'."'".'';
$queryconfig .= ' ESCAPED BY ' . "'\\'" . ' LINES TERMINATED BY ';
$queryconfig .= "'\n'";

// build from
$queryfrom = ' FROM '.$tableone;

// build join
$queryjoin = ' LEFT JOIN '.$tabletwo.'';
$queryjoin .= ' LEFT JOIN '.$tablethree.'';

$finalquery = $queryselect.$querypath.$queryconfig.$queryfrom.$queryjoin;

//$res = $wpdb->query($finalquery);
?>
<strong>The Dump Query</strong>
<form>
<textarea rows="4" cols="100"><?php echo $finalquery;?></textarea>
</form>
<br /><br />



<h2>Translations</h2>
<?php 
_e('First test message for Easy CSV Importer translation');
echo '<br />';
_e('We need translators to check various languages and help to increase quality for users local to you');
?>


<br /><br />

<h1>Widget One Testing</h1>
<p>This widget requires you to select a project then save. More form ojects will appear. The widget will use data from that projects database table. You must
pair up each column with parts of the widget.</p>
<form method="post" name="eci_widgetone_form" action="">                  
	
	<label>Select Project: 
		<select name="eci_project1" size="1">
			<?php 
            // list existing projecta each one with their own post box
            $projects = 0;
            if( isset( $pro ) && $pro != '' )
            {
                foreach( $pro as $key=>$item )
                {
                    if( $key != 'arraydesc' && $key != 'current' && $key != 'records' )
                    {
                        // close all post boxes apart from the current project
                    	echo '<option value="'.$key.'">'.$key.'</option>';
                        ++$projects;
                    }
                }
            }	
            
            if( $projects == 0 )
            {
            	echo '<option value="NA">No Projects With Data Imported Were Found</option>';
            	$noprojects = true;
            }
            ?>
			
	    </select>
    </label>   
	
	<?php 
	if( isset( $noprojects ) && $noprojects == true )
	{
		echo '<br /><br /><strong>Please create a project, import some data then refresh the page. You must have some data 
		imported for the widget to work correctly. More options will be displayed here once you have done that.</strong>';
	}
	else
	{?>
    	<br /><br /> 
		Widget Title:<input name="eci_widgettitle" type="text" value="<?php if(isset($set['widget'][1]['title'])){echo $set['widget'][1]['title'];} ?>" size="20" maxlength="20" />
	<?php
	}
	?>
                 
	<?php 
	// if table is set - display the project files columns for selection in menu
	if( isset( $set['widget'][1]['project1'] ) && $set['widget'][1]['project1'] != 'NA'
	&& isset( $set['widget'][1]['title'] ) )
	{?>
		<br /><br />
		<table>
		    <tr>
		    	<td><b><a href="#" title="">Display</a></b></td>
		    	<td><b><a href="#" title="">Column</a></b></td>
		    </tr>
			<tr>
		    	<td>Title</td>
		        <td><?php eci_csvcolumnmenu( $set['widget'][1]['project1'],'itemtitle'); ?></td>
		    </tr>						
		    <tr>
		    	<td>Image</td>
		        <td><?php eci_csvcolumnmenu($set['widget'][1]['project1'],'itemimage'); ?></td>
		    </tr>						
		    <tr>
		        <td>Price</td>
		        <td><?php eci_csvcolumnmenu($set['widget'][1]['project1'],'itemprice'); ?></td>
		    </tr>                      
		    <tr>
		        <td>Text</td>
		        <td><?php eci_csvcolumnmenu($set['widget'][1]['project1'],'itemtext'); ?></td>
		    </tr>
		</table>
	<?php 
	}?>
	
	<br /><br />
	
	<?php 
	if( !isset( $noprojects ) || isset( $noprojects )&& $noprojects != true )
	{
		echo '<input class="button-primary" type="submit" name="eci_widgetone_submit" value="Save Widget One Settings" />';
	}
	?>
</form>         


<br /><br />



<h2>Scripts</h2>
<p>Currently testing the use of JS/Ajax/Jquery to improve the plugins interface. Please feedback results to me working or not and any suggestions for specific pages. Each form 
may require a different approach and even a different script so there is much to be done.</p>

<h2>CSS/Styling</h2>

<h3>Hover Cursor Help</h3>
<a href="#" title="Simple help functionality, it won't be used everywhere but there may be places where it provides information we need 
one time only and so allows me to keep the interface tidy for continued use." style="cursor: help;">Hover Over Me</a>
<br /><br />
<a href="#" title="They will probably come in the form of question marks." style="cursor: help;">?</a>

<br /><br />

<?php eci_footer(); ?>

