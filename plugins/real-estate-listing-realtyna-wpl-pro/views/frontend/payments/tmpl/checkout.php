<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_payments_checkout_page wpl_view_container wpl-payment-checkout-wp size-width-2">
    <h3>
        <?php echo __('Checkout', 'wpl'); ?>
    </h3>

    <div class="wpl_checkout_page">
        <div class="wpl_show_message"></div>
    </div>

    <div class="wpl-payment-cnt">

        <div class="wpl-payment-invoice">
            <?php if(trim($this->transaction['title'])): ?>
                <div class="wpl-payment-invoice-title">
                    <?php echo __($this->transaction['title'], 'wpl'); ?>
                </div>
            <?php endif; ?>
            <?php if(isset($this->amount)): ?>
                <div class="wpl-payment-invoice-amount">
                    <?php echo sprintf(__('Total amount is: <span>%s</span>', 'wpl'), $this->amount); ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="wpl-payment-tabs-system" class="wpl-js-tab-system">

            <div class="wpl-gen-tab-wp wpl-payment-tabs-wp">
                <ul>
                    <?php foreach($this->gateways as $gateway): ?>
                        <li class="wpl-membership-tab-title">
                            <a href="#wpl-payments-content<?php echo $gateway->get_gateway_id(); ?>">
                                <?php echo __($gateway->get_gateway_label(), 'wpl'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="wpl-gen-tab-contents-wp wpl-payment-contents-wp">
                <?php foreach($this->gateways as $gateway): ?>
                    <div class="wpl-gen-tab-content wpl-payment-content" id="wpl-payments-content<?php echo $gateway->get_gateway_id(); ?>">
                        <?php $gateway->payment_form($this->transaction_id); ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</div>