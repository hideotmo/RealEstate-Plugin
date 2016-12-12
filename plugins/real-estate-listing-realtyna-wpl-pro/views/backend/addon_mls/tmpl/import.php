<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js_import');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48">
        </div>
        <h2><?php echo __('MLS Addon / Import', 'wpl'); ?></h2>
    </header>
    <div class="sidebar-wp" id="wpl_mls_addon_query">
    	<p class="wpl_gold_msg">
			<?php echo sprintf(__('Importing from <strong>%1$s</strong> -- <strong>%2$s</strong>', 'wpl'), $this->mls_server_data->mls_name, $this->query_data->mls_class_name); ?>
            <span id="wpl_run_import_button" class="italic_span link_span" onclick="wpl_run_import('<?php echo $this->id; ?>', '<?php echo $this->query_data->limit; ?>');">&nbsp;<?php echo __('Start', 'wpl'); ?></span>
        </p>
        <div class="wpl_item_list"><div class="wpl_show_message"></div></div>
        <div id="wpl_import_log"></div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>