jQuery(document).ready(function($) {    
    /* Adding hook for subsctiption if filter is not working */
    var subscription_bool = jquery_data.ajax_subscription;
    if(subscription_bool == 1) {  // Don't bother if filter is in place
        var subscription_url = jquery_data.sub_url;
        var url = window.location.href;
        if(url.indexOf(subscription_url) >= 0) { 
            var token = url.split('&nk=')[1];
            if(jquery_data.send_method == 0) { 
                var url = jquery_data.send_url;
                $.post(jquery_data.ajax_url,{ 'action':'nle_get_user_ajax', 'token':token }, function(r) {var data = r; alert(r);// random 0 is apearing in string
                    if(data != '') { 
                        $.ajax({
                            url: url,
                            dataType: 'jsonp',
                            data: { 'action':'ajax_user_subscription', 'data':data },
                            type: 'POST',
                            dataType: "json",
                            success: function(data) {
                                alert(data)
                                console.log('Subscription send successful');
                            },
                            error: function(jqXHR, textStatus, ex) {
                                alert(textStatus + "," + ex + "," + jqXHR.responseText);
                            }
                        }); 
                        
                    } // set up test if data was already send do not send multiple times
                    else { console.log('Error during ajax send - data are empty. (Subsciption)'); }
                });
            }
            else if(jquery_data.send_method == 1) { $.post(jquery_data.ajax_url, { 'action':'nle_ajax_subscription', 'token': token }); /* Sending token to curl */ }
            else { console.log('Error during ajax send - send method is wrong or not defined. (Subsciption)'); }
        }
    }
    
    /* Adding hook for confirmation URL from Newsletter */
    var confirm_bool = jquery_data.ajax_confirmation;
    if(confirm_bool == 0) {     // Don't bother if confirmation is disabled
        var confirm_url = jquery_data.confirm_url;
        var url = window.location.href;
        if(url.indexOf(confirm_url) >= 0) { 
            var token = url.split('&nk=')[1];
            if(jquery_data.send_method == 0) { 
                var url = jquery_data.send_url;
                $.ajax(url,{ 'action':'ajax_user_confirmation', 'token':token } ); // Set cross-domain send 
            }
            else if(jquery_data.send_method == 1) { $.post(jquery_data.ajax_url, { 'action':'nle_ajax_confirm', 'token': token }); /* Sending token to curl */ }
            else { console.log('Error during ajax send - send_method is wrong or not defined. (Confirmation)'); }
        }
    } 
});