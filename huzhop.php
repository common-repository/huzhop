<?php 
/*
Plugin Name: 	Huzhop
Plugin URI: 	https://www.huzhop.com/plugins/huzhop
Description: 	Support WooCommerce order fulfillment for dropshipping, and image variations color showing in the product page.
Version: 		1.0.1
Author: 		Huzhop
Developer: 		Be Duc Tai
Author URI: 	https://www.huzhop.com
Requires at least: 5.0
Tested up to: 	5.2.2
Text Domain: 	huzhop
*/

// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

// Let's Initialize Everything
if ( file_exists( plugin_dir_path( __FILE__ ) . 'core-init.php' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'core-init.php' );
}