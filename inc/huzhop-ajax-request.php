<?php 
/*
*
*	***** Huzhop *****
*
*	Ajax Request
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
/*
*
* Huzhop get product variants for switch images
*
*/
function huzhop_get_product_variants() {
	ini_set("memory_limit", "-1");
	set_time_limit(0);
	
	$token = $_POST['_token']; 
	if ( ! wp_verify_nonce( $token, 'hz-token' ) ) {
	    wp_send_json( array(
		    'status' => 'error',
		    'message' => 'Token not validate',
		    'token' => $token
	    ), 403 );
	} else {
		$product_id = intval( $_POST['pid'] );
		
		if ( ! $product_id || ! is_woocommerce_activated() ) {
			wp_send_json( array(), 200 );
		}

	    $product = new WC_Product_Variable( $product_id );
		$variations = $product->get_available_variations();
		
		wp_send_json( $variations, 200 );
	}
}
add_action( 'wp_ajax_hz_get_wc_variants', 'huzhop_get_product_variants' );
add_action( 'wp_ajax_nopriv_hz_get_wc_variants', 'huzhop_get_product_variants' );