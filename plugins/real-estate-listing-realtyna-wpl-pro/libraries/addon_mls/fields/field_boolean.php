<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** MLS field mapper **/
class wpl_mls_boolean_map extends wpl_addon_mls_mapper
{
    /** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
        $wpl_value = 0;
		if(strtolower(trim($mls_value)) == 'y' or strtolower(trim($mls_value)) == 'yes' or strtolower(trim($mls_value)) == 't' or strtolower(trim($mls_value)) == 'true' or (is_numeric($mls_value) and $mls_value > 0)) $wpl_value = 1;
		
		return array('value'=>$wpl_value);
	}
}