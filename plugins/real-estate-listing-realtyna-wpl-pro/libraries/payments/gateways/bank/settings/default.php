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
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_auto_confirm"><?php echo __('Auto Confirm', 'wpl'); ?></label>
        <select id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_auto_confirm" name="settings[<?php echo $this->get_gateway_key(); ?>][auto_confirm]">
            <option value="0" <?php echo ((isset($settings['auto_confirm']) and $settings['auto_confirm'] == 0) ? 'selected="selected"' : ''); ?>><?php echo __('Disabled', 'wpl'); ?></option>
            <option value="1" <?php echo ((isset($settings['auto_confirm']) and $settings['auto_confirm'] == 1) ? 'selected="selected"' : ''); ?>><?php echo __('Enabled', 'wpl'); ?></option>
        </select>
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_index"><?php echo __('Index', 'wpl'); ?></label>
        <input type="number" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_index" name="settings[<?php echo $this->get_gateway_key(); ?>][index]" placeholder="<?php echo __('Index', 'wpl'); ?>" value="<?php echo (isset($settings['index']) ? $settings['index'] : '3'); ?>" />
    </div>
    <div class="wpl-payment-settings-row prow prow-textarea">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_comment"><?php echo __('Comment', 'wpl'); ?></label>
        <textarea id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_comment" name="settings[<?php echo $this->get_gateway_key(); ?>][comment]" placeholder="<?php echo __('Comment for your users', 'wpl'); ?>"><?php echo (isset($settings['comment']) ? $settings['comment'] : ''); ?></textarea>
    </div>
</div>