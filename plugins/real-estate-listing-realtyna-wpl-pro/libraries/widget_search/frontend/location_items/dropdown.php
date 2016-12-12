<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'dropdown' and !$done_this)
{
    $levels = explode(',', $field['extoption']);
    if(count($levels) == 1 and trim($levels[0]) == '') $levels = array(2, 3);
    
    foreach($levels as $level)
    {
        $level = trim($level);
        if(!is_numeric($level)) continue;
        
        /** current values **/
        $current_value = stripslashes(wpl_request::getVar('sf_text_location'.$level.'_name', ''));
        
        $locations = wpl_db::select("SELECT `location".$level."_name` FROM `#__wpl_properties` WHERE `location".$level."_name`!='' GROUP BY `location".$level."_name` ORDER BY `location".$level."_name` ASC", 'loadColumn');
        
        $html .= '<label for="sf'.$widget_id.'_text_location'.$level.'_name">'.__($location_settings['location'.$level.'_keyword'], 'wpl').'</label>
        <select name="sf'.$widget_id.'_text_location'.$level.'_name" id="sf'.$widget_id.'_text_location'.$level.'_name" class="wpl_search_widget_field_'.$field['id'].'_select">
            <option value="" '.($current_value == '' ? 'selected="selected"' : '').'>'.__($location_settings['location'.$level.'_keyword'], 'wpl').'</option>';
        
        foreach($locations as $location) $html .= '<option value="'.$location.'" '.($current_value == $location ? 'selected="selected"' : '').'>'.__($location, 'wpl').'</option>';
        
        $html .= '</select>';
    }
	
	$done_this = true;
}