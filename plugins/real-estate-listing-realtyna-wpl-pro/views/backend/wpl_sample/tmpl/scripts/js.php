<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{

		wplj("select").chosen(rta.config.chosen);

});

function wpl_sample_data()
{
	ajax_loader_element = '#wpl_ajax_loader_sample_data';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:wpl_sample:ajax&wpl_function=save_sample_data';
	
	wplj(".wpl_sample_data_table input:text, .wpl_sample_data_table input:password, .wpl_sample_data_table input[type='hidden'], .wpl_sample_data_table select").each(function(ind, elm)
	{
		if(elm.id != 'id') request_str += "&"+elm.name + "=";
		else request_str += "&" + elm.id + "=";
		
		request_str += wplj(elm).val();
	});
	
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
				wpl_show_messages(data.message, '.wpl_item_list .wpl_show_message', 'wpl_green_msg');
			}
			else
			{
				wplj(ajax_loader_element).html('');
				wpl_show_messages(data.message, '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>