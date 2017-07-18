<?php

/*
*Instruction - if you are using this as a template for a plugin, change the class name, the call to create an object from this class *at the bottom, and modify the private variables to meet your needs.
*/

class ConstantContactFormsCustomPostType{

private $post_type = 'constantcontactforms';
private $post_label = 'Constant Contact Forms';
private $prefix = '_constant_contact_forms_';
function __construct() {
	
	add_filter( 'cmb_meta_boxes', array(&$this,'metaboxes' ));
	add_action( 'init', array(&$this,'initialize_meta_boxes'), 9999 );
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'constant_contact_forms_register_shortcodes'));
	add_action( 'wp_footer', array(&$this, 'enqueue_styles'));
	add_action( 'wp_footer', array(&$this, 'enqueue_scripts'));
	//add_filter('single_template', array(&$this,'custom_template'));
	add_action('template_redirect', array(&$this,'template_redirect' ));
	//register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}


/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function metaboxes( array $meta_boxes ) {
	
	// Start with an underscore to hide fields from custom fields list
	//$prefix = '_getresponse_form_creator_';
	

	$meta_boxes[$this->prefix . 'metabox'] = array(
		'id'         => $this->prefix . 'metabox',
		'title'      => $this->post_label,
		'pages'      => array( $this->post_type ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Headline',
				'desc' => 'Enter your headline here.',
				'id'   => $this->prefix . 'headline',
				'type' => 'text',
			),
			array(
				'name'    => 'Message',
				'id'      => $this->prefix . 'message',
				'type'    => 'wysiwyg',
				'options' => array(	'textarea_rows' => 20, 'wpautop' => true ),
				
			),
		        array(
				'name' => 'Button Text',
				'desc' => 'Enter the form button text here.',
				'id'   => $this->prefix . 'button_text',
				'type' => 'text',
				'std'  => 'Get It',
			),
			array(
				'name' => 'Constant Contact ca',
				'desc' => 'Enter the Constant Contact ca (Campaign Activity ID) here. <a href="http://techblog.constantcontact.com/api/release-updates/announcing-a-new-signup-form-post-url/">Visit Constant Contact to learn more.</a>',
				'id'   => $this->prefix . 'cc_ca',
				'type' => 'text',
			),
			array(
				'name' => 'Constant Contact list',
				'desc' => 'Enter the Constant Contact list here.',
				'id'   => $this->prefix . 'cc_list',
				'type' => 'text',
			),
			array(
				'name' => 'Redirect URL',
				'desc' => 'URL to redirect the subscriber to after successfully submitting subscription.',
				'id'   => $this->prefix . 'cc_url',
				'type' => 'text',
			),

		),
	);

	$meta_boxes =  apply_filters('flg_add_fields', $meta_boxes, $this->prefix, $this->prefix . 'metabox');

	// Add other metaboxes as needed

	return $meta_boxes;
}

function custom_template($single) {
    global $wp_query, $post;

    /* Checks for single template by post type */
    if ($post->post_type == $this->post_type){
    	$dir = plugin_dir_path( __FILE__ );
        if(file_exists(dir . '/displayTemplate.php'))
            return dir . '/displayTemplate.php';
    }
    return $single;
}

function template_redirect(){
	global $wp_query, $post;
	if ($post->post_type == $this->post_type){
		$dir = plugin_dir_path( __FILE__ );
		include $dir . '/template/displayTemplate.php';
		die();
	}

}


function constant_contact_forms_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => '',
		), $atts ) );
		//$meta_data = get_post_meta( $id, $this->prefix . 'adsense_code', true );
		//$meta_data = get_post_meta($id);
		$dir = plugin_dir_path( __FILE__ );

		$headline = get_post_meta($id, $this->prefix . 'headline', true);
		$background_color = get_post_meta($id, $this->prefix . 'background_color', true);
		$image = get_post_meta($id, $this->prefix . 'image', true);
		$message = get_post_meta($id, $this->prefix . 'message', true);
		$button_color = get_post_meta($id, $this->prefix . 'button_color', true);
		$button_text = get_post_meta($id, $this->prefix . 'button_text', true);
		$cc_ca = get_post_meta($id, $this->prefix . 'cc_ca', true);
		$cc_list = get_post_meta($id, $this->prefix . 'cc_list', true);
		$cc_url = get_post_meta($id, $this->prefix . 'cc_url', true);
		
		ob_start();
		include $dir.'template/constantContactFormsTemplate.php';
		return ob_get_clean();
}



function constant_contact_forms_register_shortcodes(){
		add_shortcode( 'constant_contact_forms', array(&$this,'constant_contact_forms_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function enqueue_styles(){
	wp_register_style( 'constantContactForms-css', plugin_dir_url(__FILE__).'css/constantContactForms.css' );
	wp_enqueue_style('constantContactForms-css');
}

function enqueue_scripts(){
	wp_enqueue_script('constantContactForms-js', plugin_dir_url(__FILE__).'js/constantContactForms.js');
}


/*
 * Initialize the metabox class.
 */
 
function initialize_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'lib/metabox/init.php';

}


}

new ConstantContactFormsCustomPostType();



?>