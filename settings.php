<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'newsexport_settings' );

function newsexport_settings() {
    add_options_page( __( 'Newsletter Export Settings', 'product-feed' ), __( 'Newsletter Export Settings', 'product-feed' ), 'manage_options', 'newsletter-export-settings', 'newsexport_settings_page' );  
}

if(!function_exists('newsexport_settings_page')) {
function newsexport_settings_page() {
    add_action('newsletter_export_settings', 'display_newsletter_export_settings');
    do_action('newsletter_export_settings');
}
}

if(!function_exists('display_newsletter_export_settings')) {
function display_newsletter_export_settings() {
    $options = get_option('feed_settings');
?>
    <div id="newsletter_export">
        <h2>Nesletter Export</h2>
        <form action="" method="POST" id="export_form">
            <div class="method">
                <p>
                <?php
                    if($options !== false) {
                        $send_options = $options['send_options'];
                        $check_send_option = $options['send_option_active'];
                        foreach($send_options as $id => $name) {
                            $checked = "";
                            if($id == $check_send_option) { $checked = 'checked'; }
                            echo '<input type="radio" name="send_options" value="'.$id.'" '.$checked.'/><label for="send_options_'.$id.'">'.__($name,'newsletter-export').'</label>';
                        }  
                    }
                ?>
                </p>
            </div>
            <div class="fields">
                <table class="send_fields wp-list-table widefat">
                    <thead>
                    <tr>
                        <th><?php _e('#','newsletter-export'); ?></th>
                        <th><?php _e('Name','newsletter-export'); ?></th>
                        <th><?php _e('Content','newsletter-export'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                    $send_fields = $options['send_variables'];
                    $alt_counter = 0;
                    foreach($send_fields as $id => $values) {
                        $field_name = $values['name'];
                        $field_value = $values['value'];
                        $alt = "";
                        if(!(bool)($alt_counter & 1)) { $alt = 'alternate'; }
                        echo '<tr class="row_'.$id.' '.$alt.'">
                                <td>Field #'.$id.'</td>
                                <td><input type="text" name="settings_'.$id.'_name" value="'.$field_name.'" /></td>
                                <td><input type="text" name="settings_'.$id.'_value" value="'.$field_value.'" /></td>
                                <td><button name="remove" value="'.$id.'">'.__('Remove','newsletter-export').'</button></td>
                              </tr>';
                        $alt_counter++;
                    }        
                ?>
                    </tbody>
                    <tfoot>
                    <tr class="new">
                        <th><?php _e('Add new field','newsletter-export') ?></th>
                        <th><input type="text" name="new_name"/></th>
                        <th><input type="text" name="new_value"/></th>
                        <th><button name="submit_new_field"><?php _e('Add','newsletter-export') ?></button></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <label for="send_url"><?php _e('URL of recieving script: ','newsletter-export'); ?></label><input type="text" name="send_url" class="send_url" value="<?php echo $options['send_url']; ?>" />
            <input type="submit" name="save_export_settings" class="send_submit" value="<?php _e('Submit changes','newsletter-export') ?>"  />
        </form>
<?php /* Testing purposes - delete when done */ ?>
<p><?php print_r(get_option( 'feed_user' )); echo "--"; ?></p>
<p><?php print_r(get_option( 'feed_data' )); echo "--"; ?></p>
<p><?php print_r(get_option( 'feed_ajax' )); echo "--"; ?></p>

<?php if(!has_filter('newsletter_user_subscribe')) { $error = '<p class="error">'.__('Filter for subscription does not exist. Switches to jQuery.','newsletter-export').'</p>'; echo $error; }  ?>
    </div>
<?php
}    
}

if($_POST['save_export_settings']) {
    global $NewsExport;
    $NewsExport->update_settings();
    
    /* Redirect */
    $url = $_SERVER['HTTP_REFERER'];
    if( strpos($url, '&saved=true') !== false ) { $url = str_replace('&saved=true','',$url); } 
    $atributes = '&saved=true';
    $NewsExport->reload($url, $atributes);
};
?>