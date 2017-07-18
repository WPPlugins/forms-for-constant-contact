<?php
/*
Plugin Name: Forms for Constant Contact
Description: An easy way to place a constant contact forms on your Wordpress site.
Version: 1.0
Author: Jeff Bullins
Author URI: http://www.thinklandingpages.com
*/

include_once 'settings.php';

include_once 'custom-post-type.php';  

function ffccns_activate() {
	$theCustomPostType = new ConstantContactFormsCustomPostType();
	$theCustomPostType->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules(true);
}

register_activation_hook( __FILE__, 'ffccns_activate' );
 