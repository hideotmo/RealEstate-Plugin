<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="page fixed" id="option_container">
    <div class="odd">
		<span class="settings_name"><label for="rets_version"><?php echo __('RETS Version', 'wpl'); ?> :</label></span>
		<input type="text" name="rets_version" id="rets_version" value="<?php echo (isset($this->mls_server->rets_version) ? $this->mls_server->rets_version : 'RETS/1.5'); ?>" />
	</div>
	<div>
		<span class="settings_name"><label for="resource"><?php echo __('Resource', 'wpl'); ?> :</label></span>
		<input type="text" name="resource" id="resource" value="<?php echo (isset($this->mls_server->resource) ? $this->mls_server->resource : 'Property'); ?>" />
	</div>
    <div class="odd">
		<span class="settings_name"><label for="image_resource"><?php echo __('Image Resource', 'wpl'); ?> :</label></span>
		<input type="text" name="image_resource" id="image_resource" value="<?php echo (isset($this->mls_server->image_resource) ? $this->mls_server->image_resource : 'Photo'); ?>" />
	</div>
    <div>
		<span class="settings_name"><label for="image_location"><?php echo __('External Image', 'wpl'); ?> :</label></span>
		<select name="image_location" id="image_location">
        	<option value="0" <?php if($this->mls_server->image_location == 0) echo 'selected="selected"'; ?>><?php echo __('No', 'wpl'); ?></option>
            <option value="1" <?php if($this->mls_server->image_location == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', 'wpl'); ?></option>
        </select>
	</div>
	<?php /** If the MLS Agent add-on is installed **/ if(wpl_global::check_addon('mls_agents')): ?>
	<div class="odd">
		<span class="settings_name"><label for="mls_agent_id_field"><?php echo __('MLS Agent Field', 'wpl'); ?> :</label></span>
		<input type="text" value="<?php echo $this->mls_server->mls_agent_id_field; ?>" id="mls_agent_id_field" name="mls_agent_id_field" />
	</div>
	<?php endif; ?>
    <?php /** If the neighborhoods add-on is installed **/ if(wpl_global::check_addon('neighborhoods')): ?>
	<div>
		<span class="settings_name"><label for="neighborhood_field"><?php echo __('Neighborhood Field', 'wpl'); ?> :</label></span>
		<input type="text" value="<?php echo $this->mls_server->neighborhood_field; ?>" id="neighborhood_field" name="neighborhood_field" />
	</div>
	<?php endif; ?>
    <?php /** If the Complex add-on is installed **/ if(wpl_global::check_addon('complex')): ?>
	<div>
		<span class="settings_name"><label for="complex_field"><?php echo __('Complex Field', 'wpl'); ?> :</label></span>
		<input type="text" value="<?php echo $this->mls_server->complex_field; ?>" id="complex_field" name="complex_field" />
	</div>
	<?php endif; ?>
</div>