<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_payment_container" class="wpl_gateway_payment_container wpl-util-clearfix">
    <div class="wpl-util-grid-1-1">
        <p class="wpl-gateway-bank-comment">
            <?php echo __($this->settings['comment'], 'wpl'); ?>
        </p>
        <form id="wpl_bank_form<?php echo $this->transaction_id; ?>" onsubmit="wpl_bank_send_request(<?php echo $this->transaction_id; ?>); return false;">
            <div class="wpl-util-form-row">
                <label for="wpl_bank_receipt"><?php echo __('Bank Receipt', 'wpl'); ?></label>
                <input type="text" name="receipt" id="wpl_bank_receipt" />
            </div>
            
            <?php wpl_security::nonce_field('wpl_payments_bank_receipt'); ?>
            
            <div class="wpl-util-form-row">
                <input type="submit" class="btn btn-primary wpl-button button-1 wpl-button-medium wpl-util-float-right" value="<?php echo __('Send', 'wpl'); ?>" />
                <span id="wpl_bank_ajax_loader"></span>
            </div>

            <div class="wpl-util-form-row">
                <span class="wpl-util-form-messages" id="wpl_bank_message"></span>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function wpl_bank_send_request(transaction_id)
{
    var message_path = '#wpl_bank_message';
    wpl_remove_message(message_path);
    
    var bank_receipt = wplj("#wpl_bank_receipt").val();
    if(bank_receipt == '')
    {
        wpl_show_messages("<?php echo __('Please insert bank receipt!', 'wpl'); ?>", message_path, 'wpl_red_msg');
        return false;
    }
    
    var request = wplj('#wpl_bank_form'+transaction_id).serialize();
    
    var ajax_loader_element = "#wpl_bank_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: 'wpl_format=f:payments:ajax&wpl_function=bank_receipt&transaction_id='+transaction_id+'&'+request,
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