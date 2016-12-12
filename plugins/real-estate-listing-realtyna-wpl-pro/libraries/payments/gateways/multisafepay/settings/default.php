<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_container" class="pwizard-section">
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_status"><?php echo __('Status', 'wpl'); ?></label>
        <select id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_status" name="settings[<?php echo $this->get_gateway_key(); ?>][status]">
            <option value="1" <?php echo ((isset($settings['status']) and $settings['status'] == 1) ? 'selected="selected"' : ''); ?>><?php echo __('Enabled', 'wpl'); ?></option>
            <option value="0" <?php echo ((isset($settings['status']) and $settings['status'] == 0) ? 'selected="selected"' : ''); ?>><?php echo __('Disabled', 'wpl'); ?></option>
        </select>
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_index"><?php echo __('Index', 'wpl'); ?></label>
        <input type="number" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_index" name="settings[<?php echo $this->get_gateway_key(); ?>][index]" placeholder="<?php echo __('Index', 'wpl'); ?>" value="<?php echo (isset($settings['index']) ? $settings['index'] : '2'); ?>" />
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_mode"><?php echo __('Mode', 'wpl'); ?></label>
        <select id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_mode" name="settings[<?php echo $this->get_gateway_key(); ?>][mode]">
            <option value="0" <?php echo ((isset($settings['mode']) and $settings['mode'] == 0) ? 'selected="selected"' : ''); ?>><?php echo __('Sandbox/Testing', 'wpl'); ?></option>
            <option value="1" <?php echo ((isset($settings['mode']) and $settings['mode'] == 1) ? 'selected="selected"' : ''); ?>><?php echo __('Live', 'wpl'); ?></option>
        </select>
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_api_key"><?php echo __('API Key', 'wpl'); ?></label>
        <input type="text" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_api_key" name="settings[<?php echo $this->get_gateway_key(); ?>][api_key]" value="<?php echo (isset($settings['api_key']) ? $settings['api_key'] : ''); ?>" placeholder="<?php echo __('API Key', 'wpl'); ?>" />
    </div>
</div>