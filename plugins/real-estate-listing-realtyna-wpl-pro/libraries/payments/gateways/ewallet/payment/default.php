<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->balance = $this->balance(NULL, $this->transaction['unit_id']);
$this->rendered = wpl_render::render_price($this->balance, $this->transaction['unit_id']);

$this->enough = ($this->balance >= $this->transaction['amount']) ? true : false;
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_payment_container" class="wpl_gateway_payment_container wpl-util-clearfix">
    <div class="wpl-util-grid-1-1">
        
        <div class="wpl-ewallet-balance">
            <?php echo sprintf(__('Your current balance is <span>%s</span>', 'wpl'), $this->rendered); ?>
        </div>
        
        <?php if($this->enough): ?>
        <div class="wpl-ewallet-pay">
            <input class="wpl-button button-1 wpl-button-medium" type="button" onclick="wpl_ewallet_send_request(<?php echo $this->transaction_id; ?>);" value="<?php echo __('Pay', 'wpl'); ?>" />
            <span id="wpl_ewallet_ajax_loader"></span>
            <span id="wpl_ewallet_message"></span>
        </div>
        <?php else: ?>
        <div class="wpl-ewallet-pay"><?php echo __('Your e-Wallet balance is low.', 'wpl'); ?></div>
        <?php endif; ?>
        
        <?php if(trim($this->settings['comment'])): ?>
        <p class="wpl-gateway-ewallet-comment">
            <?php echo __($this->settings['comment'], 'wpl'); ?>
        </p>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
function wpl_ewallet_send_request(transaction_id)
{
    var message_path = '#wpl_ewallet_message';
    var ajax_loader_element = "#wpl_ewallet_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: 'wpl_format=f:payments:ajax&wpl_function=ewallet_pay&transaction_id='+transaction_id+'&_wpnonce=<?php echo wpl_security::create_nonce('wpl_payments_ewallet_pay'); ?>',
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