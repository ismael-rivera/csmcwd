<?php
// prepare checkbox values
if( isset( $csv['conditions']['switches']['dropposts'] ) && $csv['conditions']['switches']['dropposts'] == true ){ $dpc = 'checked="checked"'; }else{ $dpc = ''; }
?>

<form method="post" name="eci_projectoptions_form" action="">   
	<br />
	<strong>Main Design</strong><br /><?php echo eci_maindesignsmenu( $csv ); ?>   <br /><br />

    <strong>Post Publish Date Method</strong><br />
	<select name="eci_datemethod" size="1" >
		<option value="default" <?php if(isset($csv['datemethod']) && $csv['datemethod'] == 'default'){echo 'selected="selected"';}?>>Default</option>
        <?php
		if( isset( $csv['specials']['col']['dates_col'] ) && isset( $csv['specials']['state']['dates_col'] ) && $csv['specials']['state']['dates_col'] == 'On' )
		{
			if(isset($csv['datemethod']) && $csv['datemethod'] == 'data'){$dataselected = 'selected="selected"';}else{$dataselected = '';}
			echo '<option value="data" '.$dataselected.'>CSV File Data</option>';
		}		
		
		if( !isset( $csv['specials']['col']['dates_col'] ) || !isset( $csv['specials']['state']['dates_col'] ) 
		|| isset( $csv['specials']['state']['dates_col'] ) && $csv['specials']['state']['dates_col'] == 'Off' )
		{
			if(isset($csv['datemethod']) && $csv['datemethod'] == 'increment'){$incrementselected = 'selected="selected"';}else{$incrementselected = '';}
			echo '<option value="increment">CSV File Data (Locked)</option>';
		}
		?>
		<option value="random" <?php if(isset($csv['datemethod']) && $csv['datemethod'] == 'random'){echo 'selected="selected"';}?>>Random</option>
		<option value="increment" <?php if(isset($csv['datemethod']) && $csv['datemethod'] == 'increment'){echo 'selected="selected"';}?>>Increment</option>
	</select><br /><br />

    <strong>Default Post Type</strong><br />
    <input name="type" type="text" value="posttype" size="20" maxlength="20" disabled="disabled" />(post,page or custom)<br /><br />

    <strong>Default Publisher ID</strong><br />
    <input name="eci_publisher" type="text" value="<?php if(isset($csv['postpublisher'])){echo $csv['postpublisher'];}else{echo 1;} ?>" size="8" maxlength="8" />(admin id is usually 1)<br /><br />

    <strong>Publish Status</strong>
    <table class="widefat post fixed">
      <tr>
        <td width="100">
            <label>
                <input type="radio" name="eci_publish" value="publish" id="eci_publish_0" 
				<?php if(isset($csv['poststatus'])&&$csv['poststatus']=='publish'){echo 'checked="checked"';}elseif(!isset($csv['poststatus'])){echo 'checked="checked"';} ?> />Publish
            </label>
        </td>
        <td width="100">
            <label>
                <input type="radio" name="eci_publish" value="draft" id="eci_publish_1" 
				<?php if(isset($csv['poststatus'])&&$csv['poststatus']=='draft'){echo 'checked="checked"';} ?> />Draft
            </label>
        </td>
        <td>
            <label>
              <input type="radio" name="eci_publish" value="pending" id="eci_publish_1" 
			  <?php if(isset($csv['poststatus'])&&$csv['poststatus']=='pending'){echo 'checked="checked"';} ?> />Pending
            </label>
        </td>
      </tr>
    </table><br />
    
    <strong>Allow Comments</strong>
    <table class="widefat post fixed">
      <tr>
        <td width="100">
            <label>
                <input type="radio" name="eci_comments" value="open" id="eci_comments_0" 
				<?php if(isset($csv['postcomments'])&&$csv['postcomments']=='open'){echo 'checked="checked"';}elseif(!isset($csv['postcomments'])){echo 'checked="checked"';} ?> />Yes
            </label>
        </td>
        <td>
            <label>
                <input type="radio" name="eci_comments" value="closed" id="eci_comments_1" 
				<?php if(isset($csv['postcomments'])&&$csv['postcomments']=='closed'){echo 'checked="checked"';} ?> />No
            </label>
        </td>
      </tr>
    </table>    
    <br />

    <strong>Allow Pings</strong>
    <table class="widefat post fixed">
      <tr>
        <td width="100">
            <label>
                <input type="radio" name="eci_pings" value="1" id="eci_pings_0" 
				<?php if(isset($csv['postpings'])&&$csv['postpings']=='1'){echo 'checked="checked"';}elseif(!isset($csv['postpings'])){echo 'checked="checked"';} ?> />Yes
            </label>
        </td>
        <td>
            <label>
                <input type="radio" name="eci_pings" value="0" id="eci_pings_1" 
				<?php if(isset($csv['postpings'])&&$csv['postpings']=='0'){echo 'checked="checked"';} ?> />No
            </label>
        </td>
      </tr>
    </table>    
    <br />

    <strong>Adopt Existing Posts</strong>
    <table class="widefat post fixed">
      <tr>
        <td width="100">
            <label>
                <input type="radio" name="eci_adopt" value="1" id="eci_adopt_0" 
				<?php if(isset($csv['postadopt'])&&$csv['postadopt']=='1'){echo 'checked="checked"';}elseif(!isset($csv['postadopt'])){echo 'checked="checked"';} ?> />Yes
            </label>
        </td>
        <td>
            <label>
                <input type="radio" name="eci_adopt" value="0" id="eci_adopt_1"
                 <?php if(isset($csv['postadopt'])&&$csv['postadopt']=='0'){echo 'checked="checked"';} ?> />No
            </label>
        </td>
      </tr>
    </table>
                    
 	<br />
    <input class="button-primary" type="submit" name="eci_projectoptions_submit" value="Submit" />
</form> 

<br /><br />