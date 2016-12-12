<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_direct_payment_container">
    <h4 class="wpl-gateway-label-method"><?php echo __('Direct Payment', 'wpl'); ?></h4>
    <form id="wpl_paypal_direct_form<?php echo $this->transaction_id; ?>" onsubmit="wpl_paypal_direct_send_request(<?php echo $this->transaction_id; ?>); return false;">
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_first_name"><?php echo __('First name', 'wpl'); ?></label>
            <input type="text" name="first_name" id="wpl_paypal_direct_first_name" />
        </div>
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_last_name"><?php echo __('Last name', 'wpl'); ?></label>
            <input type="text" name="last_name" id="wpl_paypal_direct_last_name" />
        </div>
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_card_type"><?php echo __('Card Type', 'wpl'); ?></label>
            <select name="card_type" id="wpl_paypal_direct_card_type" data-has-chosen data-chosen-opt="width:calc(100% - 100px)">
                <option value="Visa"><?php echo __('Visa', 'wpl'); ?></option>
                <option value="MasterCard"><?php echo __('MasterCard', 'wpl'); ?></option>
                <option value="Discover"><?php echo __('Discover', 'wpl'); ?></option>
                <option value="Amex"><?php echo __('American Express', 'wpl'); ?></option>
            </select>
        </div>
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_cc_number"><?php echo __('CC Number', 'wpl'); ?></label>
            <input type="text" name="cc_number" id="wpl_paypal_direct_cc_number" />
        </div>
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_expiration_date_month"><?php echo __('Expiration Date', 'wpl'); ?></label>
            <select name="expiration_date_month" id="wpl_paypal_direct_expiration_date_month" data-chosen-opt="width:60px|disable_search_threshold:100">
                <?php foreach(array('01','02','03','04','05','06','07','08','09','10','11','12') as $month): ?>
                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="expiration_date_year" id="wpl_paypal_direct_expiration_date_year" data-chosen-opt="width:calc(100% - 163px)|disable_search_threshold:100">
                <?php for($i = 0; $i <= 10; $i++): $y = date('Y', strtotime('+'.$i.' years')); ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="wpl-util-form-row">
            <label for="wpl_paypal_direct_cvv2"><?php echo __('CVV2', 'wpl'); ?></label>
            <input type="text" name="cvv2" id="wpl_paypal_direct_cvv2" />
        </div>
        
        <?php wpl_security::nonce_field('wpl_payments_paypal_direct'); ?>
        
        <div class="wpl-util-form-row">
            <input type="submit" class="btn btn-primary wpl-button button-1 wpl-button-medium wpl-util-float-right" value="<?php echo __('Pay', 'wpl'); ?>" />
            <span id="wpl_paypal_direct_ajax_loader"></span>
        </div>

        <div class="wpl-util-form-row">
            <span class="wpl-util-form-messages" id="wpl_paypal_direct_message"></span>
        </div>
    </form>
</div>
<script type="text/javascript">
function wpl_paypal_direct_send_request(transaction_id)
{
    var request = wplj('#wpl_paypal_direct_form'+transaction_id).serialize();
    
    var message_path = '#wpl_paypal_direct_message';
    var ajax_loader_element = "#wpl_paypal_direct_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    wpl_remove_message(message_path);
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: 'wpl_format=f:payments:ajax&wpl_function=paypal_direct&transaction_id='+transaction_id+'&'+request,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            if(response.success)
            {
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
                wplj(ajax_loader_element).html('');
                
                <?php foreach($this->system['javascript']['success'] as $js_success_code) echo $js_success_code."\n"; ?>
            }
            else
            {
                wpl_show_messages(response.message, message_path, 'wpl_red_msg');
                wplj(ajax_loader_element).html('');
                
                <?php foreach($this->system['javascript']['error'] as $js_error_code) echo $js_error_code."\n"; ?>
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