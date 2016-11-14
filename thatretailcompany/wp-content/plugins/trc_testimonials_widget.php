<?php
/*
Plugin Name: TRC Testimonials Widget
Plugin URI: http://speggo.com/
Description: Testimonials Widget. Not available in the Wordpress plugins repository. Custom built code by the author.
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

 
class TRC_Testimonials_Widget extends WP_Widget {
 
public function TRC_Testimonials_Widget() {
/* This function activates the widget in the back admin	*/
// widget actual processes 

		$widget_ops = array(
		  'classname' => 'trc_testimonials_widget',
		  'description' => 'Displays a promotile using specified fields'
		);
				
    	$this->WP_Widget('TRC_Testimonials_Widget', 'TRC Testimonials Widget', $widget_ops);

}

public function widget($args, $instance) {
/* This function is for the output. Here is where we write the code we would like to see outputted in the frontend.	*/	
        extract( $args, EXTR_SKIP );
		global $wpdb;
		
		$title     = ($instance['title'])     ? $instance['title']     : '';
		$body      = ($instance['body'])      ? $instance['body']      : '';
		$post_link = ($instance['post_link']) ? $instance['post_link'] : '';
		$btn_label = ($instance['btn_label']) ? $instance['btn_label'] : '';
		$img_name  = ($instance['img_name'])  ? $instance['img_name']  : '';
		
		?>
        
        <?php echo $before_widget; ?>
        <?php echo $before_title . $title . $after_title; ?>
        <?php echo testimonials_widget(); ?>
        <?php echo $after_widget; ?>

        <?php
		
    }
 
public function form( $instance ) {}
 
public function update( $new_instance, $old_instance ) {}
 

}
add_action('widgets_init', create_function('', 'return register_widget("TRC_Testimonials_Widget");'));




