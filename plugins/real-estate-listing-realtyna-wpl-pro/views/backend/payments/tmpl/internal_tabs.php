<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_payments_top_tabs_container">
    <ul class="wpl-tabs">
        <li class="<?php echo ($this->tpl == 'default' ? 'wpl-selected-tab' : ''); ?>">
            <a href="<?php echo wpl_global::add_qs_var('tpl', 'default', wpl_global::get_wpl_admin_menu('wpl_admin_payments')); ?>"><?php echo __('Settings', 'wpl'); ?></a>
        </li>
        <li class="<?php echo ($this->tpl == 'transactions' ? 'wpl-selected-tab' : ''); ?>">
            <a href="<?php echo wpl_global::add_qs_var('tpl', 'transactions', wpl_global::get_wpl_admin_menu('wpl_admin_payments')); ?>"><?php echo __('Transactions', 'wpl'); ?></a>
        </li>
    </ul>
</div>