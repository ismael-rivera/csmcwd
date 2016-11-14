<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'minti_';

global $meta_boxes;

$meta_boxes = array();

global $data;

/* ----------------------------------------------------- */

$revolutionslider = array();
$revolutionslider[0] = 'No Slider';

if(class_exists('RevSlider')){
    $slider = new RevSlider();
	$arrSliders = $slider->getArrSliders();
	foreach($arrSliders as $revSlider) { 
		$revolutionslider[$revSlider->getAlias()] = $revSlider->getTitle();
	}
}

$types = get_terms('portfolio_filter', 'hide_empty=0');
$types_array[0] = 'All categories';
if($types) {
	foreach($types as $type) {
		$types_array[$type->term_id] = $type->name;
	}
}


/* ----------------------------------------------------- */
// Page Settings
/* ----------------------------------------------------- */

$meta_boxes[] = array(
	'id' => 'pagesettings',
	'title' => 'Page Settings',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',

	// List of meta fields
	'fields' => array(
		array(
				'name'		=> 'Title Bar',
				'id'		=> $prefix . "titlebar",
				'type'		=> 'select',
				'options'	=> array(
					'titlebar'			=> 'Title & Subtitle',
					'featuredimage'	=> 'Featured Image',
					'notitlebar'			=> 'No Title Bar'
				),
				'multiple'	=> false,
				'std'		=> array( 'title' )
		),
		array(
			'name'		=> 'Subtitle',
			'id'		=> $prefix . 'subtitle',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		array(
			'name'		=> 'Show Breadcrumbs?',
			'id'		=> $prefix . "featuredimage-breadcrumbs",
			'type'		=> 'checkbox',
			'std'		=> true
		),
		array(
			'name'		=> 'Slider',
			'id'		=> $prefix . "revolutionslider",
			'type'		=> 'select',
			'options'	=> $revolutionslider,
			'multiple'	=> false,
			'desc'		=> 'Note: This overwrites "Title Bar" Setting if Slider is selected.',
		),
		array(
			'name' => 'Select Portfolio Filters',
			'id' => $prefix . "portfoliofilter",
			'type' => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options' => $types_array,
			// Select multiple values, optional. Default is false.
			'multiple' => true,
			'desc' => 'Optional: Choose what portfolio category you want to display on this page (If Portfolio Template chosen).'
		)
	)
);
/* ----------------------------------------------------- */
// Background Styling
/* ----------------------------------------------------- */

if($data['select_layoutstyle'] == 'Boxed Layout' || $data['select_layoutstyle'] == 'Boxed Layout with margin' ) {

	$meta_boxes[] = array(
		'id' => 'styling',
		'title' => 'Background Styling Options',
		'pages' => array( 'post', 'page', 'portfolio' ),
		'context' => 'side',
		'priority' => 'low',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'		=> 'Background Image URL',
				'id'		=> $prefix . 'bgurl',
				'desc'		=> '',
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'Style',
				'id'		=> $prefix . "bgstyle",
				'type'		=> 'select',
				'options'	=> array(
					'stretch'		=> 'Stretch Image',
					'repeat'		=> 'Repeat',
					'no-repeat'		=> 'No-Repeat',
					'repeat-x'		=> 'Repeat-X',
					'repeat-y'		=> 'Repeat-Y'
				),
				'multiple'	=> false,
				'std'		=> array( 'stretch' )
			),
			array(
				'name'		=> 'Background Color',
				'id'		=> $prefix . "bgcolor",
				'type'		=> 'color'
			)
		)
	);

}

/* ----------------------------------------------------- */
// Project Info Metabox
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'portfolio_info',
	'title' => 'Project Information',
	'pages' => array( 'portfolio' ),
	'context' => 'normal',
	
	

	'fields' => array(
		array(
				'name'		=> 'Title Bar',
				'id'		=> $prefix . "titlebar",
				'type'		=> 'select',
				'options'	=> array(
					'titlebar'			=> 'Title & Subtitle',
					'notitlebar'			=> 'No Title Bar'
				),
				'multiple'	=> false,
				'std'		=> array( 'title' )
		),
		array(
			'name'		=> 'Subtitle',
			'id'		=> $prefix . 'subtitle',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),	
		array(
			'name'		=> 'Client',
			'id'		=> $prefix . 'portfolio-client',
			'desc'		=> 'Leave empty if you do not want to show this.',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		array(
			'name'		=> 'Project link',
			'id'		=> $prefix . 'portfolio-link',
			'desc'		=> 'URL to the Project if available (Do not forget the http://)',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		array(
			'name'		=> 'Detail Layout',
			'id'		=> $prefix . 'portfolio-detaillayout',
			'desc'		=> 'Choose Layout for Detailpage',
			'type'		=> 'select',
			'options'	=> array(
				'wide'			=> 'Full Width',
				'sidebyside'	=> 'Side by Side'
			),
			'multiple'	=> false,
			'std'		=> array( 'no' )
		),
		array(
			'name'		=> 'Show Project Details?',
			'id'		=> $prefix . "portfolio-details",
			'type'		=> 'checkbox',
			'std'		=> true
		),
		array(
			'name'		=> 'Show Related Projects?',
			'id'		=> $prefix . "portfolio-relatedposts",
			'type'		=> 'checkbox',
			'desc'		=> 'This overwrites the global settings from Theme Options'
		),
		array(
				'name'		=> 'Link to Lightbox',
				'id'		=> $prefix . "portfolio-lightbox",
				'type'		=> 'select',
				'options'	=> array(
					'false'		=> 'false',
					'true'		=> 'true'
				),
				'multiple'	=> false,
				'std'		=> array( 'false' ),
				'desc'		=> 'Open Image in a Lightbox (on Overview, Homepage & Related Posts)'
		)
	)
);

/* ----------------------------------------------------- */
// Project Slides Metabox
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id'		=> 'portfolio_slides',
	'title'		=> 'Project Slides',
	'pages'		=> array( 'portfolio' ),
	'context' => 'normal',

	'fields'	=> array(
		array(
			'name'	=> 'Project Slider Images',
			'desc'	=> 'Upload up to 20 project images for a slideshow - or only one to display a single image. <br /><br /><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image.',
			'id'	=> $prefix . 'screenshot',
			'type'	=> 'plupload_image',
			'max_file_uploads' => 20,
		)
		
	)
);

