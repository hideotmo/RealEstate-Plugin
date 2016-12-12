<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_image_width"><?php echo __('Image Width', 'wpl'); ?></label>
    <input class="text_box" name="option[image_width]" type="text" id="wpl_o_picture_width" value="<?php echo isset($this->options->image_width) ? $this->options->image_width : '210'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_image_height"><?php echo __('Image Height', 'wpl'); ?></label>
    <input class="text_box" name="option[image_height]" type="text" id="wpl_o_picture_height" value="<?php echo isset($this->options->image_height) ? $this->options->image_height: '240'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_bar_width"><?php echo __('Bar Width', 'wpl'); ?></label>
    <input class="text_box" name="option[bar_width]" type="text" id="wpl_o_bar_width" value="<?php echo isset($this->options->bar_width) ? $this->options->bar_width: '20'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_first_bar_length"><?php echo __('First Bar Length', 'wpl'); ?></label>
    <input class="text_box" name="option[first_bar_length]" type="text" id="wpl_o_first_bar_length" value="<?php echo isset($this->options->first_bar_length) ? $this->options->first_bar_length: '70'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_length_increment"><?php echo __('Bar Length Increment', 'wpl'); ?></label>
    <input class="text_box" name="option[length_increment]" type="text" id="wpl_o_length_increment" value="<?php echo isset($this->options->length_increment) ? $this->options->length_increment: '10'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_vertical_distance"><?php echo __('Vertical Distance Between Bars', 'wpl'); ?></label>
    <input class="text_box" name="option[vertical_distance]" type="text" id="wpl_o_vertical_distance" value="<?php echo isset($this->options->vertical_distance) ? $this->options->vertical_distance: '3'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_peak"><?php echo __('Peak of Bars', 'wpl'); ?></label>
    <input class="text_box" name="option[peak]" type="text" id="wpl_o_peak" value="<?php echo isset($this->options->peak) ? $this->options->peak: '10'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_energy_font_size"><?php echo __('Font Size for Energy Value', 'wpl'); ?></label>
    <input class="text_box" name="option[energy_font_size]" type="text" id="wpl_o_energy_font_size" value="<?php echo isset($this->options->energy_font_size) ? $this->options->energy_font_size: '10'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_font_size"><?php echo __('Font Size', 'wpl'); ?></label>
    <input class="text_box" name="option[font_size]" type="text" id="wpl_o_font_size" value="<?php echo isset($this->options->font_size) ? $this->options->font_size: '10'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_show_energy_value"><?php echo __('Show Energy Value', 'wpl'); ?></label>
    <select class="text_box" name="option[show_energy_value]" type="text" id="wpl_o_show_energy_value">
        <option value="1" <?php if(isset($this->options->show_energy_value) and $this->options->show_energy_value == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', 'wpl'); ?></option>
        <option value="0" <?php if(isset($this->options->show_energy_value) and $this->options->show_energy_value == '0') echo 'selected="selected"'; ?>><?php echo __('No', 'wpl'); ?></option>
    </select>
</div>