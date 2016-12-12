<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$date_format = wpl_global::get_setting('main_date_format');
$date_format_exp = explode(':', $date_format);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#wpl_search_date_from").datepicker(
    {
		dateFormat: "<?php echo $date_format_exp[1]; ?>",
		changeMonth: true,
		changeYear: true,
		onSelect:function()
        {
            var date_end_value = wplj("#wpl_search_date_end").val();
			if(date_end_value == '') wplj("#wpl_search_date_end").val(wplj(this).val());
		},
	});
    
	wplj("#wpl_search_date_end").datepicker(
    {
		dateFormat: "<?php echo $date_format_exp[1]; ?>",
		changeMonth: true,
		changeYear: true
	});
});

function wpl_search_log()
{
	var url_strings = '';
	var name = wplj("#wpl_search_section").val();
	var date_from = wplj("#wpl_search_date_from").val();
	var date_end = wplj("#wpl_search_date_end").val();
	var list_addons = wplj("#wpl_search_list_addons").val();
	var list_status = wplj("#wpl_search_list_status").val();
	var list_priority = wplj("#wpl_search_list_priority").val();

	if(wplj.trim(name) != '') url_strings += '&sf_text_section='+encodeURIComponent(name);
	if(wplj.trim(date_from) != '') url_strings += '&sf_datemin_log_date='+encodeURIComponent(date_from);
	if(wplj.trim(date_end) != '') url_strings += '&sf_datemax_log_date='+encodeURIComponent(date_end);
	url_strings += '&sf_select_addon_id='+encodeURIComponent(list_addons);
	url_strings += '&sf_select_priority='+encodeURIComponent(list_priority);

	var url = '<?php echo wpl_global::get_full_url(); ?>'+url_strings;
	window.location = url;
}

function wpl_reset_search_log()
{
	window.location = '<?php echo wpl_global::get_wpl_admin_menu('wpl_admin_log_manager'); ?>';
}

function wpl_delete_single_log(id, confirmed)
{
    var message_path = '.wpl_log_list .wpl_show_message';
    if(typeof confirmed == 'undefined') confirmed = 0;
    
    if(!confirmed)
	{
		var message = "<?php echo __('Are you sure you want to this log?', 'wpl'); ?>&nbsp;";
		message += '<span class="wpl_actions" onclick="wpl_delete_single_log(\''+id+'\', \''+confirmed+'\');"><?php echo __('Yes', 'wpl'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\''+message_path+'\');"><?php echo __('No', 'wpl'); ?></span>';
		
		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
    
    var ajax_loader_element = "#wpl_log_ajax_loader"+id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var request_str = 'wpl_format=b:log_manager:ajax&wpl_function=delete&id='+id+'&_wpnonce=<?php echo $this->nonce; ?>';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			wplj("#item_row"+id).slideUp(200);
		}
		else if(data.success == 0)
		{
			wpl_show_messages(data.message, message_path, 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_delete_all_logs(confirmed)
{
    var message_path = '.wpl_log_list .wpl_show_message';
    if(typeof confirmed == 'undefined') confirmed = 0;
    
    if(!confirmed)
	{
		var message = "<?php echo __('Are you sure you want to remove all logs?', 'wpl'); ?>&nbsp;";
		message += '<span class="wpl_actions" onclick="wpl_delete_all_logs(\''+confirmed+'\');"><?php echo __('Yes', 'wpl'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\''+message_path+'\');"><?php echo __('No', 'wpl'); ?></span>';
		
		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
    
	var ajax_loader_element = "#wpl_log_ajax_loader_delete_all_logs";
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var request_str = 'wpl_format=b:log_manager:ajax&wpl_function=delete&_wpnonce=<?php echo $this->nonce; ?>';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			wpl_reset_search_log();
		}
		else if(data.success == 0)
		{
			wpl_show_messages(data.message, message_path, 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>