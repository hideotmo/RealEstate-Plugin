<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
});

function wpl_generate_modify_query(query_id)
{
	if(!query_id) query_id = 0;
	
	wpl_remove_message('.wpl_item_list .wpl_show_message');
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_modify_page_query&id='+query_id;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_mls_query_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

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
				wplj("#wpl_addon_mls_class_container").html(data.html);
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
	/** remove previous wizards **/
	wplj("#wpl_mls_query_wizard_container").html('');
	
	ajax_loader_element = '#wpl_ajax_loader_select_mls_class_id';
	wplj(ajax_loader_element).html('&nbsp;<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var mls_server_id = wplj("#mls_server_id").val();
	var mls_query_id = wplj("#id").val();
	if(!mls_query_id) mls_query_id = 0;
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_query_wizard&mls_class_id='+mls_class_id+'&mls_server_id='+mls_server_id+'&mls_query_id='+mls_query_id;
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
				wplj("#wpl_mls_query_wizard_container").html(data.html);
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

function wpl_save_query()
{
	/** update query **/
	wpl_update_query();
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=save_query&table=wpl_addon_mls_queries';
	wplj("#wpl_modify_query input:checkbox").each(function(ind, elm)
	{
		/** skip query wizard values **/
		if(elm.id.substring(0, 2) == 'qw') return;
		
		request_str += "&fld_" + elm.id + "=";
		if (elm.checked) request_str += '1';
		else request_str += '0';
	})

	wplj("#wpl_modify_query input:text, #wpl_modify_query textarea, #wpl_modify_query input:password, #wpl_modify_query input[type='hidden'], #wpl_modify_query select").each(function(ind, elm)
	{
		/** skip query wizard values **/
		if(elm.id.substring(0, 2) == 'qw') return;
		
		if(elm.id != 'id') request_str += "&fld_" + elm.id + "=";
		else request_str += "&" + elm.id + "=";
		
		request_str += encodeURIComponent(wplj(elm).val());
	});
	
	/** class name **/
	request_str += "&fld_mls_class_name="+wplj("#mls_class_id option:selected").text();
	wpl_remove_message('.wpl_item_list .wpl_show_message');
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wpl_show_messages('<?php echo __('Query modified.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_green_msg');
			wplj._realtyna.lightbox.close();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_update_query()
{
	var query_str = '';

	wplj("#wpl_mls_query_wizard_container .fanc-row").each(function(ind, elm)
	{
		var field_id = elm.id;
		var operator = wplj("#qw_operator_select"+field_id).val();
		var value = wplj("#qw"+field_id).val();
		
		if(value)
		{
			query_str += '('+field_id+'=';
			
			if(operator == 1) query_str += value;
			else if(operator == 2) query_str += value+'+';
			else if(operator == 3) query_str += value+'-';
			else if(operator == 4) query_str += '*'+value+'*';
			
			query_str += '),';
		}
	});
	
	query_str = query_str.substring(0, query_str.length-1)
	query_str = '('+query_str+')';
	
	wplj("#query").val(query_str);
}

function wpl_remove_mls_query(id, confirmed)
{
	if(!id)
	{
		wpl_show_messages("<?php echo __('Invalid Query ID', 'wpl'); ?>", '.wpl_item_list .wpl_show_message');
		return false;
	}

	if (!confirmed)
	{
		message = "<?php echo __('Are you sure you want to remove this item?', 'wpl'); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_remove_mls_query(' + id + ', 1);"><?php echo __('Yes', 'wpl'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', 'wpl'); ?></span>';
		wpl_show_messages(message, '.wpl_item_list .wpl_show_message');

		return false;
	}
	else
	{
		wpl_remove_message();
	}

	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=remove_mls_query&id='+id;

	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#item_row"+id).remove();
			wpl_show_messages('<?php echo __('Query deleted.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_green_msg');
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}

function wpl_generate_params_page(id)
{
	if(!id) id = '';
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_params_page_query&id='+id;
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_mls_query_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_set_enabled_mls_query(id)
{
	var ajax_loader_element = '#wpl_mls_query_enabled'+id;
	var enabled;
	
	if(wplj(ajax_loader_element).hasClass('icon-enabled')) enabled = 0;
	else enabled = 1;
	
	wplj(ajax_loader_element).removeClass('icon-enabled');
	wplj(ajax_loader_element).removeClass('icon-disabled');
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=query_enabled&id='+id+'&enabled='+enabled;
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			if(enabled)
			{
				wplj(ajax_loader_element).addClass('icon-enabled');
				wplj(ajax_loader_element).attr('title', '<?php echo __('Auto sync is enabled.', 'wpl'); ?>');
			}
			else 
			{
				wplj(ajax_loader_element).addClass('icon-disabled');
				wplj(ajax_loader_element).attr('title', '<?php echo __('Auto sync is disabled.', 'wpl'); ?>');
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			if(enabled) wplj(ajax_loader_element).addClass('icon-disabled');
			else wplj(ajax_loader_element).addClass('icon-enabled');
			
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}
</script>