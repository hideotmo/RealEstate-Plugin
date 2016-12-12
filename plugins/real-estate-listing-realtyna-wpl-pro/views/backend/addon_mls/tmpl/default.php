<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48"></div>
        <h2><?php echo __('MLS Addon / Servers', 'wpl'); ?></h2>
    </header>
    <div class="wpl_mls_servers_list"><div class="wpl_show_message"></div></div>
    <p class="need-help"><?php echo __('Need Help?', 'wpl'); ?> <a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/559/" target="_blank"><?php echo __('Download MLS manual and find related KB articles', 'wpl'); ?></a></p>
    <div class="sidebar-wp">
    	<table class="widefat page">
            <thead>
                <tr>
                    <th colspan="6">
                        <div class="action-wp">
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_mls_server_edit_div" class="wpl_create_new action-btn icon-plus" title="<?php echo __('Add a new MLS server', 'wpl'); ?>" onclick="wpl_generate_modify_mls();"></span>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th scope="col" class="manage-column"><?php echo __('ID', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Name', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('RETS Version', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Connection / Import', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Mapping', 'wpl'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Actions', 'wpl'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->mls_servers as $mls_server): ?>
                    <tr id="item_row<?php echo $mls_server->id; ?>">
                        <td><?php echo $mls_server->id; ?></td>
                        <td><?php echo $mls_server->mls_name; ?></td>
                        <td><?php echo $mls_server->rets_version; ?></td>
                        <td class="test_connection_td">
                            <span class="action-btn <?php echo ($mls_server->connection_status ? 'icon-enabled' : 'icon-disabled'); ?>"></span>
                            <span class="italic_span" onclick="wpl_test_connection(<?php echo $mls_server->id; ?>);"><?php echo ($mls_server->connection_status ? __('Update fields', 'wpl') : __('Import fields', 'wpl')); ?></span>
                            <span id="wpl_ajax_loader_mls_server<?php echo $mls_server->id; ?>"></span>
                        </td>
                        <td class="mapping_td">
                            <a class="italic_span" href="<?php echo wpl_global::add_qs_var('page', 'wpl_addon_mls_mapping'); ?>&server_id=<?php echo $mls_server->id; ?>"><?php echo __('Map with WPL', 'wpl'); ?></a>
                        </td>
                        <td class="wpl_manager_td">
                            <span data-realtyna-lightbox data-realtyna-href="#wpl_mls_server_edit_div" class="action-btn icon-gear" onclick="wpl_generate_params_page('<?php echo $mls_server->id; ?>');"></span>
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_mls_server_edit_div" class="action-btn icon-edit" onclick="wpl_generate_modify_mls(<?php echo $mls_server->id; ?>)"></span>
                            <span class="action-btn icon-recycle wpl_show" onclick="wpl_remove_mls_server(<?php echo $mls_server->id; ?>);"></span>
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_mls_show_info_box<?php echo $mls_server->id; ?>" class="action-btn icon-plus wpl_show" title="<?php echo __($mls_server->mls_name.' Information', 'wpl'); ?>"></span>
                            
                            <!--MLS info box-->
                            <div id="wpl_mls_show_info_box<?php echo $mls_server->id; ?>" class="wpl_hidden_element"> 
                                <div class="mls_box_info">
                                <?php 
                                   $mls_info = json_decode($mls_server->mls_info, true);
                                   $html_info_box = '';
                                   if(isset($mls_info['objects']))
                                   {
                                       $html_info_box .= '<strong># Objects: </strong><br /> <ol>';
                                       foreach($mls_info['objects'] as $object)
                                       {
                                           $ObjectType = $object['ObjectType'];
                                           $Description = $object['Description'];
                                           $html_info_box .= "<li> Object <i>{$ObjectType}</i> described as \"$Description\" </li>";
                                       }
                                       $html_info_box .= '</ol> <br /> <hr />';
                                   }
                                   if(isset($mls_info['classes']))
                                   {
                                       $html_info_box .= '<strong># Classes: </strong><br /> <ol>';
                                       foreach($mls_info['classes'] as $class)
                                       {
                                           $ClassName = $class['ClassName'];
                                           $Description = $class['Description'];
                                           $html_info_box .= "<li> Class <i>{$ClassName}</i> described as \"$Description\" </li>";
                                       }
                                       $html_info_box .= '</ol>';
                                   }
                                   echo $html_info_box;
                                ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="wpl_mls_server_edit_div" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<style>
    .mls_box_info
    {
        padding: 10px;
    }
</style>