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
if( class_exists( 'WidgetImageField' ) ) {
    add_action( 'widgets_init', create_function( '', "register_widget( 'Promo_Tiles_Widget' );" ) );
	}
 
class Promo_Tiles_Widget extends WP_Widget {

    /** constructor */
    function Promo_Tiles_Widget() {
        parent::WP_Widget(false, $name = 'TRC Promo Tiles');
    }
	
	/** @see WP_Widget::form */
    function form($instance) {	
		$title   = esc_attr( isset( $instance['title'] ) ? $instance['title'] : '' );
		$body    = esc_attr( isset( $instance['body'] ) ? $instance['body'] : '' );
		$image_id   = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
		$image      = new WidgetImageField( $this, $image_id );

        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

		<p>
        <label for="<?php echo $this->get_field_id('body'); ?>"><?php _e('Body:'); ?></label>
        <textarea id="<?php echo $this->get_field_id('body'); ?>" 
        name="<?php echo $this->get_field_name('count'); ?>">
		<?php esc_attr($instance['body']); ?>
        </textarea>
          
        </p>

		<p>
        <label for="<?php echo $this->get_field_id('gravatar'); ?>"><?php _e('Display Author Gravatar?'); ?></label>
        <input 
        id="<?php echo $this->get_field_id('gravatar'); ?>" 
        name="<?php echo $this->get_field_name('gravatar'); ?>" 
        type="checkbox" 
        value="1" <?php checked( '1', $gravatar ); ?>/>
        </p>

        <?php
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args, EXTR_SKIP );
		global $wpdb;
		
		/*
		$title = apply_filters('widget_title', $instance['title']);
		$body = $instance['body'];
		$link = $instance['link'];
		$icon = $instance['icon'];
		*/

        /*$title = apply_filters('widget_title', $instance['title']);
		$gravatar = $instance['gravatar'];
		$count = $instance['count'];*/

		if(!$size)
			$size = 40;
?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
							<?php

								$authors = $wpdb->get_results("SELECT ID FROM $wpdb->users ORDER BY ID");

								foreach($authors as $author) {

									$author_info = get_userdata($author->ID);

									echo '<li>';

										echo '<div style="float: left; margin-left: 5px;">';

										echo get_avatar($author->ID, 40);

										echo '</div>';

										echo '<a href="' . get_author_posts_url($author->ID) .'" title="View author archive">';
											echo $author_info->display_name;
											if($count) {
												echo '(' . count_user_posts($author->ID) . ')';
											}
										echo '</a>';

									echo '</li>';
								}
							?>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['gravatar'] = strip_tags($new_instance['gravatar']);
		$instance['count'] = strip_tags($new_instance['count']);
        return $instance;
    }

    

} // class utopian_recent_posts
function promo_tiles_widget_init(){
	register_widget("Promo_Tiles_Widget");
	
	}
add_action('widgets_init','promo_tiles_widget_init');
