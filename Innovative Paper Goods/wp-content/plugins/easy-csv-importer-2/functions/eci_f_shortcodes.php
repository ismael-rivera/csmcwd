<?php
# install shortcodes
add_shortcode( 'ecispin', 'eci_shortcodespinning' );
add_shortcode( 'ecilab', 'eci_shortcodelabel' );
add_shortcode( 'ecicol', 'eci_shortcodecolumn' );
//add_shortcode( 'ecisc', 'eci_shortcodeblock' );

# Shortcode function - text spinning, randomly selects one of multiple values passed
function eci_shortcodespinning( $atts )
{
	return 'Shortcode Spinning Not Available In Free Edition';
}

# Shortcode function - Displays a label and a value, user has option to display default value or remove label if data is null
function eci_shortcodelabel( $atts )
{
	// globalise current post
	global $post;

	// get current posts ID
	if ( !$post->ID )
	{
		return '';
	}
	else
	{	
		// get all custom fields for this post
		$custom_fields = get_post_custom( $post->ID );
	
		// extract the column attribute value
		extract( shortcode_atts( array(
			'column' => 'nocolumnsubmitted',
			'label' => 'Label Name',
			'nullaction' => 'swap',
			'nullswap' => 'Unknown',
		), $atts ) );
		
		// ensure a column was provided otherwise return a null value
		if( $column == 'nocolumnsubmitted' )
		{
			return '';
		}
		else
		{
			$my_custom_field = $custom_fields[ $column ];
			
			if( isset( $my_custom_field ) )
			{
				####  DOES THIS NEED TO BE LOOPED - IT IS A SINGLE VALUE WE ARE RETURNING
				foreach ( $my_custom_field as $key => $value )
				{
					$null = false;
										
					if( $value == NULL  || $value == ' ' || $value == '' )
					{
						$null = true; 
					}
					
					// if the column data value is empty and shortcode nullaction is delete then return nothing
					if( $null == true && $nullaction == 'delete' )
					{
						return '';
					}
					elseif( $null == true && $nullaction == 'swap' )
					{
						// nullaction is swap so we use the $nullswap variable as value
						return $label.$nullswap;
					}
					elseif( $null == false )
					{		
						return $label.$value;
					}
				}
			}
		}
	}
}

# Shortcode function - replaces shortcode with value from custom field named the same as key
function eci_shortcodecolumn( $atts ) 
{
	// globalise current post
	global $post;
	
	// get current posts ID
	// get current posts ID
	if ( !$post->ID )
	{
		return '';
	}
	else
	{	
		// get all custom fields for this post
		$custom_fields = get_post_custom( $post->ID );
	
		// extract the column attribute value
		extract( shortcode_atts( array(
			'column' => 'column',
		), $atts ) );
				
		$my_custom_field = $custom_fields[ $column ];
		
		if( isset( $my_custom_field ) )
		{
			####  DOES THIS NEED TO BE LOOPED - IT IS A SINGLE VALUE WE ARE RETURNING
			foreach ( $my_custom_field as $key => $value )
			{
				return $value;
			}
		}
	} 
}
?>