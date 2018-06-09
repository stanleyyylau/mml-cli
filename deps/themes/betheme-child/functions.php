<?php

/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );


/* ---------------------------------------------------------------------------
 * Define | YOU CAN CHANGE THESE
 * --------------------------------------------------------------------------- */

// White Label --------------------------------------------
define( 'WHITE_LABEL', false );

// Static CSS is placed in Child Theme directory ----------
define( 'STATIC_IN_CHILD', false );


/* ---------------------------------------------------------------------------
 * Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );
function mfnch_enqueue_styles() {
	
	// Enqueue the parent stylesheet
// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );		//we don't need this if it's empty
	
	// Enqueue the parent rtl stylesheet
	if ( is_rtl() ) {
		wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );
	}
	
	// Enqueue the child stylesheet
	wp_dequeue_style( 'style' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );
	wp_enqueue_style( 'app', get_stylesheet_directory_uri() .'/dist/css/main.css', null, microtime() );
	
}


/* ---------------------------------------------------------------------------
 * Load Textdomain
 * --------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'mfnch_textdomain' );
function mfnch_textdomain() {
    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );
}



/* ---------------------------------------------------------------------------
 * Override theme functions
 * 
 * if you want to override theme functions use the example below
 * --------------------------------------------------------------------------- */
// require_once( get_stylesheet_directory() .'/includes/content-portfolio.php' );


function taxonomy_checklist_checked_ontop_filter ($args)
{

    $args['checked_ontop'] = false;
    return $args;

}

add_filter('wp_terms_checklist_args','taxonomy_checklist_checked_ontop_filter');








// remove the old box
function remove_default_categories_box() {
    remove_meta_box('categorydiv', 'post', 'side');
}
add_action( 'admin_head', 'remove_default_categories_box' );


// First we create a function
function list_terms_custom_taxonomy( $atts ) {
 
// Inside the function we extract custom taxonomy parameter of our shortcode
    
    extract( shortcode_atts( array(
        'custom_taxonomy' => '',
        'child_of' => '',
        'title' => ''
    ), $atts ) );
    
// arguments for function wp_list_categories
$args = array( 
taxonomy => $custom_taxonomy,
title_li => '',
child_of => $child_of
);
    
// We wrap it in unordered list 
echo '<h2 class="sidebar-cat-title">' . $title . '</h2>';
echo '<ul>'; 
echo wp_list_categories($args);
echo '</ul>';
}
    
// Add a shortcode that executes our function
add_shortcode( 'ct_terms', 'list_terms_custom_taxonomy' );
    
//Allow Text widgets to execute shortcodes
    
//add_filter('widget_text', 'do_shortcode');






