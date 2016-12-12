<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.listing', true, ($this->wplraw ? false : true));
?>
<div class="wpl-save-search-addon wpl-addon-save-search-list-wp <?php echo wpl_request::getVar('wpl_dashboard', 0) ? '' : 'wpl_view_container'; ?>" id="wpl_addon_save_searches_container">

    <?php if($this->users->is_administrator() and $this->user_id != $this->users->get_cur_user_id()): ?>
        <div class="wpl-addon-save-search-username"><?php echo sprintf(__('Saved Searches of %s', 'wpl'), '<span>'.$this->user_data->data->user_login.'</span>'); ?></div>
    <?php endif; ?>

    <?php if($this->users->is_administrator() and wpl_sef::is_permalink_default()): ?>
        <div class="wpl_message_container"><?php echo __("For using alias feature of saved searches add-on, your WordPress Permalink structure shouldn't set to default.", 'wpl'); ?></div>
    <?php endif; ?>

    <div class="wpl-save-search-msg" id="wpl_save_searches_list_show_messages"></div>

    <table class="wpl-gen-grid-wp wpl-gen-grid-center wpl-addon-save-search-grid" id="wpl_addon_save_searches_list_container">
        <thead>
            <tr>
                <th><?php echo __('Search', 'wpl'); ?></th>
                <th><?php echo __('Created', 'wpl'); ?></th>
                <?php if($this->users->is_administrator() and !wpl_sef::is_permalink_default()): ?>
                <th><?php echo __('Alias', 'wpl'); ?></th>
                <th><?php echo __('Link', 'wpl'); ?></th>
                <?php endif; ?>
                <th><span class="wpl-addon-save-search-remove-btn" id="wpl_addon_save_searches_delete_all" onclick="wpl_addon_save_searches_delete_all(<?php echo $this->user_id; ?>, 0);" title="<?php echo __('Delete All', 'wpl'); ?>"></span></th>
            </tr>
        </thead>

        <tbody>

            <?php foreach($this->searches as $search): ?>
            <tr id="wpl_addon_save_search_item<?php echo $search['id']; ?>">

                <td><a target="_blank" href="<?php echo $search['url']; ?>"><?php echo $search['name']; ?></a></td>
                <td><?php echo $search['creation_date']; ?></td>

                <?php if($this->users->is_administrator() and !wpl_sef::is_permalink_default()): ?>
                    <td><input type="text" id="wpl_addon_save_searches_alias<?php echo $search['id']; ?>" placeholder="<?php echo __('Set an alias for link...', 'wpl'); ?>" onchange="wpl_addon_save_searches_alias(<?php echo $search['id']; ?>);" value="<?php echo $search['alias']; ?>" /></td>

                    <td><a class="wpl-addon-save-search-show-link-btn" id="wpl_addon_save_searches_link<?php echo $search['id']; ?>" href="<?php echo $this->save_searches->URL($search['id']); ?>" target="_blank" title="<?php echo __('Open SEF link', 'wpl'); ?>"></a></td>
                <?php endif; ?>

                <td><span class="wpl-addon-save-search-remove-btn" id="wpl_addon_save_searches_delete<?php echo $search['id']; ?>" onclick="wpl_addon_save_searches_delete(<?php echo $search['id']; ?>, 0);" title="<?php echo __('Delete', 'wpl'); ?>"></span></td>
            </tr>
            <?php endforeach; ?>

            <?php if(!count($this->searches)): ?>
                <tr>
                    <td class="wpl-gen-grid-no-result" colspan="1000">
                        <?php echo __('No saved search to show!', 'wpl'); ?>
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>