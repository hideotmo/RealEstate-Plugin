<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_walkscore_license_key"><?php echo __('License key', 'wpl'); ?></label>
    <input class="text_box" name="option[walkscore_license_key]" type="text" id="wpl_o_walkscore_license_key" value="<?php echo isset($this->options->walkscore_license_key) ? $this->options->walkscore_license_key : ''; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_walkscore_width"><?php echo __('Width', 'wpl'); ?></label>
    <input class="text_box" name="option[walkscore_width]" type="text" id="wpl_o_walkscore_width" value="<?php echo isset($this->options->walkscore_width) ? $this->options->walkscore_width : '317'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_walkscore_height"><?php echo __('Height', 'wpl'); ?></label>
    <input class="text_box" name="option[walkscore_height]" type="text" id="wpl_o_walkscore_height" value="<?php echo isset($this->options->walkscore_height) ? $this->options->walkscore_height : '460'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_walkscore_layout"><?php echo __('Walkscore layout', 'wpl'); ?></label>
    <select class="text_box" name="option[walkscore_layout]" type="text" id="wpl_o_walkscore_layout">
		<option value="vertical" <?php if(isset($this->options->walkscore_layout) and $this->options->walkscore_layout == 'vertical') echo 'selected="selected"'; ?>><?php echo __('Vertical', 'wpl'); ?></option>
	    <option value="horizontal" <?php if(isset($this->options->walkscore_layout) and $this->options->walkscore_layout == 'horizontal') echo 'selected="selected"'; ?>><?php echo __('Horizontal', 'wpl'); ?></option>
        <option value="none" <?php if(isset($this->options->walkscore_layout) and $this->options->walkscore_layout == 'none') echo 'selected="selected"'; ?>><?php echo __('None', 'wpl'); ?></option>
    </select>
</div>
