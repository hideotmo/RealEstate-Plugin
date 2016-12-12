<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp logs-wp">
    <header>
        <div id="icon-logs" class="icon48"></div>
        <h2><?php echo __('Logs', 'wpl'); ?></h2>
    </header>
    <div class="sidebar-wp">
        <div class="side-15">
            <div class="panel-wp">
                <h3>
                    <?php echo __('Log Tools', 'wpl'); ?>
                </h3>
                <div class="panel-body">
                    <div class="log_tools">
                        <input placeHolder="<?php echo __('Section', 'wpl'); ?>" type="text" id="wpl_search_section" value="<?php echo $this->text_search; ?>" />
                        <input placeHolder="<?php echo __('Date start', 'wpl'); ?>" type="text" id="wpl_search_date_from" value="<?php echo $this->select_log_datemin; ?>" />
                        <input placeHolder="<?php echo __('Date end', 'wpl'); ?>" type="text" id="wpl_search_date_end" value="<?php echo $this->select_log_datemax; ?>" />
                        <select id="wpl_search_list_addons">
                            <option value=""><?php echo __('Add-on', 'wpl'); ?></option>
                            <?php foreach($this->addons as $addon): ?>
                            <option value="<?php echo $addon->id; ?>" <?php echo $addon->id == $this->addon ? 'selected' : ''; ?>><?php echo $addon->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="wpl_search_list_priority">
                            <option value="" <?php echo $this->priority == '' ? 'selected' : ''; ?>><?php echo __('Priority', 'wpl'); ?></option>
                            <?php for($i=1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $this->priority ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <input class="button" type="submit" value="<?php echo __('Search', 'wpl'); ?>" onclick="wpl_search_log();" />
                        <input class="button" type="submit" value="<?php echo __('Reset', 'wpl'); ?>" onclick="wpl_reset_search_log();" />
                    </div> 
                    
                    <div class="logs_delete_export_container">
                        <div class="export_button">
                            <strong><?php echo __('Export All Logs', 'wpl'); ?>: </strong> 
                            <?php if(wpl_global::check_addon('exporter')): ?>
                            <a href="<?php echo wpl_global::get_wpl_admin_menu('wpl_addon_exporter'); ?>"><?php echo __('Exporter add-on', 'wpl'); ?></a>
                            <?php else: ?>
                            <?php echo __('Exporter add-on must be installed for this!', 'wpl'); ?>
                            <?php endif; ?>
                        </div>
                        <div class="delete_button">
                            <span id="wpl_log_ajax_loader_delete_all_logs"></span>
                            <input class="button" type="submit" value="<?php echo __('Delete All Logs', 'wpl'); ?>" onclick="wpl_delete_all_logs();" />
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
            <div class="pagination-wp">
                <?php echo $this->pagination->show(); ?>
            </div>
            <?php endif; ?>
			<div class="wpl_log_list"><div class="wpl_show_message"></div></div>
            <?php if(!wpl_global::get_setting('log', 1)): ?>
            <p>
                <?php echo __('Notice: Auto Log feature is disabled, You can enable it in WPL Settings menu.', 'wpl'); ?>
            </p>
            <?php endif; ?>
            <table class="widefat page fixed">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('ID', 'wpl'), 'id'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Addon', 'wpl'), 'addon_id'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Section', 'wpl'), 'section'); ?></th>
                        <th scope="col" class="manage-column size-8"><?php echo wpl_global::order_table(__('Log Text', 'wpl'), 'log_text'); ?></th>
                        <th scope="col" class="manage-column size-4"><?php echo wpl_global::order_table(__('Log Date', 'wpl'), 'log_date'); ?></th>
                        <th scope="col" class="manage-column size-3"><?php echo wpl_global::order_table(__('IP', 'wpl'), 'ip'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Priority', 'wpl'), 'priority'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo __('Actions', 'wpl'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('ID', 'wpl'), 'id'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Addon', 'wpl'), 'addon_id'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Section', 'wpl'), 'section'); ?></th>
                        <th scope="col" class="manage-column size-8"><?php echo wpl_global::order_table(__('Log Text', 'wpl'), 'log_text'); ?></th>
                        <th scope="col" class="manage-column size-4"><?php echo wpl_global::order_table(__('Log Date', 'wpl'), 'log_date'); ?></th>
                        <th scope="col" class="manage-column size-3"><?php echo wpl_global::order_table(__('IP', 'wpl'), 'ip'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo wpl_global::order_table(__('Priority', 'wpl'), 'priority'); ?></th>
                        <th scope="col" class="manage-column size-2"><?php echo __('Actions', 'wpl'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                	<?php if(!count($this->wp_logs)): ?>
                    <tr>
                    	<td class="wpl_no_item" colspan="8"><?php echo __('No logs', 'wpl'); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php
                    foreach ($this->wp_logs as $wp_log)
					{
                        $link = $this->admin_url . "admin.php?page=wpl_admin_log_manager";
                        $addon_name = wpl_global::get_addon($wp_log->addon_id);
                    ?>
                    <tr id="item_row<?php echo $wp_log->id; ?>">
                        <td> <?php echo $wp_log->id; ?> </td>
                        <td> <?php echo $addon_name['name'] != null ? $addon_name['name'] : __('WPL Core', 'wpl') ; ?> </td>
                        <td> <?php echo $wp_log->section; ?>  </td>
                        <td> <?php echo $wp_log->log_text; ?> </td>
                        <td> <?php echo $wp_log->log_date; ?> </td>
                        <td> <?php echo $wp_log->ip; ?> </td>
                        <td> <?php echo $wp_log->priority > 10 ? '<span title="'.__("More than 10", 'wpl').'">10*</span>' : $wp_log->priority; ?> </td>
                        <td>
                            <span class="action-btn icon-recycle" title="<?php echo __('Delete Log ID', 'wpl').' '.$wp_log->id; ?>" onclick="wpl_delete_single_log(<?php echo $wp_log->id; ?>);"></span>
                            <span id="wpl_log_ajax_loader<?php echo $wp_log->id; ?>"></span>
                        </td>
                    </tr>
	                <?php } ?>
                </tbody>
            </table>
            <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
            <div class="pagination-wp">
                <?php echo $this->pagination->show(); ?>
            </div>
            <?php endif; ?>
         
        </div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>