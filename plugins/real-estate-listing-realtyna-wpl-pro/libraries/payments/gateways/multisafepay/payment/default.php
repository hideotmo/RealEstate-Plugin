<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$link = $this->create_transaction();
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_payment_container" class="wpl_gateway_payment_container wpl-util-clearfix">
    <div class="wpl-util-grid-1-1">
        <h4 class="wpl-gateway-label-method"><?php echo __('MultiSafePay', 'wpl'); ?></h4>
        <div class="wpl-payment-multisafepay-icon"></div>
        <form id="wpl_multisafepay_express_form<?php echo $this->transaction_id; ?>" action="<?php echo $link; ?>" method="post" target="_blank" onsubmit="wpl_multisafepay_pay_checker(<?php echo $this->transaction_id; ?>);">
            <div class="wpl-util-form-row">
                <input type="submit" class="btn btn-primary wpl-button button-1 wpl-button-medium" value="<?php echo __('Pay', 'wpl'); ?>" />
                <span id="wpl_multisafepay_express_ajax_loader"></span>
            </div>

            <div class="wpl-util-form-row">
                <span class="wpl-util-form-messages" id="wpl_multisafepay_express_message"></span>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function wpl_multisafepay_pay_checker(transaction_id)
{
    var message_path = '#wpl_multisafepay_express_message';
    var ajax_loader_element = "#wpl_multisafepay_express_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: 'wpl_format=f:payments:ajax&wpl_function=payment_express_check&transaction_id='+transaction_id+'&_wpnonce=<?php echo wpl_security::create_nonce('wpl_payments_payment_express_check'); ?>',
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            if(response.success == 1)
            {
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
                wplj(ajax_loader_element).html('');
                
                <?php foreach($this->system['javascript']['success'] as $js_success_code) echo $js_success_code."\n"; ?>
            }
            else if(response.success == 0)
            {
                wpl_show_messages('<?php echo __('Error Occurred!', 'wpl'); ?>', message_path, 'wpl_red_msg');
                wplj(ajax_loader_element).html('');

                <?php foreach($this->system['javascript']['error'] as $js_error_code) echo $js_error_code."\n"; ?>
            }
            else
            {
                setTimeout(function(){wpl_multisafepay_pay_checker(transaction_id)}, 10000);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages('<?php echo __('Error Occurred!', 'wpl'); ?>', message_path, 'wpl_red_msg');
            wplj(ajax_loader_element).html('');
            
            <?php foreach($this->system['javascript']['error'] as $js_error_code) echo $js_error_code."\n"; ?>
        }
    });
}
</script>