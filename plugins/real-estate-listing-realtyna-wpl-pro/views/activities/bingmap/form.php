<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_authentication_key"><?php echo __('Authentication Key', 'wpl'); ?></label>
    <input class="text_box" name="option[authentication_key]" type="text" id="wpl_o_authentication_key" value="<?php echo isset($this->options->authentication_key) ? $this->options->authentication_key : ''; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_map_height"><?php echo __('Map Height', 'wpl'); ?></label>
    <input class="text_box" name="option[map_height]" type="text" id="wpl_o_map_height" value="<?php echo isset($this->options->map_height) ? $this->options->map_height : '385'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_default_zoom"><?php echo __('Default Zoom', 'wpl'); ?></label>
    <input class="text_box" name="option[default_zoom]" type="text" id="wpl_o_default_zoom" value="<?php echo isset($this->options->default_zoom) ? $this->options->default_zoom : '20'; ?>" />
</div>