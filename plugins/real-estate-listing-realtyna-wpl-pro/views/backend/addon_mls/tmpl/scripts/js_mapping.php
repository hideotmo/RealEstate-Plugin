<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj('#wpl_mapping_fields_table tbody tr').each(function(i,e)
    {
        var field_id = wplj(this).data('id');
        
        wplj(e).children('td:not(:first)').click(function()
        {
            wplj('#wpl_create_all'+field_id).trigger('click');
        });
    });
});

function wpl_mls_server_select(server_id)
{
	/** remove previous class box **/
	wplj("#mls_class_id").remove();
	if(!server_id) return;
	
	ajax_loader_element = '#wpl_ajax_loader_select_mls_server';
	wplj(ajax_loader_element).html('&nbsp;<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_classes&id='+server_id;
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		dataType: 'JSON',
		success: function(data)
		{
			if(data.success == 1)
			{
				wplj(ajax_loader_element).html('');
				wplj("#server_id").after(data.html);
			}
			else
			{
				wplj(ajax_loader_element).html('');
				wpl_show_messages(data.message, '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_mls_class_select(mls_class_id)
{
	var server_id = wplj("#server_id").val();
	
	url = '<?php echo wpl_global::get_full_url(); ?>';
	url = wpl_update_qs('page', 'wpl_addon_mls_mapping', url);
	url = wpl_update_qs('server_id', server_id, url);
	url = wpl_update_qs('mls_class_id', mls_class_id, url);
	
	window.location.href = url;
}

function wpl_generate_map_field(field_id)
{
	if(!field_id) field_id = 0;
	
	wpl_remove_message('.wpl_item_list .wpl_show_message');
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_mapping_page&id='+field_id;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_mls_mapping_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_field_select(wpl_field_id, field_type)
{
	/** remove location custom **/
	if(wpl_field_id != '41')
	{
		wplj("#wpl_location_custom_selectbox").remove();
		wplj("#mls_field_custom1").val('');
		location_custom_html_loaded = false;
	}
	
	wplj("#wpl_field_id").val(wpl_field_id);
	
	wplj(".wpl_field_container").removeClass('wpl_field_selected');
	wplj("#wpl_field_container"+wpl_field_id).addClass('wpl_field_selected');
	
	if(field_type == 'locations')
	{
		if(!location_custom_html_loaded)
		{
			wplj("#wpl_field_container"+wpl_field_id).append("&nbsp;"+location_custom_html);
			location_custom_html_loaded = true;
		}
	}
}

function wpl_save_mapping()
{
	var wpl_field_id = wplj("#wpl_field_id").val();
	var id = wplj("#mls_field_id").val();
	var custom1 = wplj("#mls_field_custom1").val();
	
	ajax_loader_element = '#wpl_ajax_loader_mls_mapping_field';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=save_mapping&wpl_field_id='+wpl_field_id+'&id='+id+'&custom1='+custom1;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
			wplj(ajax_loader_element).html('');
            
            if(data.data.dbst_name)
            {
                wplj("#item_row"+id+" .wpl_field").html(data.data.dbcat_name+' -> '+data.data.dbst_name);
                
                wplj("#item_row"+id+" .wpl_mapping_action span").removeClass('italic_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").removeClass('gray_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").addClass('bold_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").addClass('green_span');
                
                wplj("#item_row"+id+" .wpl_mapping_action span").html('<?php echo addslashes(__('Modify mapping', 'wpl')); ?>');
                
                // Hide Checkbox
                wplj("#item_row"+id+" #wpl_create_all"+id).prop('checked', false).hide();
            }
            else
            {
                wplj("#item_row"+id+" .wpl_field").html('');
                
                wplj("#item_row"+id+" .wpl_mapping_action span").removeClass('bold_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").removeClass('green_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").addClass('italic_span');
                wplj("#item_row"+id+" .wpl_mapping_action span").addClass('gray_span');
                
                wplj("#item_row"+id+" .wpl_mapping_action span").html('<?php echo addslashes(__('Map a field', 'wpl')); ?>');
                
                // Show Checkbox
                wplj("#item_row"+id+" #wpl_create_all"+id).prop('checked', false).show();
            }
            
			wplj._realtyna.lightbox.close();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj(ajax_loader_element).html('');
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
            
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_remove_mapping()
{
	wplj(".wpl_field_container").removeClass('wpl_field_selected');
	wplj("#wpl_field_id").val('');
	
	wpl_save_mapping();
}

function wpl_auto_create()
{
    var wpl_field_type = wplj("#wpl_field_type").val();
    
	var mls_field_ids = '';
	wplj('.wpl_create_all:checked').each(function(){
         mls_field_ids += wplj(this).val()+',';
    });
	
	ajax_loader_element = '#wpl_ajax_loader_autocreate';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=auto_create&mls_field_ids='+mls_field_ids+'&wpl_field_type='+wpl_field_type;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj(ajax_loader_element).html('');
			window.location.reload();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj(ajax_loader_element).html('');
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			window.location.reload();
		}
	});
}

function wpl_mls_filter(filter)
{
	var server_id = wplj("#server_id").val();
	var mls_class_id = wplj("#mls_class_id").val();
	
	url = '<?php echo wpl_global::get_full_url(); ?>';
	url = wpl_update_qs('page', 'wpl_addon_mls_mapping', url);
	url = wpl_update_qs('server_id', server_id, url);
	url = wpl_update_qs('mls_class_id', mls_class_id, url);
	url = wpl_update_qs('mls_field_filter', filter, url);
	
	window.location.href = url;
}
</script>