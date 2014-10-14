<?php 
//add_filter('newsletter_user_subscribe','nle_send_data',10,1);

/* Note to mention: during php called subscription user is not created yet (has no ID and is not in database).
Cant send the id. All ajax calls are after user is created (Ajax subsciption and confirmation). 
Posibility: create user as step 1. It will generate id and save user, but it will cause error for completing subsciption (not advised). */

if(!function_exists('nle_send_data')) {
    function nle_send_data($user = null) {
        global $NewsExport, $newsletter;
        
        $send_options = $NewsExport->get_options(); 
        if(empty($send_options)) { return; }
        
        $send_method = $send_options['send_option_active'];
        $send_data = $send_options['send_variables'];
        $user_data = $user;
        
        if(empty($send_data)) { return; }
        
        if(($_POST['action'] == 'nle_ajax_subscription') || ($_POST['action'] == 'nle_get_user_ajax')) {
            $token_id = $_POST['token'];
            $token_id = split('-',$token_id, 2);
            $user_id = $token_id[0];
            $user_token = $token_id[1];
    
            $user_data = (array) $newsletter->get_user($user_id);
            $data['user_id'] = $user_id;
            $data['user_token'] = $user_token;
        }
        
        /* Creating send array by replacing settings with user data */
        foreach($send_data as $k => $v) {
            $k = $v['name'];
            $v = $v['value'];
            
            $v = str_replace('{name}', $user_data['name'], $v);
            $v = str_replace('{email}', $user_data['email'], $v);
            $v = str_replace('{surname}', $user_data['surname'], $v);
            $v = str_replace('{referrer}', $user_data['referrer'], $v);
            $v = str_replace('{http_referer}', $user_data['http_referer'], $v);
            $v = str_replace('{token}', $user_data['token'], $v);
            $v = str_replace('{ip}', $user_data['ip'], $v);
            $v = str_replace('{status}', $user_data['status'], $v);
            
            if (strpos($v, '{profile_') !== false) {
                $v_edit = str_replace('{','', $v);
                $v_edit = str_replace('}','', $v_edit);
                $v = str_replace($v, $user_data[$v_edit], $v);
            }
            
            $data[$k] = $v;
        }
         
        /* Set user data for ajax send */
        if( $_POST['action'] == 'nle_get_user_ajax' ) {
            $ajax_data = json_encode($data);
            echo $ajax_data;
            return;
        }
        
        if( $send_method == 1 ) { /* send curl data */ } 
        else if( $send_method == 1 ) { /* trigger ajax send from php */ }
        // TODO: ajax send from filter hook
        
        /* Testing purposes - delete when done */
        delete_option('feed_data');
        add_option('feed_data', $data);
        delete_option('feed_user');
        add_option( 'feed_user', $user_data );
        
        return $user;
    }
}

if(!function_exists('nle_confirm_send_curl')) {
    function nle_confirm_send_curl() { 
        global $newsletter;
        $token_id = $_POST['token'];
        $token_id = split('-',$token_id, 2);
        $user_id = $token_id[0];
        $user_token = $token_id[1];
    
        $user = $newsletter->get_user($user_id);
        if ($user->status == 'C') { 
            // send curl data
        }
    
        /* Testing purposes - delete when done */
        delete_option('feed_ajax');
        add_option('feed_ajax', $id."----".$token);
    }
}
?>