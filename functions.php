<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', 'news_export_init' );

function news_export_init() {
	global $newsletter;
    
    if ( get_option( 'feed_settings' ) === false ) {
        add_option( 'feed_settings', newsexport_default_settings() );
    }
    
    if(is_admin()) {
        add_action('nle_update_settings', 'nle_save_settings', 10, 1);
        wp_register_script( 'nle_admin', plugins_url( 'js/admin.js', __FILE__ ), array('jquery'));
        wp_enqueue_script( 'nle_admin');
    }
    
    $options = get_option( 'feed_settings' );
    $newsletter_functions = $newsletter->get_options();
    print_r($newsletter_functions);
    $ajax_sub_conn = 1;
    if(has_filter('newsletter_user_subscribe')) { $ajax_sub_conn = 0; }
    $url = $newsletter_functions['noconfirmation'];
    
    $ajax_url = admin_url( 'admin-ajax.php' );
    $send_url = $options['send_url'];
    $send_method = $options['send_option_active'];
    $ajax_subscription = $ajax_sub_conn;
    //$sub_url = $url;
    $ajax_confirmation = $newsletter_functions['noconfirmation'];
    //$confirm_url = $url;
    
    echo "-----".$ajax_confirmation;
    
    wp_register_script( 'nle_ajax', plugins_url( 'js/ajax.js', __FILE__ ), array('jquery'));
    wp_localize_script( 'nle_ajax', 'jquery_data', array('ajax_url' => $ajax_url, ) );
    wp_enqueue_script( 'nle_ajax' );
        
    /* Style enqueue */
    if(is_admin()) {
        $style_url = PRODUCTFEED_URI . "style.css";
        wp_register_style( 'newsexport-feed',  plugins_url('style.css', __FILE__));
        wp_enqueue_style('newsexport-feed');
    }
}

if(!function_exists('newsexport_default_settings')) {
function newsexport_default_settings() {
    $settings = array(
        'send_options' => array(
            0 => "Cross-domain ajax",
            1 => "Cron"),
        'send_option_active' => 0,
        'send_variables' => array(
            0 => array('name' => 'email', 'value' => ''),
            1 => array('name' => 'name', 'value' => '')),
        'send_url' => 'http://www.domain.com/recieve.php'
    );
    return $settings;
}
}

if(!function_exists('nle_save_settings')) {
function nle_save_settings($args) {
    $options = get_option( 'feed_settings' );
    foreach ($args as $k => $v) {
        if($k == 'send_options']) { $options['send_option_active'] = $v; break;}
        else if($k == 'send_url']) { $options['send_url'] = $v; break;}
        else if(preg_match('settings_',$k)) {
            $split = explode('_',$k, 2);
            $id = $split['1'];
            $name_val = $split
        } // check it
        else { }
    }
}
}
?>