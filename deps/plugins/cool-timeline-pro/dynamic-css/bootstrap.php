<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


/**
 * Prevent loading the library more than once
 */
if( defined( 'WP_DYNAMIC_CSS' ) ) return;
define( 'WP_DYNAMIC_CSS', true );

/**
 * Load required files
 */
require_once 'compiler.php';
require_once 'functions.php';

/**
 * The following actions are used for printing or loading the compiled 
 * stylesheets externally.
 */
$dcss = DynamicCSSCompiler::get_instance();
add_action( 'wp_print_styles', array( $dcss, 'compile_printed_styles' ) );
add_action( 'wp_enqueue_scripts', array( $dcss, 'wp_enqueue_style' ) );
add_action( 'wp_ajax_wp_dynamic_css', array( $dcss, 'compile_external_styles' ) );
add_action( 'wp_ajax_nopriv_wp_dynamic_css', array( $dcss, 'compile_external_styles' ) );