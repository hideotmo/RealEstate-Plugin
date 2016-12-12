<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var freeze = false;

// Handle window scroll event
wplj(window).scroll(function()
{
	if(freeze) return;
    
    // get full height of div (from top and actual div height).
	var elem = wplj('#wpl_profile_listing_container');
	var divend = elem[0].scrollHeight + elem.offset().top;
    
	if(wplj(window).scrollTop() >= wplj(document).height() - wplj(window).height() - 50 || wplj(window).scrollTop() >= divend - 300)
	{
        freeze = true;
        
		wpl_listing_current_page += 1;
        
		if(wpl_listing_current_page > wpl_listing_total_pages)
        {
            freeze = false;
            return;
        }
        
        var ajax_loader = '<div id="scroll_pagination_loader" class="clear" title="<?php echo __('Loading ...', 'wpl'); ?>"><ul class="bokeh"><li></li><li></li><li></li><li></li></ul></div>';
		wplj("#wpl_profile_listing_container").append(ajax_loader);
		
        wpl_listing_request_str = wpl_update_qs('wplpage', wpl_listing_current_page, wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs('wplpcc', wpl_current_property_css_class, wpl_listing_request_str);
        
		var request_str = 'wpl_format=f:profile_listing:raw&'+wpl_listing_request_str;
        
        try
        {
            var current_js_url = window.location.href;
            current_js_url = wpl_update_qs('wplpage', wpl_listing_current_page, current_js_url);
            history.pushState({wplpage: wpl_listing_current_page}, "page "+wpl_listing_current_page, current_js_url);
        }
        catch(err){}
        
		wplj.ajax(
		{
			url: '<?php echo wpl_global::get_full_url(); ?>',
			data: request_str,
			type: 'GET',
			async: true,
			cache: false,
			timeout: 30000,
			success: function(data)
			{
                wplj("#scroll_pagination_loader").remove();
				wplj(".wpl_profile_listing_list_view_container").append(data);
				
				freeze = false;
			}
		});
	}
});
</script>