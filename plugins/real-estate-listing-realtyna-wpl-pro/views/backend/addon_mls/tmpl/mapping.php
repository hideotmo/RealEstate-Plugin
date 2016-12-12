<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js_mapping');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48"></div>
        <h2><?php echo __('MLS Addon / Mapping', 'wpl'); ?></h2>
    </header>
    <div class="wpl_item_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp" id="wpl_mls_addon_fields">
        
        <?php if(wpl_request::getVar('mls_class_id')) echo _wpl_import($this->tpl_path.'.internal_mapping_imp_exp'); ?>
        
        <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
    	<div class="pagination-wp">
			<?php echo $this->pagination->show(); ?>
        </div>
        
        <?php endif; ?>
    	<table id="wpl_mapping_fields_table" class="widefat page">
            <thead>
                <tr>
                    <th colspan="7">
                        <div class="action-wp">
                            <label for="server_id"><?php echo __('Server', 'wpl'); ?> :</label>
                            <select name="server_id" id="server_id" onchange="wpl_mls_server_select(this.value);">
                                <option value="">----</option>
                                <?php foreach ($this->mls_servers as $mls_server): ?>
                                <option value="<?php echo $mls_server->id; ?>" <?php if($mls_server->id == $this->server_id) echo 'selected="selected"'; ?>><?php echo $mls_server->mls_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if($this->server_id): ?>
                            <select name="mls_class_id" id="mls_class_id" onchange="wpl_mls_class_select(this.value);">
                                <option value="">----</option>
                                <?php foreach ($this->classes as $key=>$class): ?>
                                <option value="<?php echo $key; ?>" <?php if($key == $this->mls_class_id) echo 'selected="selected"'; ?>><?php echo ($class ? $class : $key); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <span id="wpl_ajax_loader_select_mls_server"></span>
                            <?php if($this->mls_class_id): ?>
                            &nbsp;<label for="mls_field_filter"><?php echo __('Filter', 'wpl'); ?> :</label>
                            <input type="text" name="mls_field_filter" id="mls_field_filter" value="<?php echo $this->mls_field_filter; ?>" onchange="wpl_mls_filter(this.value);" />
                            <?php endif; ?>
                        </div>
                    </th>
                </tr>
                <tr>
                	<th scope="col" class="manage-column"></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('ID', 'wpl'), 'id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Name', 'wpl'), 'field_name'); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Data type', 'wpl'), 'field_type'); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Sample data', 'wpl'), 'field_sample_data'); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('WPL field', 'wpl'), 'wpl_field_id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Mapping', 'wpl'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                	<th scope="col" class="manage-column" colspan="7">
                        <label for="wpl_field_type"><?php echo __('Field Type', 'wpl'); ?>: </label>
                        <select name="wpl_field_type" id="wpl_field_type">
                            <option value="">-----</option>
                            <?php foreach($this->dbst_types as $dbst_type): ?>
                            <option value="<?php echo $dbst_type->type; ?>"><?php echo __(ucfirst($dbst_type->type), 'wpl'); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="button" id="wpl_mapping_create_all_button" class="wpl-button button-1" value="<?php echo __('Create All', 'wpl'); ?>" onclick="wpl_auto_create();" />
                        <span id="wpl_ajax_loader_autocreate"></span>
                    </th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                	foreach($this->fields as $field):
					
					$wpl_field_data = wpl_flex::get_field($field->wpl_field_id);
					if($field->wpl_field_id) $category_data = wpl_flex::get_category($wpl_field_data->category);
				?>
                <tr id="item_row<?php echo $field->id; ?>" data-id="<?php echo $field->id; ?>">
                    <td class="wpl_manager_td"><input type="checkbox" class="wpl_create_all" name="wpl_create_all[]" id="wpl_create_all<?php echo $field->id; ?>" value="<?php echo $field->id; ?>" autocomplete="off" style="<?php if(trim($field->wpl_field_id) != '') echo 'display: none;'; ?>" /></td>
                    <td><?php echo $field->id; ?></td>
                    <td><?php echo $field->field_name; ?></td>
                    <td><?php echo $field->field_type; ?></td>
                    <td class="wpl_sample_data_td"><?php echo $field->field_sample_data; ?></td>
                    <td class="wpl_field"><?php if(trim($field->wpl_field_id) != '') echo __($category_data->name, 'wpl').' -> '.__($wpl_field_data->name, 'wpl'); ?></td>
                    <td class="wpl_mapping_action">
                        <span data-realtyna-lightbox data-realtyna-href="#wpl_mls_mapping_div" class="<?php echo (trim($field->wpl_field_id) != '' ? 'bold_span green_span' : 'italic_span gray_span'); ?>" onclick="wpl_generate_map_field(<?php echo $field->id; ?>);"><?php echo (trim($field->wpl_field_id) != '' ? __('Modify mapping', 'wpl') : __('Map a field', 'wpl')); ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="wpl_mls_mapping_div" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>