/* ----------------------------------------------------- */
// Project Video Metabox
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id'		=> 'portfolio_video',
	'title'		=> 'Project Video',
	'pages'		=> array( 'portfolio' ),
	'context' => 'normal',

	'fields'	=> array(
		array(
			'name'		=> 'Video Source',
			'id'		=> $prefix . 'source',
			'type'		=> 'select',
			'options'	=> array(
				'youtube'		=> 'Youtube',
				'vimeo'			=> 'Vimeo',
				'own'			=> 'Own Embed Code'
			),
			'multiple'	=> false,
			'std'		=> array( 'no' )
		),
		array(
			'name'	=> 'Video URL or own Embedd Code<br />(Audio Embedd Code is possible, too)',
			'id'	=> $prefix . 'embed',
			'desc'	=> 'Just paste the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>GUEZCxBcM78</strong>) you want to show, or insert own Embed Code. <br />This will show the Video <strong>INSTEAD</strong> of the Image Slider.<br /><strong>Of course you can also insert your Audio Embedd Code!</strong><br /><br /><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image..',
			'type' 	=> 'textarea',
			'std' 	=> "",
			'cols' 	=> "40",
			'rows' 	=> "8"
		)
	)
);

/* ----------------------------------------------------- */

/*
// 2nd meta box
$meta_boxes[] = array(
	'id'		=> 'additional',
	'title'		=> 'Additional Information',
	'pages'		=> array( 'post', 'film', 'project' ),

	'fields'	=> array(
		// WYSIWYG/RICH TEXT EDITOR
		array(
			'name'	=> 'Your thoughts about Deluxe Blog Tips',
			'id'	=> "{$prefix}thoughts",
			'type'	=> 'wysiwyg',
			'std'	=> "It's great!",
			'desc'	=> 'Do you think so?'
		),
		// FILE UPLOAD
		array(
			'name'	=> 'Upload your source code',
			'desc'	=> 'Any modified code, or extending code',
			'id'	=> "{$prefix}code",
			'type'	=> 'file'
		),
		// IMAGE UPLOAD
		array(
			'name'	=> 'Screenshots',
			'desc'	=> 'Screenshots of problems, warnings, etc.',
			'id'	=> "{$prefix}screenshot",
			'type'	=> 'image'
		),
		// PLUPLOAD IMAGE UPLOAD (WP 3.3+)
		array(
			'name'	=> 'Screenshots (plupload)',
			'desc'	=> 'Screenshots of problems, warnings, etc.',
			'id'	=> "{$prefix}screenshot2",
			'type'	=> 'plupload_image',
			'max_file_uploads' => 1,
		),
		// THICKBOX IMAGE UPLOAD (WP 3.3+)
		array(
			'name'	=> 'Screenshots (thickbox upload)',
			'desc'	=> 'Screenshots of problems, warnings, etc.',
			'id'	=> "{$prefix}screenshot3",
			'type'	=> 'thickbox_image',
		)
	)
);

// 3rd meta box
$meta_boxes[] = array(
	'id'		=> 'survey',
	'title'		=> 'Survey',
	'pages'		=> array( 'post', 'slider', 'project' ),

	'fields'	=> array(
		// COLOR
		array(
			'name'		=> 'Your favorite color',
			'id'		=> "{$prefix}color",
			'type'		=> 'color'
		),
		// CHECKBOX LIST
		array(
			'name'		=> 'Your hobby',
			'id'		=> "{$prefix}hobby",
			'type'		=> 'checkbox_list',
			// Options of checkboxes, in format 'key' => 'value'
			'options'	=> array(
				'reading'	=> 'Books',
				'sport'		=> 'Gym, Boxing'
			),
			'desc'		=> 'What do you do in free time?'
		),
		// TIME
		array(
			'name'		=> 'When do you get up?',
			'id'		=> "{$prefix}getdown",
			'type'		=> 'time',
			// Time format, default hh:mm. Optional. @link See: http://goo.gl/hXHWz
			'format'	=> 'hh:mm:ss'
		),
		// DATETIME
		array(
			'name'		=> 'When were you born?',
			'id'		=> "{$prefix}born_time",
			'type'		=> 'datetime',
			// Time format, default hh:mm. Optional. @link See: http://goo.gl/hXHWz
			'format'	=> 'hh:mm:ss'
		),
		// TAXONOMY
		array(
			'name'    => 'Categories',
			'id'      => "{$prefix}cats",
			'type'    => 'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy'	=> 'category',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type'		=> 'select_tree',
				// Additional arguments for get_terms() function. Optional
				'args'		=> array()
			),
			'desc'		=> 'Choose One Category'
		)
	)
);

*/

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function YOUR_PREFIX_register_meta_boxes()
{
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'YOUR_PREFIX_register_meta_boxes' );