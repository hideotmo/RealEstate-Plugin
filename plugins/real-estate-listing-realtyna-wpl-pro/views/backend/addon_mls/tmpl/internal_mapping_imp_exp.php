<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

?>
<div class="wpl-imp-exp-addon import-export-box">

    <div class="wpl_importexport"><div class="wpl_show_message"></div></div>

	<ul>
        <li class="wpl-imp-exp-exp-icon">
            <label class="wpl-gen-panel-label" for="wpl_export_file"><?php echo __('Export Mapping', 'wpl'); ?>: </label>
<!--            <select id="wpl_export_format" data-has-chosen>
                <option value="json"><?php echo __('JSON', 'wpl'); ?></option>
                <option value="xml"><?php echo __('XML', 'wpl'); ?></option>
            </select>-->
            <input type="button" class="wpl-button button-1" onclick="wpl_mls_export_mapping();" value="<?php echo __('Download', 'wpl'); ?>"/>
        </li>

        <li class="wpl-imp-exp-imp-icon">
            <label class="wpl-gen-panel-label" for="wpl_import_mapping_file"><?php echo __('Import Mapping', 'wpl'); ?>: </label>

            <?php
            $params = array('html_element_id' => 'wpl_import_mapping_file', 'html_path_message' => '.wpl_importexport .wpl_show_message', 'html_ajax_loader' => '#wpl_import_mapping_ajax_loader', 'request_str' => 'admin.php?wpl_format=b:addon_mls:ajax&wpl_function=import_mapping&mls_class_id='.wpl_request::getVar('mls_class_id',''), 'valid_extensions' => array('json', 'xml'));
            wpl_global::import_activity('ajax_file_upload:default', '', $params);
            ?>

            <span id="wpl_import_mapping_ajax_loader"></span>

            <div id="wpl_import_mapping_note" class="wpl-util-panel-note wpl-util-panel-note-error"><?php echo __('<b>Note:</b>This will override all your current mappings! Proceed with caution.', 'wpl'); ?></div>
        </li>
    </ul>
    <hr />
    <div class="wpl-mls-create-query-box">
        <?php echo __('Create the default query', 'wpl'); ?>: <input class="wpl-button button-2" type="button" value="<?php echo __('Create', 'wpl'); ?>" id="wpl_mls_create_query" onclick="return wpl_mls_create_default_query()" /> <span id="wpl_create_default_query_ajax_loader"></span>
    </div>
</div>
<script type="text/javascript">

wplj(document).ready(function()
{
});

function wpl_mls_export_mapping()
{
	var format = 'json';
	var mls_class_id = wplj('#mls_class_id').val();
    
    if(mls_class_id.length == 0) wplj('.wpl_show_message').html("<?php echo __('Please select a MLS category', 'wpl'); ?>")
    
	document.location = '<?php echo wpl_global::get_full_url(); ?>&wpl_format=b:addon_mls:ajax&wpl_function=export_mapping&mls_mapping_export_format='+format+'&mls_class_id='+mls_class_id;
}

function wpl_mls_create_default_query()
{
	var mls_class_id = wplj('#mls_class_id').val();
	var mls_class_name = wplj('#mls_class_id option:selected').text();
    
    if(mls_class_id.length == 0) wplj('#wpl_show_message').html("<?php echo __('Please select a MLS category', 'wpl'); ?>")
    
    ajax_loader_element = '#wpl_create_default_query_ajax_loader';
	wplj(ajax_loader_element).html('&nbsp;<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
    request_str = '&wpl_format=b:addon_mls:ajax&wpl_function=create_default_query&mls_class_id='+mls_class_id+'&mls_server_id=<?php echo wpl_request::getVar('server_id'); ?>&mls_class_name='+mls_class_name;
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
				wpl_show_messages(data.message, '.wpl_importexport .wpl_show_message', 'wpl_green_msg');
			}
			else
			{
				wplj(ajax_loader_element).html('');
				wpl_show_messages(data.message, '.wpl_importexport .wpl_show_message', 'wpl_red_msg');
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '.wpl_importexport .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
    
}
</script>

<style>
    .import-export-box
    {
        background: #fafafa none repeat scroll 0 0;
        border: 1px solid #d5d5d5;
        margin-bottom: 10px;
        margin-top: 10px;
        padding: 5px;
    }
</style>