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

/**
 * Register a meta box using a class.
 */
class Huzhop_Fulfillment_Meta_Box {
 
    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
 
    }
 
    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'huzhop-meta-box',
            __( 'Huzhop Fulfillment', 'huzhop' ),
            array( $this, 'render_metabox' ),
            'shop_order',
            'side',
            'high'
        );
 
    }
 
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'huzhop_nonce_action', 'hz_nonce' );
        
        $fulfillment_data = get_post_meta( $post->ID, 'hz_fulfillment', true );
        
        if($fulfillment_data && $fulfillment_data['status']){
	    	echo '<div class="hz-fulfill-area">
	    		<span class="fulfill-icon">
	    			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><title>payment-success</title><circle fill="#BBE5B3" cx="16" cy="16" r="16"></circle><circle fill="#FFF" cx="16" cy="16" r="9"></circle><path fill="#108043" d="M16 26c-5.523 0-10-4.477-10-10S10.477 6 16 6s10 4.477 10 10c-.006 5.52-4.48 9.994-10 10zm0-18c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8c-.005-4.416-3.584-7.995-8-8z"></path><path fill="#108043" d="M15 19c-.265 0-.52-.105-.707-.293l-2-2c-.374-.407-.347-1.04.06-1.413.383-.352.972-.352 1.354 0L15 16.587l3.293-3.29c.397-.385 1.03-.374 1.414.024.374.388.374 1.002 0 1.39l-4 4c-.188.186-.442.29-.707.29z"></path></svg>
	    		</span>
	    		<span class="fulfill-status">Fulfilled</span>
	    	</div>';
	    	
	    	$tracking_url_show = '';
	    	$tracking_url = $fulfillment_data['tracking_url'];
	    	if($tracking_url){
		    	$tracking_url_show = '<a class="_tracking_url" href="'. esc_url( $tracking_url ) .'" target="_blank">'. $tracking_url .'</a>';
		    	$edit_tracking_url = '<a class="edit-tracking-url" href="javascript:void(0)">
		    		<img src="'.HUZHOP_CORE_IMG.'edit.svg" width="14" alt="Edit" />
		    	</a>';
		    	$edit_tracking_url .= '<div id="hz_tracking_edit">
		    		<input type="text" id="hz_tracking_url" name="tracking_url" value="'. esc_attr( $tracking_url ) .'" /> 
		    		<button class="button">Save Change</button>
		    	</div>';
		    	 
		    	echo "<div class=\"hz-fulfill-area hz-row\">
		    		<label>Tracking Url: </label>
		    		$tracking_url_show $edit_tracking_url
		    	</div>";
	    	}
	    	
        }else{
	        echo '<button class="button button-primary mark-as-fulfilled">Mark as fulfilled</button>';
        }
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['hz_nonce'] ) ? $_POST['hz_nonce'] : '';
        $nonce_action = 'huzhop_nonce_action';
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
    }
}
 
new Huzhop_Fulfillment_Meta_Box();