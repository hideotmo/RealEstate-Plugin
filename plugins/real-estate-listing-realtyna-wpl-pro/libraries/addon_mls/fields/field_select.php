<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');

/** MLS field mapper **/
class wpl_mls_select_map extends wpl_addon_mls_mapper
{
    /** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
        $select_options = json_decode($wpl_field['options'], true);
        
        $wpl_value = NULL;
        $max_id = 0;
        foreach($select_options['params'] as $item)
        {
            if(strtolower(trim($item['value'])) == strtolower(trim($mls_value))) $wpl_value = $item['key'];
            if($mls_value == $item['value']) $wpl_value = $item['key'];
            $max_id = max($max_id, $item['key'])+1;
        }
        
        /** Add the option into field options **/
        if(is_null($wpl_value))
        {
            $select_options['params'][$max_id] = array('value'=>ucfirst(strtolower($mls_value)), 'key'=>$max_id, 'enabled'=>1);
            wpl_flex::update('wpl_dbst', $wpl_field['id'], 'options', json_encode($select_options));
            
            $wpl_value = $max_id;
        }
		
		return array('value'=>$wpl_value);
	}
}