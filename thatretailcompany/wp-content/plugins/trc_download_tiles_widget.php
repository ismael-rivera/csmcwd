<?php
/*
Plugin Name: TRC Download Tiles Widget
Plugin URI: http://speggo.com/
Description: Custom Fill in the Blanks Widget for Downloads in the sidebars. Not available in the Wordpress plugins repository. Custom built code by the author.
Author: Ismael Rivera
Version: 1.0.0
Author URI: http://speggo.com/
*/

/**
 * Authors Widget Class
 
 Copyright 2012  Ismael Rivera

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/
 
 
 
// You can only use this Widget if the plugin is active

 
class TRC_Dowload_Tiles_Widget extends WP_Widget {
 
public function TRC_Dowload_Tiles_Widget() {
/* This function activates the widget in the back admin	*/
// widget actual processes 

		$widget_ops = array(
		  'classname' => 'trc_download-tiles-widget',
		  'description' => 'Mostly like Promo Tiles Widget except this one is for downloads links'
		);
				
    	$this->WP_Widget('TRC_Dowload_Tiles_Widget', 'TRC Download Tiles Widget', $widget_ops);

}

public function widget($args, $instance) {
/* This function is for the output. Here is where we write the code we would like to see outputted in the frontend.	*/	
        extract( $args, EXTR_SKIP );
		global $wpdb;
		
		$title     = ($instance['title'])     ? $instance['title']     : '';
		$body      = ($instance['body'])      ? $instance['body']      : '';
		$file_link = ($instance['file_link']) ? $instance['file_link'] : '';
		$btn_label = ($instance['btn_label']) ? $instance['btn_label'] : '';
		$img_name  = ($instance['img_name'])  ? $instance['img_name']  : '';
		
		?>
        
        <?php echo $before_widget; ?>
        <?php echo $before_title . $title . $after_title; ?>
        <div class="promo_tile_content">
        <p><?php echo $body; ?></p>
        <a target="_blank" class="promo_tile_button" href="<?php bloginfo('url'); ?>/wp-content/uploads/<?php echo $file_link; ?>"><?php echo $btn_label; ?> </a>
        </div>
        <div class="promo_tile_icon">
        <?php echo do_shortcode("[promo-icon icon=".$img_name."]"); ?>
        </div>
        <?php echo $after_widget; ?>

        <?php
		
    }
 
public function form( $instance ) {
/* This function is for the form that we would like to see in the back-end.	*/	
// outputs the options form on admin
      
	  $title      = esc_attr( isset( $instance['title'] ) ? $instance['title'] : '' );
	  $title_id   = $this->get_field_id('title');
	  $title_name = $this->get_field_name('title');
	  
	  $body       = esc_attr( isset( $instance['body'] ) ? $instance['body'] : '' );
	  $body_id    = $this->get_field_id('body');
	  $body_name  = $this->get_field_name('body');
	  
	  $file_link       = esc_attr( isset( $instance['file_link'] ) ? $instance['file_link'] : '' );
	  $file_link_id    = $this->get_field_id('file_link');
	  $file_link_name  = $this->get_field_name('file_link');
	  
	  $btn_label       = esc_attr( isset( $instance['btn_label'] ) ? $instance['btn_label'] : '' );
	  $btn_label_id    = $this->get_field_id('btn_label');
	  $btn_label_name  = $this->get_field_name('btn_label');
	  
	  $img_name       = esc_attr( isset( $instance['img_name'] ) ? $instance['img_name'] : '' );
	  $img_name_id    = $this->get_field_id('img_name');
	  $img_name_name  = $this->get_field_name('img_name');
	  
?>
      <table width="100%">
  <tr>
    <td><label for="<?php echo $title_id; ?>"><?php _e('Title:'); ?></label></td>
  </tr>
  <tr>
    <td><input class="widefat" id="<?php echo $title_id; ?>" name="<?php echo $title_name; ?>" type="text" value="<?php echo $title; ?>" /></td>
  </tr>
  <tr>
    <td><label for="<?php echo $body_id; ?>"><?php _e('Body:'); ?></label></td>
  </tr>
  <tr>
    <td><textarea class="widefat" id="<?php echo $body_id; ?>" name="<?php echo $body_name; ?>"><?php echo $body; ?></textarea></td>
  </tr>
  <tr>
    <td><label for="<?php echo $file_link; ?>"><?php _e('ID of page you wish to link to:'); ?></label></td>
  </tr>
  <tr>
    <td><input class="widefat" id="<?php echo $file_link_id; ?>" name="<?php echo $file_link_name; ?>" type="text" value="<?php echo $file_link; ?>" /></td>
  </tr>
  <tr>
    <td><label for="<?php echo $btn_label; ?>"><?php _e('Text For Button:'); ?></label></td>
  </tr>
  <tr>
    <td><input class="widefat" id="<?php echo $btn_label_id; ?>" name="<?php echo $btn_label_name; ?>" type="text" value="<?php echo $btn_label; ?>" /></td>
  </tr>
  <tr>
    <td><label for="<?php echo $img_name; ?>"><?php _e('Name of image file with file extension: </br>ex. <i>yourfilename.jpg</i>'); ?></label></td>
  </tr>
  <tr>
    <td><input class="widefat" id="<?php echo $img_name_id; ?>" name="<?php echo $img_name_name; ?>" type="text" value="<?php echo $img_name; ?>" /></td>
  </tr>
       </table>
        	
<?php
	
}
 
public function update( $new_instance, $old_instance ) {
/*This fucntion is for saving the data from the form in the backadmin widget. Notice that here the data is being saved and passed from function to function in the form of the $instance variable*/	
// processes widget options to be saved
	$instance = $old_instance;
	$instance['title']     = $new_instance['title'];
	$instance['body']      = $new_instance['body'];
	$instance['img_name']  = $new_instance['img_name'];
    $instance['file_link'] = $new_instance['file_link'];
	$instance['btn_label'] = $new_instance['btn_label'];
	$instance['img_name']  = $new_instance['img_name'];
    return $instance;
}
 

}
add_action('widgets_init', create_function('', 'return register_widget("TRC_Dowload_Tiles_Widget");'));




