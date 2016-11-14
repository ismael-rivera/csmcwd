<?php 
class Realforms{

function __construct() {
   }
	
	function rform_open($rf_fatts=NULL){
	if(!empty($rf_fatts)){	
		$output = '<form ';
		foreach($rf_fatts as $key => $value){
        $output .= $key . '="' . $value . '" ';
		}
		$output .= ">";
		}
	else $output = '<form>';
	echo $output;	
	}
		
	function rform_input($rf_iatts){	
			
	if(!empty($rf_iatts)){
       $output = "<input ";
       foreach($rf_iatts as $key => $value){
       $output .= $key . '="' . $value . '" ';
       }
       $output .= "/>";
	   echo $output;
       }
   }
   
   function rform_checkbox($name, $value, $text=NULL, $rf_iatts=NULL){	
       $output = "<input type='checkbox' name='".$name."' value='".$value."'";
	   if(!empty($rf_iatts)){
       foreach($rf_iatts as $key => $value){
       $output .= $key . '="' . $value . '" ';
       }
	   }
       $output .= "/>";
	if(!empty($text)){
	   $output .= $text;
	}
	   echo $output;
    }
   
	
	
	function rform_textarea($rf_taratts = NULL, $text_in_box = NULL){	
			
	if(!empty($rf_taratts)){
       $output = "<textarea ";
       foreach($rf_taratts as $key => $value){
       $output .= $key . '="' . $value . '" ';
       }
       $output .= ">";
	   $output .= $text_in_box;
	   $output .= "</textarea>";
	   echo $output;
       }
   }
	
	function rform_select($name){
		if(!empty($rf_taratts)){
       $output = "<select ";
       foreach($rf_taratts as $key => $value){
       $output .= $key . '="' . $value . '" ';
       }
       $output .= ">";
	   $output .= $text_in_box;
	   $output .= "</select>";
	   echo $output;
       }
		}
	
	

	function real_label(){}
	function real_fieldset(){}
	function real_legend(){}
	function real_optgroup(){}
	function real_option(){}
	function real_button(){}
	function real_datalist(){}
	function real_keygen(){}
	function real_output(){}

		
		

		function rform_close(){
		
		$output = '</form>'; 	
		echo $output;	
		}
			
}
