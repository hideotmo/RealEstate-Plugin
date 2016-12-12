<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
});

var total;
var limit;
var offset = 0;
var remained;

function wpl_run_import(id, import_limit)
{
	wpl_show_messages('<?php echo __('Please wait! This process may take several minutes based on your server speed.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_gold_msg');
	
	limit = parseInt(import_limit);
	
	wplj("#wpl_run_import_button").hide();
	wpl_import_log('<?php echo __('Starting the process ...'); ?>');
	
	setTimeout(function(){ wpl_import_log('<?php echo __('Initializing RETS connection ...'); ?>'); wpl_import_init(id); }, 500);
}

function wpl_import_log(log_message)
{
	wplj("#wpl_import_log").prepend('<br />'+log_message);
}

function wpl_import_init(id)
{
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=init_import&id='+id;
	
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		dataType: 'JSON',
		success: function(data)
		{
			if(data.success)
			{
				wpl_import_log(data.message);
				total = data.data.total;
				remained = total;
				wpl_import_log('<?php echo __('Total listings: ', 'wpl'); ?>'+total);
				
				if(total > 0)
				{
					wpl_import_log('<strong><?php echo __('Importing/Updating ...', 'wpl'); ?></strong>');
					wpl_do_import(id, offset, limit);
				}
				else
				{
					wpl_import_log('<?php echo __('Import stopped - no listing!', 'wpl'); ?>');
				}
			}
			else
			{
				wpl_import_log(data.message);
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}

function wpl_do_import(id, offset, limit)
{
	wpl_import_log('<br />'+'<?php echo __('Remained listings', 'wpl'); ?> : '+remained);
	request_str = 'wpl_format=b:addon_mls:ajax&wpl_function=import&id='+id+'&offset='+offset+'&limit='+limit;
	
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		dataType: 'JSON',
		success: function(data)
		{
			if(data.success)
			{
				if(total > 0)
				{
					if(remained > limit) imported_listings = limit;
					else imported_listings = remained;
					
					remained = (remained - limit);
					offset = (parseInt(offset, 10) + parseInt(limit, 10));
					
					wpl_import_log(imported_listings+' <?php echo __('listing(s) successfully imported.', 'wpl'); ?>');
					
					/** recursive calling **/
					if(remained > 0) wpl_do_import(id, offset, limit);
					else
					{
						wpl_show_messages('<?php echo __('Import/Update completed.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_green_msg');
						wpl_import_log('<strong><?php echo __('Import/Update completed.', 'wpl'); ?></strong>');
					}
				}
				else
				{
					wpl_import_log('<?php echo __('Import stopped - no listing!', 'wpl'); ?>');
				}
			}
			else
			{
				wpl_import_log(data.message);
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_item_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}
</script>