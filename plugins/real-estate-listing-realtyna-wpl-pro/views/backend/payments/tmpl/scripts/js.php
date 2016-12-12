<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
});

function wpl_payments_save_settings()
{
    var ajax_loader_element = "#wpl_slide_label_id10000 span";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    var param_str = '';
    wplj("#wpl_payments_settings_form input:checkbox").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		if(elm.checked) param_str += '1'; else param_str += '0';
	});
	
	wplj("#wpl_payments_settings_form input:text, #wpl_payments_settings_form input[type='number'], #wpl_payments_settings_form input[type='email'], #wpl_payments_settings_form input[type='hidden'], #wpl_payments_settings_form select, #wpl_payments_settings_form textarea").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		param_str += wplj(elm).val();
	});
    
    request_str = 'wpl_format=b:payments:ajax&wpl_function=save_settings&'+param_str+'&_wpnonce=<?php echo $this->nonce; ?>';
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'HTML', 'POST');

    ajax.success(function(data)
    {
        wplj(ajax_loader_element).html("<?php echo addslashes(__('Save', 'wpl')); ?>");
    });
}

function wpl_reset_transaction_form()
{
    wplj('#sf_filter').val('');
    wplj('#sf_select_mode').val('');
    wplj('#sf_select_status').val('');
    wplj('#wpl_payments_transactions_search_form').submit();
}

function wpl_transaction_bank_confirm(transaction_id, value)
{
    var ajax_loader_element = '#wpl_bank_receipt_ajax_loader'+transaction_id;
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    request_str = 'wpl_format=b:payments:ajax&wpl_function=confirm_bank_receipt&transaction_id='+transaction_id+'&value='+value+'&_wpnonce=<?php echo $this->nonce; ?>';
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);

    ajax.success(function(data)
    {
        wplj(ajax_loader_element).html('');
        
        if(data.success)
        {
            if(value)
            {
                wplj("#icon_disabled"+transaction_id).remove();
                wplj("#icon_enabled"+transaction_id).addClass('disable');
                wplj("#icon_enabled"+transaction_id).addClass('cursor-none');
                wplj("#icon_enabled"+transaction_id).attr('onclick', '');
                wplj("#icon_enabled"+transaction_id).attr('title', "<?php echo addslashes(__('Already Confirmed.', 'wpl')); ?>");
            }
            else
            {
                wplj("#icon_enabled"+transaction_id).remove();
                wplj("#icon_disabled"+transaction_id).addClass('disable');
                wplj("#icon_disabled"+transaction_id).addClass('cursor-none');
                wplj("#icon_disabled"+transaction_id).attr('onclick', '');
                wplj("#icon_disabled"+transaction_id).attr('title', "<?php echo addslashes(__('Already Rejected.', 'wpl')); ?>");
            }
        }
    });
}
</script>