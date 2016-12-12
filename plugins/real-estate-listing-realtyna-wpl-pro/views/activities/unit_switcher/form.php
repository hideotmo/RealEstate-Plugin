<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

// Default value for uni type
if(!isset($this->options->unit_type)) $this->options->unit_type = 4;

$unit_types = wpl_units::get_unit_types();
?>
<div class="fanc-row">
    <label for="wpl_o_unit_type"><?php echo __('Unit Type', 'wpl'); ?></label>
    <select class="text_box" name="option[unit_type]" id="wpl_o_unit_type">
        <?php foreach($unit_types as $unit_type): ?>
        <option <?php if(isset($this->options->unit_type) and $this->options->unit_type == $unit_type['id']) echo 'selected="selected"'; ?> value="<?php echo $unit_type['id']; ?>"><?php echo __($unit_type['name'], 'wpl'); ?></option>
        <?php endforeach; ?>
    </select>
</div>