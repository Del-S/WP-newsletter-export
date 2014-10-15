<?php
/*
Plugin Name: Newsletter instant export
Description: Sends cross-domain data from Newsletter instantly after registration/confirmation.
Version:     0.1
Plugin URI:  #
Author:      David Sucharda
Author URI:  http://idefixx.cz/
Text Domain: newsletter-export
Domain Path: /languages/
License:     GPL v2 or later

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('newsletter/plugin.php')) { 
    $NewsExport = new NewsExport();
}

class NewsExport {
	/* PHP5 constructor */
	function __construct() {
        /* add code for moving /ext/page.php to extenstions/subscription/page.php - if not exist of course */
        add_action( 'plugins_loaded', array(&$this,'newsexport_init') );
	}
    
    function newsexport_init() {
        /* Defining Variables */
        define( 'NEWSLETTEREXPORT_VERSION', '0.1' );
        define( 'NEWSLETTEREXPORT_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'NEWSLETTEREXPORT_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
        
        /* Plugin functions */
		require_once (NEWSLETTEREXPORT_DIR . 'functions.php');
        require_once (NEWSLETTEREXPORT_DIR . 'connection.php');
        
        /* Defining settings and deactivation */
		if ( is_admin( ) ) {        
			require_once (NEWSLETTEREXPORT_DIR . 'settings.php');
            add_filter( 'plugin_action_links', array(&$this,'nle_links'));            
			register_deactivation_hook( __FILE__, array(&$this,'nle_deactivate') );
		}
        
        add_action( 'wp_ajax_nle_get_user_ajax', 'nle_send_data' );
        add_action( 'wp_ajax_nopriv_nle_get_user_ajax', 'nle_send_data' );
    
        add_action( 'wp_ajax_nle_ajax_subscription', 'nle_send_data' );
        add_action( 'wp_ajax_nopriv_nle_ajax_subscription', 'nle_send_data' );
    
        add_action( 'wp_ajax_nle_ajax_confirm', 'nle_confirm_send_curl' );
        add_action( 'wp_ajax_nopriv_nle_ajax_confirm', 'nle_confirm_send_curl' );
    }
    
    function nle_links($links) {
		$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page=newsletter-export-settings' ) .'">' . __( 'Settings' ) . '</a>';
        return $links;
	}
    
    /* Plugin deactivation function */
    function nle_deactivate() {
        delete_option('feed_settings');
    }
    
    function update_settings() { 
        $attr = $_POST;
        do_action('nle_update_settings', $attr);
    }
    
    function reload($url, $attr) {
        header('Location: '.$url.$attr);
    }
    
    function get_options() {
        if(get_option( 'feed_settings' ) !== false) { return get_option( 'feed_settings' ); }
        else { $NewsExport->default_settings(); return get_option( 'feed_settings' ); }
    }
}
?>