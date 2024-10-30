<?php 
/*
*
*	***** Huzhop *****
*
*	Core Functions
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/*
*
* Header vars
*
*/
function huzhop_header_vars(){
	$ajaxUrl = admin_url('admin-ajax.php');
	$_token = wp_create_nonce( 'hz-token' );
	
	$huzhop_options = get_option( 'huzhop_options' );
	$options = '{}';
	
	if($huzhop_options){
		$options = json_encode($huzhop_options);
	}
	
	echo "<script>var hz_vars = {ajaxUrl: '$ajaxUrl', _token: '$_token', options: JSON.parse('$options')};</script>";
}
add_action( 'wp_head', 'huzhop_header_vars' );