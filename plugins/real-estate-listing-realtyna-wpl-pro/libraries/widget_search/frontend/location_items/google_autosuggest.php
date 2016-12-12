<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'googleautosuggest' and !$done_this)
{
    // Load Google Maps API
    wpl_global::include_google_maps();

	/** current values **/
	$current_value = stripslashes(wpl_request::getVar('sf_locationtextsearch', ''));
	
	$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container_googleplaces_search">';
	$html .= '<input class="wpl_search_widget_location_textsearch" value="'.$current_value.'" name="sf'.$widget_id.'_locationtextsearch" id="sf'.$widget_id.'_locationtextsearch" placeholder="'.__($placeholder, 'wpl').'" />';
	$html .= '<script type="text/javascript">
    (function($){$(function()
    {
        var input = document.getElementById(\'sf'.$widget_id.'_locationtextsearch\');
        autocomplete = new google.maps.places.Autocomplete(input);
    });})(jQuery);
	</script>';
	$html .= '</div>';
	
	$done_this = true;
}