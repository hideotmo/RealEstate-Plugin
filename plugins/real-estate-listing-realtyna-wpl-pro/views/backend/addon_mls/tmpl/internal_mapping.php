<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js');
?>
<div class="fanc-content size-width-2">
    <h2><?php echo __('Map', 'wpl').' '.$this->field_data->field_name.' '.__('to WPL', 'wpl'); ?></h2>
    <div class="wpl_show_message"></div>
    <div class="fanc-body">
        <div class="fanc-row  fanc-button-row-2">
        	<span id="wpl_ajax_loader_mls_mapping_field"></span>
        	<input type="hidden" name="wpl_field_id" id="wpl_field_id" value="<?php echo $this->field_data->wpl_field_id; ?>" />
            <input type="hidden" name="mls_field_custom1" id="mls_field_custom1" value="<?php echo $this->field_data->custom1; ?>" />
            <input type="hidden" name="mls_field_id" id="mls_field_id" value="<?php echo $this->id; ?>" />
            <?php if(trim($this->field_data->wpl_field_id) != ''): ?><input type="button" class="wpl-button button-1" value="<?php echo __('Remove Mapping', 'wpl'); ?>" onclick="wpl_remove_mapping();" /><?php endif; ?>
            <input type="button" class="wpl-button button-1" value="<?php echo __('Save', 'wpl'); ?>" onclick="wpl_save_mapping();" />
        </div>
        <div class="col-wp">
            <div class="col-fanc-left fanc-tabs-wp">
                <ul>
                	<?php $i = 1; foreach($this->wpl_categories as $wpl_category): ?>
                    <li class="<?php echo ($i == 1 ? 'active' : ''); ?>"><a href="#<?php echo $wpl_category->id; ?>" class="tab-wplc<?php echo $wpl_category->id; ?>" id="wpl_slide_label_id_wplc<?php echo $wpl_category->id; ?>" onclick="rta.internal.slides.open('_wplc<?php echo $wpl_category->id; ?>','.fanc-tabs-wp','.fanc-content-body');"><?php echo __($wpl_category->name, 'wpl'); ?></a></li>
                    <?php $i++; endforeach; ?>
                </ul>
            </div>
            <div class="col-fanc-right fanc-content-wp" id="wpl_modify_mls">
            	<?php $i = 1; foreach($this->wpl_categories as $wpl_category): $fields = wpl_flex::get_fields($wpl_category->id); ?>
                <div class="fanc-content-body" id="wpl_slide_container_id_wplc<?php echo $wpl_category->id; ?>" <?php echo ($i != 1 ? 'style="display: none"' : ''); ?>>
                    <?php foreach($fields as $field): if(in_array($field->type, array('separator'))) continue; ?>
                    <div class="wpl_field_container <?php echo ($this->field_data->wpl_field_id == $field->id ? 'wpl_field_selected' : (in_array($field->id, $this->mapped_fields) ? 'wpl_field_mapped' : '')); ?>" id="wpl_field_container<?php echo $field->id; ?>" onclick="wpl_field_select(<?php echo $field->id; ?>, '<?php echo $field->type; ?>');"><?php echo __($field->name, 'wpl'); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var location_custom_html = '';
var location_custom_html_loaded = false;

wplj(document).ready(function()
{
	location_custom_html += '<select id="wpl_location_custom_selectbox" onchange="wplj(\'#mls_field_custom1\').val(this.value);">';
	location_custom_html += '<option value="">----</option>';
	<?php for($i=1; $i<=7; $i++): if(trim($this->location_settings['location'.$i.'_keyword']) == '') continue; ?>
	location_custom_html += '<option <?php echo ($this->field_data->custom1 == $i ? 'selected="selected"' : ''); ?> value="<?php echo $i; ?>"><?php echo $this->location_settings['location'.$i.'_keyword']; ?></option>';
	<?php endfor; ?>
	location_custom_html += '<option <?php echo ($this->field_data->custom1 == 'zip' ? 'selected="selected"' : ''); ?> value="zip"><?php echo $this->location_settings['locationzips_keyword']; ?></option>';
	location_custom_html += '</select>';
	
	<?php if($this->field_data->wpl_field_id == 41): ?>
	wplj("#wpl_field_container41").trigger("click");
	<?php endif; ?>
});
</script>