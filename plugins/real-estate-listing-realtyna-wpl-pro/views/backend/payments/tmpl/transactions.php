<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp payments-wp">
    <header>
        <div id="icon-payments" class="icon48"></div>
        <h2><?php echo __('Transactions', 'wpl'); ?></h2>
    </header>
    <?php $this->include_tabs(); ?>
    <div class="wpl_payments_list"><div class="wpl_show_message"></div></div>
    <div class="wpl-payments-transactions-search-form">
        <form method="GET" id="wpl_payments_transactions_search_form">
            <input type="hidden" name="page" value="wpl_admin_payments" />
            <input type="hidden" name="tpl" value="transactions" />
            <label for="sf_filter"><?php echo __('Filter', 'wpl'); ?>: </label>
            <input type="text" id="sf_filter" name="filter" value="<?php echo $this->filter; ?>" placeholder="<?php echo __('Transaction ID, Title, Description', 'wpl'); ?>" class="long" />
            <select name="sf_select_gateway" id="sf_select_category">
                <?php foreach($this->gateways as $gateway): ?>
                <option value="<?php echo $gateway; ?>" <?php if($gateway == $this->gateway) echo 'selected="selected"'; ?>><?php echo $this->objects[$gateway]->get_gateway_label(); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="sf_select_mode" id="sf_select_mode">
                <option value=""><?php echo __('Mode', 'wpl'); ?></option>
                <option value="1" <?php if(isset($this->vars['sf_select_mode']) and 1 == $this->vars['sf_select_mode']) echo 'selected="selected"'; ?>><?php echo __('+ (Plus)', 'wpl'); ?></option>
                <option value="2" <?php if(isset($this->vars['sf_select_mode']) and 2 == $this->vars['sf_select_mode']) echo 'selected="selected"'; ?>><?php echo __('- (Minus)', 'wpl'); ?></option>
            </select>
            <select name="sf_select_status" id="sf_select_status">
                <option value=""><?php echo __('Status', 'wpl'); ?></option>
                <option value="2" <?php if(isset($this->vars['sf_select_status']) and 2 == $this->vars['sf_select_status']) echo 'selected="selected"'; ?>><?php echo __('Waiting', 'wpl'); ?></option>
                <option value="0" <?php if(isset($this->vars['sf_select_status']) and trim($this->vars['sf_select_status']) != '' and 0 == $this->vars['sf_select_status']) echo 'selected="selected"'; ?>><?php echo __('Failed', 'wpl'); ?></option>
                <option value="1" <?php if(isset($this->vars['sf_select_status']) and 1 == $this->vars['sf_select_status']) echo 'selected="selected"'; ?>><?php echo __('Succeed', 'wpl'); ?></option>
            </select>
            <button class="button button-1"><?php echo __('Search', 'wpl'); ?></button>
            <button type="reset" class="button button-1" onclick="wpl_reset_transaction_form();"><?php echo __('Reset', 'wpl'); ?></button>
        </form>
    </div>
    <div class="wpl-payments-transactions-container">
        <?php include $this->transaction_path; ?>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>