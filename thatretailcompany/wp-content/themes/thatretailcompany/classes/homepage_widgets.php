<?php

class Homepage_Widgets {
	
	static $count = 0;
	//public $name;
	
	function Homepage_Widgets(){
		
		/*// Area 1, located in the sidebar. Empty by default.
		 register_sidebar( array(
			'name'          => __( 'Home-Promo-Tiles-Area', 'twentyten' ),
			'id'            => 'home-promo-tiles-area',
			'description'   => __( 'The homepage promo widget area', 'twentyten' ),
			'before_widget' => $this->before_widget() .'<td class="promotiles"><div class="promotiles_inner">',
			'after_widget'  => $this->after_widget() . '</div></td><td class="cellspace1">&nbsp;</td>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );*/
		
		
		}
	
	function before_widget(){
		
		self::$count++;
			
		return '<td class="promotiles"><div class="promotiles_inner">' && self::$count++;
		
		
		}
		
		
	function after_widget(){
		
			
		return '</div></td><td class="cellspace1">&nbsp;</td>';
	
	   }
			
		
		
	
}