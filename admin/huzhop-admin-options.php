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

class HuzhopSettings{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page(
            'Huzhop', 
            'Huzhop', 
            'manage_options', 
            'huzhop-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page(){
        // Set class property
        $this->options = get_option( 'huzhop_options' );
        ?>
        <div class="wrap">
            <h1>Huzhop</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'huzhop_option_group' );
                do_settings_sections( 'huzhop-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init(){        
        register_setting(
            'huzhop_option_group', // Option group
            'huzhop_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Image variants type', // Title
            array( $this, 'print_section_info' ), // Callback
            'huzhop-setting-admin' // Page
        );  
    
        add_settings_field(
            'type_ids', 
            'Variant Type IDs', 
            array( $this, 'variant_types_callback' ), 
            'huzhop-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
        $new_input = array();
        
        if( isset( $input['type_ids'] ) )
            $new_input['type_ids'] = sanitize_text_field( $input['type_ids'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info(){
        print 'If your website uses variants of different colors, you can add it to the field below.';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function variant_types_callback(){
        printf(
	        '<textarea class="large-text" id="type_ids" name="huzhop_options[type_ids]" rows="3">%s</textarea>',
	        isset( $this->options['type_ids'] ) ? esc_attr( $this->options['type_ids']) : ''
        );
        
        print '<p style="color: #999;">With each type of id separated by commas. For example: color, type, ... </p>';
    }
}

if( is_admin() )
    $huzhop_settings_page = new HuzhopSettings();