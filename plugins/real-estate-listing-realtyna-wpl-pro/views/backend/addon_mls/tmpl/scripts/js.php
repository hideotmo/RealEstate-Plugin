<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
});

function wpl_generate_modify_mls(mls_server_id)
{
	if(!mls_server_id) mls_server_id = 0;
	
	wpl_remove_message('.wpl_mls_servers_list .wpl_show_message');
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_modify_page&id='+mls_server_id;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_mls_server_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_save_mls()
{
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=save_mls&table=wpl_addon_mls';

	wplj("#wpl_modify_mls input:checkbox").each(function(ind, elm)
	{
		request_str += "&fld_" + elm.id + "=";
		if (elm.checked) request_str += '1';
		else request_str += '0';
	})

	wplj("#wpl_modify_mls input:text, #wpl_modify_mls input:password, #wpl_modify_mls input[type='hidden'], #wpl_modify_mls select").each(function(ind, elm)
	{
		if(elm.id != 'id') request_str += "&fld_" + elm.id + "=";
		else request_str += "&" + elm.id + "=";
		
		request_str += wplj(elm).val();
	});
	
	wpl_remove_message('.wpl_mls_servers_list .wpl_show_message');

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wpl_show_messages('<?php echo __('MLS modified.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_green_msg');
			wplj._realtyna.lightbox.close();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_remove_mls_server(id, confirmed)
{
	if(!id)
	{
		wpl_show_messages("<?php echo __('Invalid MLS ID', 'wpl'); ?>", '.wpl_mls_servers_list .wpl_show_message');
		return false;
	}

	if (!confirmed)
	{
		message = "<?php echo __('Are you sure you want to remove this item?', 'wpl'); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_remove_mls_server(' + id + ', 1);"><?php echo __('Yes', 'wpl'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', 'wpl'); ?></span>';
		wpl_show_messages(message, '.wpl_mls_servers_list .wpl_show_message');

		return false;
	}
	else
	{
		wpl_remove_message();
	}

	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=remove_mls_server&id='+id;

	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#item_row"+id).remove();
			wpl_show_messages('<?php echo __('MLS server deleted.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_green_msg');
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}

function wpl_test_connection(id)
{
	/** remove message **/
	wpl_remove_message();
	
	ajax_loader_element = '#wpl_ajax_loader_mls_server'+id;
	wplj(ajax_loader_element).html('&nbsp;<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var wait_timeout = setTimeout(function(){wpl_show_messages("<?php echo __("We're processing your request. Please wait ...", 'wpl'); ?>", '.wpl_mls_servers_list .wpl_show_message', 'wpl_gold_msg');}, 15000);
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=test_connection&id='+id;
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
				clearTimeout(wait_timeout);
				wplj(ajax_loader_element).html('');
				
				wpl_show_messages(data.message, '.wpl_mls_servers_list .wpl_show_message', 'wpl_green_msg');
				setTimeout(function(){window.location.reload();}, 1000);
			}
			else
			{
				clearTimeout(wait_timeout);
				wplj(ajax_loader_element).html('');
				
				wpl_show_messages(data.message, '.wpl_mls_servers_list .wpl_show_message', 'wpl_red_msg');
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_mls_servers_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
			
			clearTimeout(wait_timeout);
		}
	});
}

function wpl_generate_params_page(id)
{
	if(!id) id = '';
	
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=generate_params_page&id='+id;
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_mls_server_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj._realtyna.lightbox.close();
		}
	});
}
</script>