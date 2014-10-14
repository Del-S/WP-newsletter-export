jQuery(document).ready(function($) {    
    $('#export_form button[name=submit_new_field]').click(function( e ) {
        e.preventDefault();
        var name = $(this).attr('name');
        if(name == 'submit_new_field') {    
            var id = $('.send_fields tbody tr').last().find('td').first().html();
                id = parseInt(id.split('Field #')[1])+1;
            var name = $("input[name=new_name]").val();
            if((!name) || (!name.trim())) { alert('Enter name please'); return; }
            var value = $("input[name=new_value]").val();
            $('.send_fields tbody').append('<tr class="added_field row_'+id+'"><td>Field #'+id+'</td><td><input type="text" name="settings_'+id+'_name" value="'+name+'" /></td><td><input type="text" name="settings_'+id+'_value" value="'+value+'" /></td><td><button name="remove" class="remove_new" value="'+id+'">Remove</button></td></tr>');
            $('#export_form tbody tr:even').addClass('alternate');
            $('.added_field').fadeIn('quick');
            $("input[name=new_name]").val('');
            $("input[name=new_value]").val('');
        }
    });
    
    $('#export_form').on('click','button[name=remove]',function( e ) {
        e.preventDefault();
        var id = $(this).val();
        $('tr.row_'+id).fadeOut('quick');
        $('tr.row_'+id).remove();
    });
    
    /*$('#export_form').submit(function(e) {
        
        window.location.reload();
        //e.preventDefault();
        // update options
    });*/
});