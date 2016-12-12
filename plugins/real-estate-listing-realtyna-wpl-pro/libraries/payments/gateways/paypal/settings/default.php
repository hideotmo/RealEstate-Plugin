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
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_method"><?php echo __('Payment Method', 'wpl'); ?></label>
        <select id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_method" name="settings[<?php echo $this->get_gateway_key(); ?>][method]">
            <option value="1" <?php echo ((isset($settings['method']) and $settings['method'] == 1) ? 'selected="selected"' : ''); ?>><?php echo __('Express Checkout', 'wpl'); ?></option>
            <option value="2" <?php echo ((isset($settings['method']) and $settings['method'] == 2) ? 'selected="selected"' : ''); ?>><?php echo __('Direct Payment', 'wpl'); ?></option>
            <option value="3" <?php echo ((isset($settings['method']) and $settings['method'] == 3) ? 'selected="selected"' : ''); ?>><?php echo __('Both', 'wpl'); ?></option>
        </select>
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_username"><?php echo __('Username', 'wpl'); ?></label>
        <input type="text" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_username" name="settings[<?php echo $this->get_gateway_key(); ?>][username]" value="<?php echo (isset($settings['username']) ? $settings['username'] : ''); ?>" placeholder="<?php echo __('Paypal Username', 'wpl'); ?>" />
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_password"><?php echo __('Password', 'wpl'); ?></label>
        <input type="text" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_password" name="settings[<?php echo $this->get_gateway_key(); ?>][password]" value="<?php echo (isset($settings['password']) ? $settings['password'] : ''); ?>" placeholder="<?php echo __('Paypal Password', 'wpl'); ?>" />
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_signature"><?php echo __('Signature', 'wpl'); ?></label>
        <input type="text" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_signature" name="settings[<?php echo $this->get_gateway_key(); ?>][signature]" value="<?php echo (isset($settings['signature']) ? $settings['signature'] : ''); ?>" placeholder="<?php echo __('Paypal API Signature', 'wpl'); ?>" />
    </div>
    <div class="wpl-payment-settings-row prow">
        <label for="wpl_gateway<?php echo $this->get_gateway_id(); ?>_account"><?php echo __('Business Account', 'wpl'); ?></label>
        <input type="text" id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_account" name="settings[<?php echo $this->get_gateway_key(); ?>][account]" value="<?php echo (isset($settings['account']) ? $settings['account'] : ''); ?>" placeholder="<?php echo __('Paypal Business Email', 'wpl'); ?>" />
    </div>
</div>