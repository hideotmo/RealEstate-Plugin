<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js_query');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48">
        </div>
        <h2><?php echo __('MLS Addon / Query Wizard', 'wpl'); ?></h2>
    </header>
    <div class="wpl_item_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp" id="wpl_mls_addon_query">
    	<table class="widefat page">
            <thead>
                <tr>
                    <th colspan="6">
                        <div class="action-wp">
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_mls_query_div" class="wpl_create_new action-btn icon-plus" title="<?php echo __('Add a new Query', 'wpl'); ?>" onclick="wpl_generate_modify_query();"></span>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th scope="col" class="manage-column"><?php echo __('ID', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('MLS Server', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('MLS Class', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Default User', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Import', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Actions', 'wpl'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                	foreach($this->mls_queries as $mls_query):
						
						$user_data = wpl_users::get_user($mls_query->default_user_id);
						$server_data = wpl_addon_mls::get_servers($mls_query->mls_server_id);
				?>
                <tr id="item_row<?php echo $mls_query->id; ?>">
                    <td><?php echo $mls_query->id; ?></td>
                    <td><?php echo $server_data->mls_name; ?></td>
                    <td><?php echo $mls_query->mls_class_name; ?></td>
                    <td><?php echo $user_data->data->user_login; ?></td>
                    <td title="<?php echo __('Last sync').' : '.($mls_query->last_sync_date == '0000-00-00 00:00:00' ? __('Never', 'wpl') : $mls_query->last_sync_date); ?>"><a href="<?php echo wpl_global::get_full_url(); ?>&tpl=import&id=<?php echo $mls_query->id; ?>"><?php echo __('Import', 'wpl'); ?></a></td>
                    <td class="wpl_manager_td">
                        <span data-realtyna-lightbox data-realtyna-href="#wpl_mls_query_div" class="action-btn icon-gear" onclick="wpl_generate_params_page('<?php echo $mls_query->id; ?>');"></span>
                        <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_mls_query_div" class="action-btn icon-edit" onclick="wpl_generate_modify_query(<?php echo $mls_query->id; ?>)"></span>
                        <span class="action-btn icon-recycle wpl_show" onclick="wpl_remove_mls_query(<?php echo $mls_query->id; ?>);"></span>
                        <span class="action-btn <?php echo $mls_query->enabled ? 'icon-enabled' : 'icon-disabled'; ?>" id="wpl_mls_query_enabled<?php echo $mls_query->id ?>" onclick="wpl_set_enabled_mls_query(<?php echo $mls_query->id ?>);" title="<?php echo $mls_query->enabled ? __('Auto sync is enabled.', 'wpl') : __('Auto sync is disabled.', 'wpl'); ?>"></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="wpl_mls_query_div" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>