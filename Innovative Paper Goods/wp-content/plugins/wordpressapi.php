<?php
/*
Plugin Name:  WordPressapi Meta Box
Plugin URI: http://images.wordpressapi.com/
Description: Adding the Meta box to admin panel -> post
Version: 0.0
Author: WordPressapi
Author URI: http://images.wordpressapi.com/
*/
 
function wordpressapi_meta_box() {
add_meta_box(
'wordpressapi',
'Wordpressapi Box', //Meta box title
'write_in_meta_box',
'products' // You can define here where to add the Meta box(post, page, link)
);
}
 
function write_in_meta_box(){
echo "Wordpressapi is writing something in admin meta box";
}
 
if (is_admin())
add_action('admin_menu', 'wordpressapi_meta_box');
?>