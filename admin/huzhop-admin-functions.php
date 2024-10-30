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

// Update CSS within in Admin
function admin_style($hook) {

	wp_enqueue_style('huzhop-styles', HUZHOP_CORE_CSS.'hz-admin.css', null, time('s'), 'all');

	if ( 'post.php' != $hook && 'settings_page_huzhop-setting-admin' != $hook ) {
        return;
    }

    wp_enqueue_script( 'huzhop_admin_script', HUZHOP_CORE_JS. 'huzhop-admin.js', 'jquery', time() );
}
add_action('admin_enqueue_scripts', 'admin_style');


/**
 * Register and add settings
 */
function huzhop_order_fulfillment(){
	$order_id = isset($_POST['order_id'])? intval($_POST['order_id']): 0;
	$tracking_url = isset($_POST['tracking_url'])? sanitize_url($_POST['tracking_url']): '';
	
	if($order_id){
		update_post_meta( $order_id, 'hz_fulfillment', array(
			'status' => true,
			'tracking_url' => $tracking_url
		) );
	}
	
	wp_send_json( array(
		'status' => 'success'
	), 200 );
}
add_action( 'wp_ajax_hz_order_fulfillment', 'huzhop_order_fulfillment' );


/**
 * Register and add settings
 */
function huzhop_save_change_tracking_url(){
	$order_id = isset($_POST['order_id'])? intval($_POST['order_id']): 0;
	$tracking_url = isset($_POST['tracking_url'])? sanitize_url($_POST['tracking_url']): '';
	
	if($order_id && $tracking_url){
		$fulfillment_data = get_post_meta( $order_id, 'hz_fulfillment', true );
		
		if(is_array($fulfillment_data)){
			$fulfillment_data = array_merge($fulfillment_data, array('tracking_url' => $tracking_url));
			update_post_meta($order_id, 'hz_fulfillment', $fulfillment_data);
		}
	}
	
	wp_send_json( array(
		'status' => 'success',
		'tracking_url' => $tracking_url
	), 200 );
}
add_action( 'wp_ajax_hz_save_change_tracking_url', 'huzhop_save_change_tracking_url' );

