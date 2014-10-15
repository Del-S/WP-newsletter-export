jQuery(document).ready(function($) {   
    /* TODO: Add protection for sending same data multiple times (reload page etc) - protection */
    
    /* Adding hook for subsctiption if filter is not working */
    var subscription_bool = jquery_data.ajax_subscription;
    var subscription_url = jquery_data.sub_url;
    var url = window.location.href;
    if(url.indexOf(subscription_url) >= 0) { 
        var token = url.split('&nk=')[1];
        if(jquery_data.send_method == 0) { 
            var send_url = jquery_data.send_url;
            $.post(jquery_data.ajax_url,{ 'action':'nle_get_user_ajax', 'token':token }, function(r) {
                var data = r; //alert(r);// random 0 is apearing in string
                if(data != '') { 
                    $.ajax({
                        type: 'POST',
                        url: send_url,
                        dataType: 'jsonp',
                        jsonp: 'callback',
                        data: { 'action':'ajax_user_subscription', 'data':data },
                        success: function(res) {
                            console.log('Subscription send successful');
                        },
                        error: function(res) {
                            console.log('Error during sending - Subscription'); // If there is an error switch to another method (curl)
                        }
                    });
                } // set up test if data was already send do not send multiple times
                else { console.log('Error during ajax send - data are empty. (Subsciption)'); }
            });
        }
        else if((jquery_data.send_method == 1) && (subscription_bool == 1)) { $.post(jquery_data.ajax_url, { 'action':'nle_ajax_subscription', 'token': token }); /* Sending token to curl */ }
        else { console.log('Error during ajax send - send method is wrong or not defined. (Subsciption)'); }
    }
    
    /* Adding hook for confirmation URL from Newsletter */
    var confirm_bool = jquery_data.ajax_confirmation;
    if(confirm_bool == 0) {     // Don't bother if confirmation is disabled
        var confirm_url = jquery_data.confirm_url;
        var url = window.location.href;
        if(url.indexOf(confirm_url) >= 0) { 
            var token = url.split('&nk=')[1];
            if(jquery_data.send_method == 0) { 
                var send_url = jquery_data.send_url;
                $.post(jquery_data.ajax_url,{ 'action':'nle_get_user_ajax', 'token':token }, function(r) {
                    var data = r;
                    if(data != '') { 
                        $.ajax({
                            type: 'POST',
                            url: send_url,
                            dataType: 'jsonp',
                            jsonp: 'callback',
                            data: { 'action':'ajax_user_confirmation', 'data':data },
                            success: function(res) {
                                console.log('Confirmation send successful');
                            },
                            error: function(res) {
                                console.log('Error during sending - Confirmation'); // If there is an error switch to another method (curl)
                            }
                        });
                    } // set up test if data was already send do not send multiple times
                    else { console.log('Error during ajax send - data are empty. (Confirmation)'); }
                });
            }
            else if(jquery_data.send_method == 1) { $.post(jquery_data.ajax_url, { 'action':'nle_ajax_confirm', 'token': token }); /* Sending token to curl */ }
            else { console.log('Error during ajax send - send_method is wrong or not defined. (Confirmation)'); }
        }
    }
});