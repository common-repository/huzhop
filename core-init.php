<?php 
/*
*
*	***** Huzhop *****
*
*	This file initializes all HUZHOP Core components
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('HUZHOP_CORE_ADMIN',dirname( __FILE__ ).'/admin/');
define('HUZHOP_CORE_INC',dirname( __FILE__ ).'/inc/');
define('HUZHOP_CORE_IMG',plugins_url( 'assets/img/', __FILE__ ));
define('HUZHOP_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('HUZHOP_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));
/*
*
*  Register CSS
*
*/
function huzhop_register_core_css(){
	wp_enqueue_style('huzhop-core', HUZHOP_CORE_CSS . 'huzhop-core.css',null,time('s'),'all');
};
add_action( 'wp_enqueue_scripts', 'huzhop_register_core_css' );    
/*
*
*  Register JS/Jquery Ready
*
*/
function huzhop_register_core_js(){
	// Register Core Plugin JS	
	wp_enqueue_script('huzhop-core', HUZHOP_CORE_JS . 'huzhop-core.js','jquery', time(), true);
};
add_action( 'wp_enqueue_scripts', 'huzhop_register_core_js' );    
/*
*
*  Includes
*
*/ 
// Load the admin core
if ( file_exists( HUZHOP_CORE_ADMIN . 'huzhop-admin-core.php' ) ) {
	require_once HUZHOP_CORE_ADMIN . 'huzhop-admin-core.php';
} 

// Load the admin Functions
if ( file_exists( HUZHOP_CORE_ADMIN . 'huzhop-admin-functions.php' ) ) {
	require_once HUZHOP_CORE_ADMIN . 'huzhop-admin-functions.php';
}

// Load the admin Options
if ( file_exists( HUZHOP_CORE_ADMIN . 'huzhop-admin-options.php' ) ) {
	require_once HUZHOP_CORE_ADMIN . 'huzhop-admin-options.php';
}

// Load the admin Fulfillment
if ( file_exists( HUZHOP_CORE_ADMIN . 'huzhop-fulfillment-order-box.php' ) ) {
	require_once HUZHOP_CORE_ADMIN . 'huzhop-fulfillment-order-box.php';
}

// Load the Functions
if ( file_exists( HUZHOP_CORE_INC . 'huzhop-core-functions.php' ) ) {
	require_once HUZHOP_CORE_INC . 'huzhop-core-functions.php';
}     
// Load the ajax Request
if ( file_exists( HUZHOP_CORE_INC . 'huzhop-ajax-request.php' ) ) {
	require_once HUZHOP_CORE_INC . 'huzhop-ajax-request.php';
} 
// Load the Shortcodes
if ( file_exists( HUZHOP_CORE_INC . 'huzhop-shortcodes.php' ) ) {
	require_once HUZHOP_CORE_INC . 'huzhop-shortcodes.php';
}