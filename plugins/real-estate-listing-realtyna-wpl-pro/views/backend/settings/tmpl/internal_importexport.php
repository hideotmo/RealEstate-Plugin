<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-imp-exp-addon">

    <div class="wpl_importexport"><div class="wpl_show_message"></div></div>

	<ul>
        <li class="wpl-imp-exp-exp-icon">
            <label class="wpl-gen-panel-label" for="wpl_export_file"><?php echo __('Export Settings', 'wpl'); ?>: </label>
            <select id="wpl_export_format" data-has-chosen>
                <option value="json"><?php echo __('JSON', 'wpl'); ?></option>
                <option value="xml"><?php echo __('XML', 'wpl'); ?></option>
            </select>
            <input type="button" class="wpl-button button-1" onclick="wpl_export_settings();" value="<?php echo __('Download', 'wpl'); ?>"/>
        </li>

        <li class="wpl-imp-exp-imp-icon">
            <label class="wpl-gen-panel-label" for="wpl_import_file"><?php echo __('Import Settings', 'wpl'); ?>: </label>

            <?php
            $params = array('html_element_id' => 'wpl_import_file', 'html_path_message' => '.wpl_importexport .wpl_show_message', 'html_ajax_loader' => '#wpl_import_settings_ajax_loader', 'request_str' => 'admin.php?wpl_format=b:settings:ajax&wpl_function=import_settings', 'valid_extensions' => array('json', 'xml'));
            wpl_global::import_activity('ajax_file_upload:default', '', $params);
            ?>

            <span id="wpl_import_settings_ajax_loader"></span>

            <div id="wpl_import_settings_note" class="wpl-util-panel-note wpl-util-panel-note-error"><?php echo __('<b>Note:</b>This will override all your current settings! Proceed with caution.', 'wpl'); ?></div>
        </li>
    </ul>
</div>