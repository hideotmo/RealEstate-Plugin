<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->unit_type = isset($params['unit_type']) ? $params['unit_type'] : 4;
$this->units = wpl_units::get_units($this->unit_type);

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.default', true, true);
$current_unit = wpl_request::getVar('wpl_unit'.$this->unit_type, NULL, 'COOKIE');
?>
<div class="wpl_unit_switcher_activity" id="wpl_unit_switcher_activity<?php echo $this->activity_id; ?>">
    <select id="wpl_unit_switcher_activity<?php echo $this->activity_id; ?>_select" autocomplete="off">
        <?php foreach($this->units as $unit): $name = trim($unit['extra']) ? $unit['extra'] : $unit['name']; ?>
        <option value="<?php echo $unit['id']; ?>" <?php if($current_unit == $unit['id']) echo 'selected="selected"'; ?>><?php echo __($name, 'wpl'); ?></option>
        <?php endforeach; ?>
    </select>
</div>