<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$this->minimum_charge = isset($params['minimum_charge']) ? $params['minimum_charge'] : 1;
$this->default_amount = isset($params['default_amount']) ? $params['default_amount'] : 10;
$this->unit_id = isset($params['unit_id']) ? $params['unit_id'] : 260;
$this->show_charge_form = isset($params['show_charge_form']) ? $params['show_charge_form'] : 1;
$this->minimum_charge_rendered = wpl_render::render_price($this->minimum_charge, $this->unit_id);
$this->client = wpl_global::get_client() ? 'backend' : 'frontend';

$this->user_id = wpl_users::get_cur_user_id();
if($this->client == 'backend') $this->user_id = wpl_request::getVar('id', wpl_users::get_cur_user_id());

$this->payments = new wpl_payments();
$this->ewallet = $this->payments->get('ewallet');
$this->balance = $this->ewallet->balance($this->user_id, $this->unit_id);
$this->rendered = wpl_render::render_price($this->balance, $this->unit_id);

/** importing js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.'.$this->client, true, true);
?>
<div class="wpl-ewallet-wp">
    <div class="wpl-ewallet-balance">
        <?php echo sprintf(__('Your current balance is <span>%s</span>', 'wpl'), $this->rendered); ?>
    </div>
    <?php if($this->show_charge_form): ?>
    <div class="wpl-ewallet-charge">
        <form id="wpl_ewallet_charge_form">
            <input type="number" id="wpl_ewallet_charge_amount" name="wpl_ewallet_charge_amount" value="<?php echo $this->default_amount; ?>" autocomplete="off" />
            <input type="hidden" id="wpl_ewallet_charge_unit_id" name="wpl_ewallet_charge_unit_id" value="<?php echo $this->unit_id; ?>" autocomplete="off" />
            <input type="submit" class="wpl-button button-1" value="<?php echo __('Charge', 'wpl'); ?>" />
            <span id="wpl_ewallet_charge_ajax_loader"></span>
        </form>
        <div class="wpl-ewallet-charge-show-messages"></div>
        <div id="wpl_ewallet_checkout_page_container" class="wpl_hidden_element"></div>
        <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" id="wpl_ewallet_lightbox_handler" class="wpl_hidden_element" data-realtyna-href="#wpl_ewallet_checkout_page_container"></span>
    </div>
    <?php endif; ?>
</div>