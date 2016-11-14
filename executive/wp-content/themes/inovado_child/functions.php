<?php

show_admin_bar(FALSE);

add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );  
    function custom_image_sizes_choose( $sizes ) {  
        $custom_sizes = array(  
            'thumbnail-02' => 'Thumbnail 2'  
        );  
        return array_merge( $sizes, $custom_sizes );  
    }
	
function calltoAction_func( $atts, $content = null ) { 
      extract(shortcode_atts(array(  
        "class" => 'ctoa',
		"button" => '',
		  
       ), $atts)); 
      return '<div class="'.$class.'">'.$content.'</div>';  
    }  
	
add_shortcode("calltoaction", "calltoAction_func"); 


/*-----------------------------------------------------------------------------------*/
/*Exec Child Pricing Table */
/*-----------------------------------------------------------------------------------*/

function exec_minti_plan( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'name'      => 'Premium',
		'link'      => 'http://www.google.de',
		'linkname'      => 'Sign Up',
		'price'      => '39.00$',
		'per'      => false,
		'color'    => false, // grey, green, red, blue
		'featured' => ''
    ), $atts));
    
    if($featured != '') {
    	$return = "<div class='featured' style='background-color:".$color.";'>".$featured."</div>";
    }
    else{
	    $return = "";
    }

    if($per != false) {
    	$return3 = "".$per."";
    }
    else{
    	$return3 = "";
    }
    $return5 = "";
    if($color != false) {
    	if($featured == true){
    		$return5 = "style='color:".$color.";' ";
    	}
    	$return4 = "style='color:".$color.";' ";
    }
    else{
    	$return4 = "";
    }
	
	$out = "
		<div class='plan'>	
			".$return."
			<div class='plan-head'><h3 ".$return4.">".$name."</h3>
			<div class='price' ".$return4.">".$price." <br /><span>".$return3."</span></div></div>
			<ul>" .do_shortcode($content). "</ul><div class='signup'><a class='button' href='".$link."'>".$linkname."<span></span></a></div>
		</div>";
    return $out;
}

add_shortcode('xplan', 'exec_minti_plan');
/*-----------------------------------------------------------------------------------*/

/*Start Elastislide Carousel*/
function carousel_slide($atts){	
extract(shortcode_atts(array(
		'images'            => '',
		'urls'              => ''
    ), $atts));
//explode( ', ', $images );	
$uploads = wp_upload_dir();
$output  =	'<div id="carousel" class="page es-carousel-wrapper">';
$output .=					'<div class="es-carousel">';
$output .=						'<ul>';
//$i = 0;
$getimages = new ArrayIterator(preg_split("/[\s,]+/", $images ));
$geturls  = new ArrayIterator(preg_split("/[\s,]+/", $urls ));
$flags = MultipleIterator::MIT_NEED_ANY|MultipleIterator::MIT_KEYS_ASSOC;
$li = new MultipleIterator($flags);
$li->attachIterator($getimages, 'img_src');
$li->attachIterator($geturls, 'link');

foreach ($li as $a) {
    //$output .= print_r($imagelink);
	$output .= '<li><a href="'.$a['link'].'" target="_blank"><img src="'. $uploads['baseurl'] . '/parceiros/' . $a['img_src'] . '" alt="parceiro" /></a></li>';
}

/*foreach(explode( ', ', $images ) as $image){
$output .=							'<li><a href="http://demos.speggo.com/confidentempoweredwoman/" target="_blank"><img src="' . $uploads['baseurl'] . '/parceiros/'.$image.'" alt="parceiro" /></a></li>';
}*/
$output .=						'</ul>';
                        
$output .=				'</div>';
$output .=             '<span>Click Arrows To Scroll</span>';
$output .=				'</div>';

return $output;
}

add_shortcode('carousel', 'carousel_slide');
                
/*End Elastislide Carousel*/

	