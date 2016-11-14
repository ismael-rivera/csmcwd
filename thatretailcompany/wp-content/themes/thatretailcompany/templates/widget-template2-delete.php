<?php
/*
Plugin Name: Promo Tiles Widget
Plugin URI: http://speggo.com/
Description: Custom Fill in the Blanks Widget for the Promotiles in the sidebars specifically created for thatretailcompany.com.au website.
Author: Ismael Rivera
Version: 1.0.0
Author URI: http://speggo.com/
*/

/**
 * Authors Widget Class
 */
 
// we can only use this Widget if the plugin is active
 
class Promo_Tiles_Widget extends WP_Widget {
	
	var $image_field = 'image';  // the image field ID

    /** constructor */
    function Promo_Tiles_Widget() {
		
		$widget_options = array(
			'classname' => 'promo-tiles-widget',
			'description' => 'Just a simple widget'
			);
			
			parent::WP_Widget('promo_tiles_widget', 'Promo Tiles Widget', $widget_options);
		
        
    }	
 
	public function form() {
	// outputs the options form on admin
	
	$title   = esc_attr( isset( $instance['title'] ) ? $instance['title'] : '' );
	$body   = esc_attr( isset( $instance['body'] ) ? $instance['body'] : '' );
	$image_id   = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
	$image      = new WidgetImageField( $this, $image_id );
	?>
    <p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
    
	<?php _e('Title:'); ?>
    
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    
    </label>
    </p>
    
    <p>
    
    <label for="<?php echo $this->get_field_id('body'); ?>">
	
	<?php _e('Body:'); ?>
    
    <textarea id="<?php echo $this->get_field_id('body'); ?>" name="<?php echo $this->get_field_name('count'); ?>"><?php esc_attr($instance['body']); ?></textarea>
        
     </label>
     
     <label><?php _e( 'Image:' ); ?></label>
	<?php echo $image->get_widget_field(); ?>
    
    <?php
	}
	 
	public function update( $new_instance, $old_instance ) {
	// processes widget options to be saved
	
	$instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
		$instance['body']  = strip_tags( $new_instance['body'] );
		$link   = $instance['link'];
        $instance[$this->image_field] = intval( strip_tags( $new_instance[$this->image_field] ) );

        return $instance;
	
	}
	 
	public function widget($args, $instance) {
        extract( $args, EXTR_SKIP );
		global $wpdb;
		
		$title     = ($instance['title'])    ? $instance['title']    : 'A simple Widget';
		$body      = ($instance['body'])     ? $instance['body']     : 'A simple Widget';
		$post_ID   = ($instance['post_ID'])  ? $instance['post_ID']  : 'A simple Widget';
		$btn_label = ($instance['btn_label'])? $instance['btn_label']: 'A simple Widget';
		$img_name  = ($instance['img_name']) ? $instance['img_name'] : 'A simple Widget';
		
		?>
        
        <?php echo $before_widget; ?>
        <?php echo $before_title . $title . $after_title; ?>
        <div class="promo_tile_content">
        <p><?php echo $body; ?></p>
        </div>
        <?php echo do_shortcode("[promo-link post_id='".$post_ID."' label='".$btn_label."']"); ?>
        <div class="promo_tile_icon">
        <?php echo do_shortcode("[promo-icon icon=".$img_name."]"); ?>
        </div>
        <?php echo $after_widget; ?>
        
        <?php

		
    }
}  

// class utopian_recent_posts

//if( class_exists( 'WidgetImageField' ) ) {
    add_action('widgets_init', create_function('', 'return register_widget("Promo_Tiles_Widget");'));
//	}