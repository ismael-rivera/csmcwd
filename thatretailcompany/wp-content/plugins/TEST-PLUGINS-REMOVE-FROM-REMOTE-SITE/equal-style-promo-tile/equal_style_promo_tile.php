<?php
/*
Plugin Name: ESPT - Equal Style Promo Tile
Plugin URI: http://www.speggo.com/
Description: Display your promos in equally consistent visual beauty
Version: 1.3.2
Author: Ismael Rivera
Author URI: http://www.speggo.com
License: GPL2
*/

/*  Copyright 2011  Dave Clements  (email : http://www.theukedge.com/contact/)

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

/*-----

Developers Notes:

Code reference for building this Widget was taken from following URL:

http://www.doitwithwp.com/how-to-create-wordpress-widget-step-by-step/

---*/

// Start class soup_widget //

class espt_widget extends WP_Widget {

// Constructor //

	function espt_widget() {
		
		$widget_ops = array( 'classname' => 'espt_widget', 'description' => 'Display your promos in equally consistent visual beauty' ); 
		
		// Widget Settings
		
		$control_ops = array( 'id_base' => 'espt_widget' ); 
		
		// Widget Control Settings
		
		$this->WP_Widget( 'espt_widget', 'Upcoming', $widget_ops, $control_ops ); 
		
		// Create the widget
	}
}