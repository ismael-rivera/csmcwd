<?php
/*
Plugin Name: TRC Testimonials Widget
Plugin URI: http://thatretailcompany.com/
Description: Custom Plugin for testimonials display widget from the Testimonials section of 
That Retail Company website 
Author: Ismael Rivera
Version: 1
Author URI: http://speggo.me/
*/
 
 
class TrcTestimonialsWidget extends WP_Widget
{
  function TrcTestimonialsWidget()
  {
    $widget_ops = array('classname' => 'TrcTestimonialsWidget', 'description' => 'Displays recent testimonials from the testimonials page of That Retail Company website' );
    $this->WP_Widget('TrcTestimonialsWidget', 'TRC - Recent Testimonials', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    echo "<h1>This is my new widget!</h1>";
 
    echo $after_widget;
  }
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("TrcTestimonialsWidget");') );?>