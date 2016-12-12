<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<script id="wpl-js-confirm-message" type="text/x-handlebars-template">
    <div>{{message}}</div>
    <div class="wpl-addon-save-search-msg-btns">
        <span class="wpl-addon-save-search-yes-btn" {{onclick yes.func yes.param}}>{{yes.text}}</span>
        <span class="wpl-addon-save-search-no-btn" {{onclick no.func no.param}}>{{no.text}}</span>
    </div>
</script>

<script type="text/javascript">
Handlebars.registerHelper('onclick', function(funcName, funcParam)
{
    funcName = Handlebars.Utils.escapeExpression(funcName);
    funcParam =  '(' + funcParam.join(',') + ')';

    var result = 'onclick="' + funcName + funcParam +  '"';

    return new Handlebars.SafeString(result);
});

function wpl_addon_save_searches_delete_all(user_id, confirmed)
{
    var message_path = '#wpl_save_searches_list_show_messages';

    if(!confirmed)
	{
        var message = Handlebars.compile(wplj("#wpl-js-confirm-message").html())({
            message : '<?php echo __('Are you sure you want to remove all items?', 'wpl'); ?>',
            yes     : {
                text    : '<?php echo __('Yes', 'wpl'); ?>',
                func    : 'wpl_addon_save_searches_delete_all',
                param   : [user_id, 1]
            },
            no      : {
                text    :'<?php echo __('No', 'wpl'); ?>',
                func    : 'wpl_remove_message',
                param   : ['\''+ message_path+ '\'']
            }
        });

		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
    
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show("#wpl_addon_save_searches_delete_all", 'tiny', 'rightOut');
    
    wplj.ajax(
    {
        url: '<?php echo ($this->wplraw ? wpl_global::get_wp_url() : wpl_global::get_full_url()); ?>',
        data: 'wpl_format=f:addon_save_searches:ajax&wpl_function=delete&user_id='+user_id,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            if(response.success)
            {
                wplj("#wpl_addon_save_searches_list_container").remove();
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
            }
            else
            {
                wpl_show_messages(response.message, message_path, 'wpl_red_msg');
            }
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages("<?php echo addslashes(__('Error Occurred!', 'wpl')); ?>", message_path, 'wpl_red_msg');
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        }
    });
}

function wpl_addon_save_searches_delete(id, confirmed)
{
    var message_path = '#wpl_save_searches_list_show_messages';
    
    if(!confirmed)
	{
        var message = Handlebars.compile(wplj("#wpl-js-confirm-message").html())({
            message : '<?php echo __('Are you sure you want to remove this item?', 'wpl'); ?>',
            yes     : {
                text    : '<?php echo __('Yes', 'wpl'); ?>',
                func    : 'wpl_addon_save_searches_delete',
                param   : [id, 1]
            },
            no      : {
                text    :'<?php echo __('No', 'wpl'); ?>',
                func    : 'wpl_remove_message',
                param   : ['\''+ message_path+ '\'']
            }
        });

		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
    
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show("#wpl_addon_save_searches_delete"+id, 'tiny', 'rightOut');
    
    wplj.ajax(
    {
        url: '<?php echo ($this->wplraw ? wpl_global::get_wp_url() : wpl_global::get_full_url()); ?>',
        data: 'wpl_format=f:addon_save_searches:ajax&wpl_function=delete&id='+id,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            if(response.success)
            {
                wplj("#wpl_addon_save_search_item"+id).remove();
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
            }
            else
            {
                wpl_show_messages(response.message, message_path, 'wpl_red_msg');
            }
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages("<?php echo addslashes(__('Error Occurred!', 'wpl')); ?>", message_path, 'wpl_red_msg');
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        }
    });
}

function wpl_addon_save_searches_alias(id)
{
    var message_path = '#wpl_save_searches_list_show_messages';
    
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show("#wpl_addon_save_searches_alias"+id, 'tiny', 'rightIn');
    var alias = wplj("#wpl_addon_save_searches_alias"+id).val();
    
    wplj.ajax(
    {
        url: '<?php echo ($this->wplraw ? wpl_global::get_wp_url() : wpl_global::get_full_url()); ?>',
        data: 'wpl_format=f:addon_save_searches:ajax&wpl_function=alias&id='+id+'&alias='+alias,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            if(response.success)
            {
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
                if(response.data.url) wplj("#wpl_addon_save_searches_link"+id).attr('href', response.data.url);
            }
            else
            {
                wpl_show_messages(response.message, message_path, 'wpl_red_msg');
            }
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages("<?php echo addslashes(__('Error Occurred!', 'wpl')); ?>", message_path, 'wpl_red_msg');
            
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
        }
    });
}
</script>