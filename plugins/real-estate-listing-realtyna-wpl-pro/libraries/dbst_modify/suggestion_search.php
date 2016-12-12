<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'suggestion_search' and !$done_this)
{
_wpl_import('libraries.locations');
_wpl_import('libraries.addon_aps');

$location_settings = wpl_global::get_settings('3');

/** get units **/
$units = wpl_units::get_units(1);

$great_units = array();
foreach($units as $unit) if($unit['tosi'] > 100) $great_units[] = $unit;
?>
<script type="text/javascript">
(function($,window, document){
    $(function(){
        $("div.sortable").sortable(
        {
            handle: 'span.icon-move',
            cursor: "move" ,
            update : function(e, ui)
            {
                $('input[rel="sortable_suggestion_fields"]').each(function(i, element)
                {
                    $(element).attr('value', i);
                });
            }
        });
    });
})(jQuery, window, document);

function wpl_flex_disable_param(param_id)
{
    if (wplj("#wpl_felx_change_param_status" + param_id).hasClass("wpl_actions_icon_enable"))
    {
        wplj("#suggestion_search_field_" + param_id + " input[type='text']").attr("disabled", "disabled");
        wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_enable");
        wplj("#wpl_felx_change_param_status" + param_id).removeClass("icon-enabled");
        wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_disable");
        wplj("#wpl_felx_change_param_status" + param_id).addClass("icon-disabled");
        wplj("#suggestion_search_field_" + param_id + " input[name='<?php echo $__prefix; ?>opt_values[" + param_id + "][enabled]']").val(0);
    }
    else
    {
        wplj("#suggestion_search_field_" + param_id + " input[type='text']").removeAttr("disabled");
        wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_disable");
        wplj("#wpl_felx_change_param_status" + param_id).removeClass("icon-disabled");
        wplj("#wpl_felx_change_param_status" + param_id).addClass("icon-enabled");
        wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_enable");
        wplj("#suggestion_search_field_" + param_id + " input[name='<?php echo $__prefix; ?>opt_values[" + param_id + "][enabled]']").val(1);
    }
}
</script>
<div class="fanc-body">
	<div class="fanc-row fanc-button-row-2">
        <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
		<input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save', 'wpl'); ?>" id="wpl_dbst_submit_button" />
	</div>
	<div class="col-wp">
		<div class="col-fanc-left" id="wpl_flex_general_options">
			<div class="fanc-row fanc-inline-title">
				<?php echo __('General Options', 'wpl'); ?>
			</div>
			<?php
				/** include main file **/
				include _wpl_import('libraries.dbst_modify.main.main', true, true);
			?>
		</div>
         <div class="col-fanc-right" id="wpl_flex_specific_options">
            <div class="fanc-row fanc-inline-title">
                    <span>
                        <?php echo __('Nearby Params', 'wpl'); ?>    
                    </span>
            </div>
            <?php 
                $option_raduis_unit_params = (isset($options['radius_unit'])) ? $options['radius_unit'] : '13';
                $option_raduis_distance = (isset($options['radius_distance'])) ? $options['radius_distance'] : '10';
            ?>
            <div class="fanc-row">
                <label for="<?php echo $__prefix; ?>opt_radius_unit"><?php echo __('Unit type', 'wpl'); ?></label>
                <select class="wpl_search_widget_radius_unit_selectbox" name="<?php echo $__prefix; ?>opt_radius_unit" id="<?php echo $__prefix; ?>opt_radius_unit">';
                    <?php foreach($great_units as $unit) echo '<option value="'.$unit['id'].'" '.($option_raduis_unit_params == $unit['id']? 'selected="selected"' : '').'>'.$unit['name'].'</option>'; ?>
               </select>
            </div>
            <div class="fanc-row">
                <label for="<?php echo $__prefix; ?>opt_radius_distance"><?php echo __('Distance/Radius', 'wpl'); ?></label>
                <span><input type="text" size="18" placeHolder="<?php echo __("Type a default text", 'wpl'); ?>" name="<?php echo $__prefix; ?>opt_radius_distance" id="<?php echo $__prefix; ?>opt_radius_distance" value="<?php echo $option_raduis_distance; ?>" />
                </span>
            </div>
        </div>

		<div class="col-fanc-right" id="wpl_flex_specific_options">
            <div class="fanc-row fanc-inline-title">
				<span>
					<?php echo __('Params', 'wpl'); ?>    
				</span>
			</div>
            <div class="fanc-row sortable">
            <?php
            $option_params = (isset($options['values']) and is_array($options['values'])) ? $options['values'] : array();
            $addon_aps = new wpl_addon_aps;
            $search_fields = $addon_aps->get_suggestion_search_fields($option_params);

            foreach ($search_fields as $field_name=>$field_value)
        	{
    			if(stristr($field_name, 'location') or $field_name == 'zip_name')
				{
					$field_name_rendered = isset($location_settings[$field_name.'_keyword']) ? $location_settings[$field_name.'_keyword'] : ucfirst(str_replace('_', ' ', $field_name));

					$field_name_rendered = $field_name_rendered == '' ? $field_name : $field_name_rendered ;
				}
				else $field_name_rendered = ucfirst(str_replace('_', ' ', $field_name));
				?>
				<div class="fanc-row" id="suggestion_search_field_<?php echo $field_name; ?>">
					<label for="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][placeholder]"><?php echo __($field_name_rendered, 'wpl'); ?></label>
                    <!-- Placeholder text -->
        			<span><input type="text" size="18" placeHolder="<?php echo __('Type a default text', 'wpl'); ?>" name="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][placeholder]" id="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][placeholder]" value="<?php echo $option_params[$field_name]['placeholder']; ?>" <?php echo (!$option_params[$field_name]['enabled'] ? 'disabled="disabled"' : ''); ?> />
        			</span>
                    <!-- Enable/Disable  -->
                    <span class="margin-left-1p action-btn icon-<?php echo ($option_params[$field_name]['enabled'] ? 'enabled wpl_actions_icon_enable' : 'disabled'); ?>" id="wpl_felx_change_param_status<?php echo $field_name; ?>" onclick="wpl_flex_disable_param('<?php echo $field_name; ?>');"></span>
                    <input type="hidden" id="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][enabled]" name="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][enabled]" value="<?php echo $option_params[$field_name]['enabled']; ?>" />
                    <!-- Sorting -->
                    <span id="field_move_<?php echo $field_name; ?>" class="action-btn icon-move ui-sortable-handle"></span>
        			<input type="hidden" name="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][index]" id="<?php echo $__prefix; ?>opt_values[<?php echo $field_name; ?>][index]" rel="sortable_suggestion_fields" value="<?php echo $option_params[$field_name]['index']; ?>">
                    
    			</div>
            <?php } ?>
			</div>
		</div>
	</div>
    <div class="col-wp">
        <div class="col-fanc-left">
        	<div class="fanc-row fanc-inline-title">
                <?php echo __('Accesses', 'wpl'); ?>
            </div>
            <?php
				/** include accesses file **/
				include _wpl_import('libraries.dbst_modify.main.accesses', true, true);
            ?>
        </div>
    </div>
</div>
<?php
    $done_this = true;
}